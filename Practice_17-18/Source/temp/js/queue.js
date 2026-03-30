
class SimpleQueue {
    constructor() {
        this.tasks = [];         
        this.isWorking = false;   
        this.handlers = {};        
        this.stats = {
            processed: 0,
            failed: 0
        };
    }

    register(type, handler) {
        this.handlers[type] = handler;
        console.log(`[Queue] Handler registered: ${type}`);
    }

    add(type, data) {
        const task = {
            id: Date.now() + '_' + Math.random().toString(36).substr(2, 6),
            type: type,
            data: data,
            createdAt: new Date().toISOString()
        };
        
        this.tasks.push(task);
        console.log(`[Queue] Task added: ${type} (${task.id})`);
        
        this.worker();
        
        return task.id;
    }

    async worker() {
        if (this.isWorking) return;
        if (this.tasks.length === 0) return;
        
        this.isWorking = true;
        
        while (this.tasks.length > 0) {
            const task = this.tasks.shift();
            
            console.log(`[Worker] Processing: ${task.type} (${task.id})`);
            
            try {
                const handler = this.handlers[task.type];
                if (!handler) {
                    throw new Error(`No handler for type: ${task.type}`);
                }
                
                const startTime = Date.now();
                const result = await handler(task.data);
                const duration = Date.now() - startTime;
                
                this.stats.processed++;
                console.log(`[Worker]  Done: ${task.type} in ${duration}ms`);
                
                this.emit('complete', { task, result, duration });
                
            } catch (error) {
                this.stats.failed++;
                console.error(`[Worker]  Failed: ${task.type}`, error.message);
                this.emit('error', { task, error });
            }
        }
        
        this.isWorking = false;
        console.log(`[Worker] Queue empty. Stats: ${this.stats.processed} done, ${this.stats.failed} failed`);
        this.emit('empty');
    }

    on(event, callback) {
        if (!this._events) this._events = {};
        if (!this._events[event]) this._events[event] = [];
        this._events[event].push(callback);
    }
    
    emit(event, data) {
        if (!this._events || !this._events[event]) return;
        this._events[event].forEach(cb => cb(data));
    }
    
    getStats() {
        return {
            pending: this.tasks.length,
            processed: this.stats.processed,
            failed: this.stats.failed,
            isWorking: this.isWorking
        };
    }
    
    clear() {
        const count = this.tasks.length;
        this.tasks = [];
        console.log(`[Queue] Cleared ${count} tasks`);
        return count;
    }
}

const queue = new SimpleQueue();

async function sendEmail(emailData) {
    console.log(` Sending email to: ${emailData.to}`);
    console.log(`   Subject: ${emailData.subject}`);
    console.log(`   Body: ${emailData.body}`);
    
    await new Promise(resolve => setTimeout(resolve, 500 + Math.random() * 1000));
    
    if (Math.random() < 0.1) {
        throw new Error(`Failed to send email to ${emailData.to}`);
    }
    
    console.log(` Email sent successfully to ${emailData.to}`);
    return { success: true, messageId: Date.now() };
}

queue.register('order:notification', async (data) => {
    const emailData = {
        to: data.userEmail,
        subject: `Заказ #${data.orderId} подтвержден`,
        body: `
            Здравствуйте!
            
            Ваш заказ #${data.orderId} успешно оформлен.
            
            Состав заказа:
            ${data.items.map(item => `- ${item.title} x ${item.quantity} = ${item.price * item.quantity} ₽`).join('\n')}
            
            Итого: ${data.total} ₽
            
            Спасибо за покупку!
        `
    };
    
    return await sendEmail(emailData);
});

window.Queue = queue;
window.OrderManager = {
    createOrder(orderData) {
        const order = {
            id: Date.now(),
            ...orderData,
            status: 'pending',
            createdAt: new Date().toISOString()
        };
        
        const orders = JSON.parse(localStorage.getItem('orders') || '[]');
        orders.push(order);
        localStorage.setItem('orders', JSON.stringify(orders));
        
        const taskId = queue.add('order:notification', {
            orderId: order.id,
            userEmail: orderData.userEmail,
            items: orderData.items,
            total: orderData.total
        });
        
        console.log(`Order ${order.id} created, notification queued: ${taskId}`);
        
        return { order, taskId };
    },
    
    getOrders() {
        return JSON.parse(localStorage.getItem('orders') || '[]');
    }
};

queue.on('complete', ({ task }) => {
    console.log(` Event: Task ${task.type} completed`);
});

queue.on('error', ({ task, error }) => {
    console.warn(` Event: Task ${task.type} failed - ${error.message}`);
});

queue.on('empty', () => {
    console.log(' Event: All tasks processed');
});

console.log('Simple Queue initialized!');
console.log('Commands: Queue.getStats(), OrderManager.createOrder({...})');