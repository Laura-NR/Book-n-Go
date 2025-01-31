    // Fonction pour afficher/masquer le champ de certification
    function toggleCertificationField() {
        const profil = document.getElementById('profil').value;
        const certificationField = document.getElementById('certification-field');
        if (profil === 'guide') {
            certificationField.classList.remove('d-none');
        } else {
            certificationField.classList.add('d-none');
        }
    }
    //faire en sorte que le champ de certification soit visible quand le profil est guide récupéré des données
    document.addEventListener('DOMContentLoaded', function() {
        toggleCertificationField();
    });
