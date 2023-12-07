document.addEventListener('DOMContentLoaded', function () {
    // Message div for displaying success/error messages
    const messageDiv = document.createElement('div');
    messageDiv.id = 'message';
    document.body.appendChild(messageDiv);

    // Handle create news form submission
    const createForm = document.getElementById('createNewsForm');
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            sendFormData(formData);
        });
    }

    // Setup listeners for each news item
    document.querySelectorAll('.news-item').forEach(newsItem => {
        setupNewsItemListeners(newsItem);
    });

    function setupNewsItemListeners(newsItem) {
        const editIcon = newsItem.querySelector('.edit-icon');
        const deleteIcon = newsItem.querySelector('.delete-icon');

        editIcon.addEventListener('click', () => toggleEditState(newsItem, true));
        deleteIcon.addEventListener('click', () => handleDelete(newsItem));
    }

    function toggleEditState(newsItem, isEditState) {
        const titleElement = isEditState ? newsItem.querySelector('.news-title') : newsItem.querySelector('.edit-title');
        const descriptionElement = isEditState ? newsItem.querySelector('.news-description') : newsItem.querySelector('.edit-description');
        const actionIcon = newsItem.querySelector(isEditState ? '.edit-icon' : '.save-icon');
        const closeIcon = newsItem.querySelector(isEditState ? '.delete-icon' : '.close-icon');

        if (!titleElement || !descriptionElement || !actionIcon || !closeIcon) return;

        if (isEditState) {
            // Switch to edit mode
            const currentTitle = titleElement.textContent;
            const currentDescription = descriptionElement.textContent;

            titleElement.outerHTML = `<input type="text" class="edit-title" value="${currentTitle}">`;
            descriptionElement.outerHTML = `<textarea class="edit-description">${currentDescription}</textarea>`;
            actionIcon.src = 'public/images/arrow.svg';
            actionIcon.className = 'save-icon';
            closeIcon.src = 'public/images/close.svg';
            closeIcon.className = 'close-icon';

            // Update event listeners
            actionIcon.onclick = () => handleSave(newsItem);
            closeIcon.onclick = () => toggleEditState(newsItem, false);
        } else {
            // Revert to display mode
            const editedTitle = titleElement.value;
            const editedDescription = descriptionElement.value;

            newsItem.innerHTML = `
                <div class="news-display">
                    <span class="news-title">${editedTitle}</span>
                    <span class="news-description">${editedDescription}</span>
                    <span class="news-actions">
                        <img src="public/images/pencil.svg" alt="Edit" class="edit-icon" />
                        <img src="public/images/delete.svg" alt="Delete" class="delete-icon" />
                    </span>
                </div>`;

            setupNewsItemListeners(newsItem); // Re-setup listeners for this news item
        }
    }


    function handleSave(newsItem) {
        const updatedTitle = newsItem.querySelector('.edit-title').value;
        const updatedDescription = newsItem.querySelector('.edit-description').value;
        const newsId = newsItem.getAttribute('data-id');
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('id', newsId);
        formData.append('title', updatedTitle);
        formData.append('description', updatedDescription);
        sendFormData(formData);
    }

    function handleDelete(newsItem) {
        if (!newsItem.querySelector('.edit-title')) {
            // Only proceed if not in edit mode
            const newsId = newsItem.getAttribute('data-id');
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', newsId);
            sendFormData(formData);
        }
    }

    async function sendFormData(formData) {
        try {
            const response = await fetch('admin.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            displayMessage(data.message, data.success);
            if (data.success) {
                setTimeout(() => location.reload(), 4000);  // Refresh the page on success
            }
        } catch (error) {
            console.error('Error:', error);
            displayMessage('Failed to perform the action.', false);
        }
    }

    function displayMessage(message, isSuccess) {
        const messageContainer = document.querySelector('.message-container');
        messageContainer.textContent = message;
        messageContainer.className = isSuccess ? 'message-container success' : 'message-container error';
        messageContainer.style.display = 'block';
        setTimeout(() => {
            messageContainer.style.display = 'none';
        }, 4000); // Hide the message after 4 seconds
    }

});
