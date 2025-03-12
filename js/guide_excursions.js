document.addEventListener('DOMContentLoaded', function() {
    // Les notifications vont disparaitre après 5 secondes si l'utilisateur ne les ferme pas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        setTimeout(() => {
            bsAlert.close();
        }, 5000);
    });

    // Code pour le modal de suppression
    const deleteModal = document.getElementById('deleteModal');
    const deleteLinks = document.querySelectorAll('.delete-link');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');

    
    deleteLinks.forEach(deleteLink => {
        deleteLink.addEventListener('click', function(event) {
            event.preventDefault();
            const excursionId = this.dataset.id;
            confirmDeleteButton.href = `index.php?controleur=excursion&methode=supprimer&id=${excursionId}`;
        });
    });
    // Initialize Feather Icons
    feather.replace();
});