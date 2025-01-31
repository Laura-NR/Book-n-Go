const editButton = document.getElementById('editButton');
const editForm = document.getElementById('editForm');

if (editButton) {
    editButton.addEventListener('click', () => {
        editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
    });
}

feather.replace();