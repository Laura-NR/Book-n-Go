document.addEventListener('DOMContentLoaded', function() {
    console.log('Hello Everyone');
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
    console.log('Hello_1');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');

    
    deleteLinks.forEach(deleteLink => {
        deleteLink.addEventListener('click', function(event) {
            console.log('click');
            event.preventDefault();
            const excursionId = this.getAttribute('data-id');
            console.log(excursionId);
            confirmDeleteButton.href = `index.php?controleur=excursion&methode=supprimer&id=${excursionId}`;
        });
    });

    // Initialize Feather Icons
    feather.replace();
});