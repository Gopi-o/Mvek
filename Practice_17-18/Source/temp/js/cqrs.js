const QueryDB = {
    cache: new Map(),
    cacheTTL: 5 * 60 * 1000, 
    
    get(key, loader) {
        const cached = this.cache.get(key);
        if (cached && Date.now() - cached.time < this.cacheTTL) {
            console.log('[QUERY] Cache hit:', key);
            return cached.data;
        }
        
        console.log('[QUERY] Cache miss:', key);
        const data = loader();
        this.cache.set(key, { data, time: Date.now() });
        return data;
    },
    
    invalidate(key) {
        this.cache.delete(key);
        console.log('[QUERY] Cache invalidated:', key);
    },
    
    clear() {
        this.cache.clear();
        console.log('[QUERY] Cache cleared');
    }
};

const CommandDB = {
    events: [],
    
    save(key, data) {
        if (!this.validate(data)) {
            throw new Error('Validation failed');
        }
        
        const previous = localStorage.getItem(key);
        localStorage.setItem(key, JSON.stringify(data));
        
        const event = {
            type: previous ? 'UPDATED' : 'CREATED',
            key: key,
            timestamp: new Date().toISOString(),
            user: this.getCurrentUser(),
            changes: this.getChanges(previous ? JSON.parse(previous) : null, data)
        };
        
        this.events.push(event);
        this.saveEvent(event);
        
        QueryDB.invalidate(key);
        
        console.log('[COMMAND] Saved:', key, event.type);
        return { success: true, event };
    },
    
    delete(key) {
        const previous = localStorage.getItem(key);
        localStorage.removeItem(key);
        
        const event = {
            type: 'DELETED',
            key: key,
            timestamp: new Date().toISOString(),
            user: this.getCurrentUser(),
            previousData: previous ? JSON.parse(previous) : null
        };
        
        this.events.push(event);
        this.saveEvent(event);
        QueryDB.invalidate(key);
        
        console.log('[COMMAND] Deleted:', key);
        return { success: true, event };
    },
    
    validate(data) {
        if (!data) return false;
        if (typeof data !== 'object') return false;
        return true;
    },
    
    getCurrentUser() {
        return localStorage.getItem('currentUser') || 'anonymous';
    },
    
    getChanges(oldData, newData) {
        if (!oldData) return { all: newData };
        
        const changes = {};
        for (const key in newData) {
            if (JSON.stringify(oldData[key]) !== JSON.stringify(newData[key])) {
                changes[key] = { from: oldData[key], to: newData[key] };
            }
        }
        return changes;
    },
    
    saveEvent(event) {
        const events = JSON.parse(localStorage.getItem('audit_log') || '[]');
        events.push(event);
        if (events.length > 100) events.shift();
        localStorage.setItem('audit_log', JSON.stringify(events));
    },
    
    getAuditLog(filter = {}) {
        const events = JSON.parse(localStorage.getItem('audit_log') || '[]');
        if (filter.type) return events.filter(e => e.type === filter.type);
        if (filter.key) return events.filter(e => e.key === filter.key);
        return events;
    }
};

export const CatalogAPI = {
    getProduct(id) {
        return QueryDB.get(`product_${id}`, () => {
            const data = localStorage.getItem(`product_${id}`);
            return data ? JSON.parse(data) : null;
        });
    },
    
    getAllProducts() {
        return QueryDB.get('all_products', () => {
            const products = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key?.startsWith('product_')) {
                    products.push(JSON.parse(localStorage.getItem(key)));
                }
            }
            return products;
        });
    },
    
    createProduct(id, data) {
        return CommandDB.save(`product_${id}`, { id, ...data, createdAt: new Date().toISOString() });
    },
    
    updateProduct(id, data) {
        const existing = this.getProduct(id);
        if (!existing) throw new Error('Product not found');
        return CommandDB.save(`product_${id}`, { ...existing, ...data, updatedAt: new Date().toISOString() });
    },
    
    deleteProduct(id) {
        return CommandDB.delete(`product_${id}`);
    },
    
    getAuditLog: CommandDB.getAuditLog.bind(CommandDB),
    
    getStats() {
        return {
            cachedItems: QueryDB.cache.size,
            totalEvents: CommandDB.events.length,
            storageUsed: (JSON.stringify(localStorage).length / 1024).toFixed(2) + ' KB'
        };
    }
};

window.CatalogAPI = CatalogAPI;
window.QueryDB = QueryDB;
window.CommandDB = CommandDB;