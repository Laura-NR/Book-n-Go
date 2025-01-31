document.querySelector("#form").addEventListener("submit", (e) => {
  let errors = []; 

  // Validation du titre
  const titreInput = document.querySelector("#titre");
  const titreRegex = /^[a-zA-ZÀ-ÿ'\s-]+$/;
  if (!titreRegex.test(titreInput.value)) {
    errors.push("Le titre contient des caractères invalides.");
  }

  // Validation de l'adresse
  const adresseInput = document.querySelector("#adresse");
  const adresseRegex = /^[0-9]{1,3}\s[a-zA-ZÀ-ÿ'\s-]+$/;
  if (!adresseRegex.test(adresseInput.value)) {
    errors.push("L'adresse contient des caractères invalides.");
  }

  // Validation de la ville
  const villeInput = document.querySelector("#ville");
  const villeRegex = /^[a-zA-ZÀ-ÿ'\s-]+$/;
  if (!villeRegex.test(villeInput.value)) {
    errors.push("La ville contient des caractères invalides.");
  }

  // Validation du code postal
  const codePostalInput = document.querySelector("#codePostal");
  const codePostalRegex = /^[0-9]{5}$/;
  if (!codePostalRegex.test(codePostalInput.value)) {
    errors.push("Le code postal doit comporter exactement 5 chiffres.");
  }

  // Affichage des erreurs
  if (errors.length > 0) {
    e.preventDefault(); // Empêche la soumission
    alert(errors.join("\n")); // Affiche toutes les erreurs dans une alerte
  }
});