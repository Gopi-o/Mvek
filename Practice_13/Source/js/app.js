import { initTaskManager } from "./taskManager.js";
import { summa, appeal } from "./customScripts/ignition_turboFan.js";

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('../service-worker.js')
            .then(registration => {
                console.log('Service Worker зарегистрирован', registration);
            })
            .catch(error => {
                console.error('Ошибка регистрации Service Worker:', error);
            });
    });
}

async function measureRequestTime(url) {
    const start = performance.now();
    await fetch(url);
    const end = performance.now();
    return end - start;
}

measureRequestTime('../style.css')
    .then(time => {
        console.log(`Время запроса: ${time} мс`);
    });

document.addEventListener('DOMContentLoaded', function() {
    const addTaskForm = document.getElementById('add-task-form');
    const newTaskInput = document.getElementById('new-task-input');
    const taskList = document.getElementById('task-list');

    // {
        const obj1 = {
            name: 'Alice',
            age: 30,
            city: 'Paris1'
        };
    
    //     const obj2 = {
    //         age: 30,
    //         city: 'Paris2',
    //         name: 'Alice'
    //     };
    
    //     const obj3 = {
    //         city: 'Paris3',
    //         name: 'Alice',
    //         age: 30
    //     };
    
    //     const objSim = {
    //         name: 'Alice',
    //         age: 30,
    //         city: 'Paris1'
    //     };
    
    //     const objlarge = {
    //         age: 30,
    //         city: 'Paris2',
    //         f_name: '123',
    //         s_name: '456',
    //         l_name: '789',
    //         salary: 1234,
    //         pass: 'qwerty',
    //         name: 'Alice'
    //     }
    // }



    


    
    const taskManager = initTaskManager(taskList);
    
    addTaskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const taskText = newTaskInput.value.trim();
        if (taskText === '') { 
            alert('Введите текст задачи!'); 
            return; 
        }
        taskManager.addSingleTask(taskText);
        newTaskInput.value = '';
    });
    
    document.addEventListener('click', function(e) {
        const taskItem = e.target.closest('.task-item');
        
        if (e.target.classList.contains('delete-task-button')) {
            taskItem.remove();
            return;
        }
        
        if (e.target.classList.contains('edit-task-button')) {
            taskItem.querySelector('.task-view').style.display = 'none';
            taskItem.querySelector('.task-edit-form').style.display = 'flex';
            return;
        }

        
        if (e.target.classList.contains('add-many-tasks-button')) {
            taskManager.loadTasksFromServer(125, 25);
            // taskManager.addManyTasksChunked(125, 25);
            return;
        }

        if (e.target.classList.contains('filter-heavy-button')) {
            taskManager.filterTasksHeavy();
            return;
        }

        if (e.target.classList.contains('filter-chunked-button')) {
            taskManager.filterTasksChunked();
            return;
        }

        if (e.target.classList.contains('custom-button')) {
            // console.log(summa(Math.random()*120, Math.random()*120));
            // console.log(summa(Math.random()*120, '15'));
            // for (let i = 20; i > 0; i--)
            // {
            //     console.log(appeal(obj1));
            //     console.log(appeal(obj2));
            //     console.log(appeal(obj3));
            //     console.log("\n");
            // }
            // console.time('obj1.city');
            // for (let i = 0; i < 1e6; i++) appeal(obj1);
            // console.timeEnd('obj1.city'); 

            // console.time('obj2.city');
            // for (let i = 0; i < 1e6; i++) appeal(obj2);
            // console.timeEnd('obj2.city');

            // console.time('objsim1.city');
            // for (let i = 0; i < 1e6; i++) appeal(obj1);
            // console.timeEnd('objsim1.city'); 

            // console.time('objsim2.city');
            // for (let i = 0; i < 1e6; i++) appeal(objSim);
            // console.timeEnd('objsim2.city'); 

            // console.time('objLarge.city');
            // for (let i = 0; i < 1e6; i++) appeal(objlarge);
            // console.timeEnd('objLarge.city'); 

            // console.time('create(1)');
            // const user1 = {};
            // user1.name = 'Анна';
            // user1.age = 28;
            // user1.email = 'anna@example.com';
            // user1.isActive = true;
            // console.timeEnd('create(1)'); 

            // console.time('create(2)');
            // const user2 = {
            // name: 'Борис',
            // age: 35,
            // email: 'boris@example.com',
            // isActive: false
            // };
            // console.timeEnd('create(2)'); 

            // console.time('create(1)');
            // const mixedArray = [42, 'Hello', { name: 'Alice', age: 30 }, true, 3.14, null, [1, 2, 3],  false];
            // console.log(mixedArray);
            // console.timeEnd('create(1)'); 

            // console.time('create(2)');
            // let sum = 0;
            // const numberArray = [1, 5, 10, 15, 20, 25, 30, 32, 2];
            // // numberArray.forEach(function(elem) { 
            // //     // if(elem === 156)
            // //     //     return;
            // //     // if(elem === 'nunNumbers')
            // //     //     return;
            // //     // if(elem === obj1.city)
            // //     //     return;
            // //     sum = summa(sum, elem); });
            // // for (let i = 0; i < numberArray.length; i += 3) {
            // //     sum = summa(sum, numberArray.slice(i, i + 3).reduce((a, b) => a + b, 0));
            // // }
            // numberArray.forEach(function(elem) {
            //     let obj = { value: elem };
            //     sum += obj.value;
            // });
            // console.log(sum);
            // console.timeEnd('create(2)'); 



            //     const asyncTasks = [
            //     async () => {
            //         await new Promise(resolve => setTimeout(() => {
            //             console.log('Задача 1 выполнена');
            //             resolve();
            //         }, 1000));
            //     },
            //     async () => {
            //         await new Promise(resolve => setTimeout(() => {
            //             console.log('Задача 2 выполнена');
            //             resolve();
            //         }, 1500));
            //     },
            //     async () => {
            //         await new Promise(resolve => setTimeout(() => {
            //             console.log('Задача 3 выполнена');
            //             resolve();
            //         }, 2000));
            //     },
            //     async () => {
            //         await new Promise(resolve => setTimeout(() => {
            //             console.log('Задача 4 выполнена');
            //             resolve();
            //         }, 1200));
            //     }
            // ];
            // taskManager.runAsyncTasks(asyncTasks);



            const largeArray = Array.from({length: 100}, (_, i) => i);
            taskManager.processArrayInChunks(largeArray, 10, 100)
            return;
        }

    });
    
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('task-edit-form')) {
            e.preventDefault();
            const taskItem = e.target.closest('.task-item');
            const newText = e.target.querySelector('input[name="edited-task"]').value.trim();
            
            if (newText === '') { 
                alert('Задача не может быть пустой!'); 
                return; 
            }
            
            taskItem.querySelector('.task-text').textContent = newText;
            e.target.querySelector('input').value = newText;
            taskItem.querySelector('.task-view').style.display = 'flex';
            taskItem.querySelector('.task-edit-form').style.display = 'none';
        }
    });
});