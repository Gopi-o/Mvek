import { createTaskItem } from "./taskFactory.js";


export function initTaskManager(taskList) {
    let currentTaskId = 1;
    let currentAbortController = null;
    
    function addSingleTask(taskText) {
        const taskId = ++currentTaskId;
        const taskItem = createTaskItem(taskId, taskText);
        taskList.insertBefore(taskItem, taskList.firstChild);
        return taskId;
    }

    function simulateServerRequest(count, chunkSize, signal) {
        const chunks = Math.ceil(count / chunkSize);
        const promises = [];
        
        for (let i = 0; i < chunks; i++) {
            promises.push(
                new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => {
                        const chunkStart = i * chunkSize;
                        const chunkEnd = Math.min(chunkStart + chunkSize, count);
                        const chunkTasks = [];
                        
                        for (let j = chunkStart; j < chunkEnd; j++) {
                            chunkTasks.push({
                                id: ++currentTaskId,
                                text: `Сервер: Чанк ${i + 1} Задача ${j + 1}`
                            });
                        }
                        resolve(chunkTasks);
                    }, Math.random() * 500 + 200);
                    
                    signal.addEventListener('abort', () => {
                        clearTimeout(timeout);
                        reject(new DOMException('Aborted', 'AbortError'));
                    });
                })
            );
        }
        
        return Promise.all(promises);
    }

    async function loadTasksFromServer(count = 125, chunkSize = 25) {
        if (currentAbortController) {
            currentAbortController.abort();
        }
        
        currentAbortController = new AbortController();
        const signal = currentAbortController.signal;
        
        try {
            taskList.innerHTML = '';
            currentTaskId = 0;
            
            const chunks = await simulateServerRequest(count, chunkSize, signal);
            
            for (const chunk of chunks) {
                if (signal.aborted) break;
                
                const fragment = document.createDocumentFragment();
                for (const task of chunk) {
                    const taskItem = createTaskItem(task.id, task.text);
                    fragment.appendChild(taskItem);
                }
                taskList.insertBefore(fragment, taskList.firstChild);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        }
    }

    function addMoreTasks_NP(count = 125) {
        const tasksCount = 125;
        currentTaskId = 1;
        
        for(let i = 0; i < tasksCount; i++) {
            const taskText = `Это шаблонный пример ${i + 1}`;
            const taskItem = createTaskItem(currentTaskId, taskText);
            taskList.insertBefore(taskItem, taskList.firstChild);
            currentTaskId++;
        }
        
        console.log(`Создано ${tasksCount} задач!`);
    }
    
    function addManyTasks(count = 125) {
        currentTaskId = 1;
        const fragment = document.createDocumentFragment();
        
        for(let i = 0; i < count; i++) {
            const taskText = `Это шаблонный пример ${i + 1}`;
            const taskItem = createTaskItem(currentTaskId, taskText);
            fragment.appendChild(taskItem);
            currentTaskId++;
        }
        
        taskList.insertBefore(fragment, taskList.firstChild);
        console.log(`Оптимизировано: ${count} задач за 1 DOMвызов!`);
    }

    async function addManyTasksChunked(count = 125, chunkSize = 25) {
        currentTaskId = 1;
        console.log(` Запуск ${count} задач по ${chunkSize}`);
        
        for (let i = 0; i < count; i += chunkSize) {
            const chunk = document.createDocumentFragment();
            const end = Math.min(i + chunkSize, count);
            
            for (let j = i; j < end; j++) {
                const taskText = `Чанк: ${Math.floor(j/25)+1} Задача ${j + 1}`;
                const taskItem = createTaskItem(currentTaskId, taskText);
                chunk.appendChild(taskItem);
                currentTaskId++;
            }
            
            taskList.insertBefore(chunk, taskList.firstChild);
            console.log(`Добавлен чанк ${Math.floor(i/25)+1}/${Math.ceil(count/chunkSize)}`);
            
            await new Promise(resolve => setTimeout(resolve, 0));
        }
        
        console.log(`Завершено ${count} задач!`);
    }



    function filterTasksHeavy() {
        console.time('Long Task');
        const allTasks = Array.from(taskList.querySelectorAll('.task-item'));
        const filtered = allTasks.filter(task => {
            const text = task.querySelector('.task-text').textContent;
            let score = 0;
            for(let i = 0; i < 1000000; i++) score += Math.sin(i) * 0.0001;
            return text.includes('Чанк') || score > 0.5;
        });
        
        allTasks.forEach(task => task.style.display = 'none');
        filtered.forEach(task => task.style.display = '');
        
        console.timeEnd('Long Task'); 
    }

    async function filterTasksChunked(chunkSize = 10) {
        console.time('Chunked Filter');
        const allTasks = Array.from(taskList.querySelectorAll('.task-item'));
        
        for(let i = 0; i < allTasks.length; i += chunkSize) {
            const chunk = allTasks.slice(i, i + chunkSize);
            
            chunk.forEach(task => {
                const text = task.querySelector('.task-text').textContent;
                let score = 0;
                for(let j = 0; j < 1000000; j++) score += Math.sin(j) * 0.0001;
                
                task.dataset.filterScore = score;
                task.style.display = (text.includes('Чанк') || score > 0.5) ? '' : 'none';
            });
            
            await new Promise(resolve => setTimeout(resolve, 0));
        }
        
        console.timeEnd('Chunked Filter');
    }


    
    async function runLimitedParallel(tasks, limit) {
        const running = [];
        const results = [];

        for (const task of tasks) {
            const promise = task()
            if (running.length >= limit) {
                await Promise.race(running);
            }

            running.push(promise);
            results.push(promise);

            const index = running.indexOf(promise);
            if (index !== -1) {
                running.splice(index, 1);
            }
        }

        await Promise.all(results);
    }

    
    async function runAsyncTasks(asyncTasks) {
        console.time('create(1)');
        await Promise.all(asyncTasks.map(task => task()));
        console.timeEnd('create(1)'); 

        console.time('create(2)');
        await runLimitedParallel(asyncTasks, 2);
        console.timeEnd('create(2)'); 
    }

    async function processArrayInChunks(array, chunkSize = 10, delay = 100) {
        for (let i = 0; i < array.length; i += chunkSize) {
            const end = Math.min(i + chunkSize, array.length);
            const chunk = array.slice(i, end);
            
            await processChunk(chunk);
            
            await sleep(delay);
        }
    }

    async function processChunk(chunk) {
        for (const item of chunk) {
            console.log(`Обрабатываем элемент: ${item} → ${item * 2}`);
        }
    }

    async function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    return {
        addSingleTask,
        addManyTasks,
        addManyTasksChunked,
        filterTasksHeavy,
        filterTasksChunked,
        loadTasksFromServer,
        runAsyncTasks,
        processArrayInChunks
    };
}