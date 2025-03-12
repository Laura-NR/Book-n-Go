document.getElementById('resetButton').addEventListener('click', function() {
    // Vider le champ de recherche
    document.getElementById('searchbar').value = '';

    // Recharger la page
    window.location.href = 'index.php?controleur=excursion&methode=lister';
});