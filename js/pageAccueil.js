document.addEventListener('DOMContentLoaded', function() {
        // Les notifications vont disparaitre après 5 secondes si l'utilisateur ne les ferme pas
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            setTimeout(() => {
                bsAlert.close();
            }, 5000);
        });
        
        // Scroll Down
        const scrollDownButton = document.getElementById('scrollDownButton');
        const targetSection = document.querySelector('.container.text-center');

        scrollDownButton.addEventListener('click', function() {
            targetSection.scrollIntoView({ behavior: 'smooth' });
        });
    });

