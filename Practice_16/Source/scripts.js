document.addEventListener('DOMContentLoaded', function() {
    const addTaskForm = document.getElementById('add-task-form');
    const newTaskInput = document.getElementById('new-task-input');
    const taskList = document.getElementById('task-list');
    
    let taskCounter = 1;
    let currentTaskId = 1;

    addTaskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const taskText = newTaskInput.value.trim();
        if (taskText === '') { alert('Введите текст задачи!'); return; }
        
        const taskId = ++taskCounter;
        const taskItem = createTaskItem(taskId, taskText);
        taskList.insertBefore(taskItem, taskList.firstChild);
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
            createMoreTasks();
            return;
        }
    });

    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('task-edit-form')) {
            e.preventDefault();
            const taskItem = e.target.closest('.task-item');
            const newText = e.target.querySelector('input[name="edited-task"]').value.trim();
            
            if (newText === '') { alert('Задача не может быть пустой!'); return; }
            
            taskItem.querySelector('.task-text').textContent = newText;
            e.target.querySelector('input').value = newText;
            
            taskItem.querySelector('.task-view').style.display = 'flex';
            taskItem.querySelector('.task-edit-form').style.display = 'none';
        }
    });

    function createTaskItem(id, text) {
        const li = document.createElement('li');
        li.className = 'task-item';
        li.dataset.taskId = id;
        li.innerHTML = `
            <div class="task-view">
                <span class="task-text">${escapeHtml(text)}</span>
                <button class="edit-task-button" type="button">Редактировать</button>
                <button class="delete-task-button" type="button">Удалить</button>
            </div>
            <form class="task-edit-form">
                <input type="text" name="edited-task" value="${escapeHtml(text)}">
                <button type="submit">Сохранить</button>
                <button type="button" class="cancel-edit-button">Отмена</button>
            </form>
        `;
        return li;
    }

    // function createMoreTasks() {
    //     const tasksCount = 125;
    //     currentTaskId = 1;
        
    //     for(let i = 0; i < tasksCount; i++) {
    //         const taskText = `Это шаблонный пример ${i + 1}`;
    //         const taskItem = createTaskItem(currentTaskId, taskText);
    //         taskList.insertBefore(taskItem, taskList.firstChild);
    //         currentTaskId++;
    //     }
        
    //     console.log(`Создано ${tasksCount} задач!`);
    // }

    function createMoreTasks() {
        const tasksCount = 125;
        currentTaskId = 1;
        
        const fragment = document.createDocumentFragment();
        
        for(let i = 0; i < tasksCount; i++) {
            const taskText = `Это шаблонный пример ${i + 1}`;
            const taskItem = createTaskItem(currentTaskId, taskText);
            fragment.appendChild(taskItem); 
            currentTaskId++;
        }
        
        taskList.insertBefore(fragment, taskList.firstChild);
        
        console.log(` Оптимизировано: ${tasksCount} задач за 1 DOM-вызов!`);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});