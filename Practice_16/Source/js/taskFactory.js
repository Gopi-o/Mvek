export function createTaskItem(id, text) {
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

// export function createTaskItem(id, text) {
//     const li = document.createElement('li');
//     li.className = 'task-item';
//     li.dataset.taskId = id;
//     li.innerHTML = `
//         <div class="task-view">
//             <span class="task-text">${escapeHtml(text)}</span>
//             <button class="edit-task-button" type="button">Редактировать</button>
//             <button class="delete-task-button" type="button">Удалить</button>
//         </div>
//         <form class="task-edit-form">
//             <input type="text" name="edited-task" value="${escapeHtml(text)}">
//             <button type="submit">Сохранить</button>
//             <button type="button" class="cancel-edit-button">Отмена</button>
//         </form>
//     `;
//     const deleteBtn = li.querySelector('.delete-task-button');
//     const editBtn = li.querySelector('.edit-task-button');
    
//     const handleDelete = function() { li.remove(); };
//     const handleEdit = function() { 
//         li.querySelector('.task-view').style.display = 'none';
//         li.querySelector('.task-edit-form').style.display = 'flex';
//     };
    
//     deleteBtn.addEventListener('click', handleDelete);
//     editBtn.addEventListener('click', handleEdit);
    
//     li._listeners = { handleDelete, handleEdit };
    
//     return li;
// }

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}