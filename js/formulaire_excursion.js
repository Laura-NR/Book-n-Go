import Sortable from '/node_modules/sortablejs/modular/sortable.complete.esm.js';

// Affichage des erreurs
function showErrors(errors) {
  const errorContainer = document.getElementById('errorContainer');
  errorContainer.innerHTML = "";
  if (errors.length > 0) {
    errors.forEach((error) => {
      const errorElement = document.createElement("div");
      errorElement.classList.add("alert", "alert-danger", "alert-dismissible", "fade", "show");
      errorElement.textContent = error;
      const closeButton = document.createElement("button");
      closeButton.type = "button";
      closeButton.classList.add("btn-close");
      closeButton.setAttribute("data-bs-dismiss", "alert");
      closeButton.setAttribute("aria-label", "Close");
      errorElement.appendChild(closeButton);
      errorContainer.appendChild(errorElement);

      // Les messages d'erreur vont disparaitre après 8 secondes si l'utilisateur ne les ferme pas
      const bsAlert = new bootstrap.Alert(errorElement);
          setTimeout(() => {
              bsAlert.close();
          }, 8000);
    });
    errorContainer.style.display = "block";
  } else {
    errorContainer.style.display = "none";
  }
} 

// Initialisation de la liste des visites
function initSortable()
{
  if (selectedVisits) {
    new Sortable(selectedVisits, {
      handle: '.handle', // Drag uniquement via le handle ☰
      animation: 150,
      ghostClass: "sortable-ghost"   
    });
  }
}

// Ajout d'une visite à la liste des visites
function addVisitToList(visitId, visitTitle, tempsSurPlace = "") {
  if (document.querySelector(`#visit-${visitId}`)) {
    showErrors(["Cette visite est déjà sélectionnée"]);
    return;
  }

  //d-flex justify-content-between align-items
  const listItem = document.createElement("li");
  listItem.id = `visit-${visitId}`;
  listItem.classList.add("mb-2");

  listItem.innerHTML = `
      <div class="d-flex align-items-center justify-content-between gap-2">
      <div class='d-flex justify-content-start gap-3'>
        <div class="handle" style="cursor: grab;"><h5>☰</h5></div>
        <div>
        <p>${visitTitle}</p>
        </div>
      </div>
      <div class="d-flex justify-content-end gap-3">
        <div class="mb-4">
          <label for="temps_sur_place_${visitId}" class="form-label mb-0">Temps sur place :</label>
          <input
          type="time"
          id="temps_sur_place_${visitId}"
          name="temps_sur_place_${visitId}"
          class="form-control form-control-sm"
          value="${tempsSurPlace}"
          />
        </div>
        <button type="button" class="remove-visit-style remove-visit">
          <i id="deleteIcon" data-feather="x-circle"></i>
        </button>
      </div>
      </div>
      `;

  selectedVisits.appendChild(listItem);

  selectedVisits.style.display = "block";
  selectedVisitsTitle.style.display = "block";

  listItem.querySelector(".remove-visit").addEventListener("click", () => {
    listItem.remove();
    if (selectedVisits.children.length === 0) {
      selectedVisits.style.display = "none";
      selectedVisitsTitle.style.display = "none";
    }
  });
}

// Enregistrement du gestionnaire d'événement pour le bouton d'affichage de la liste des visites
function registerSelectEvent()
{
  const itineraireSelect = document.getElementById("itineraire");

  itineraireSelect.addEventListener("focus", () => {
    if (itineraireSelect.querySelector("[data-fetched]")) {
      return;
    }

    fetch("index.php?controleur=excursion&methode=afficherCreer", {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        console.log("Response status:", response.status);
        console.log(
          "Response content-type:",
          response.headers.get("Content-Type")
        );

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        // return response.json();
        console.log("Response: ", response);
        return response.text();
      })
      .then((responseText) => {
        responseText = responseText.trim();
        console.log("Response Text:", responseText);

        try {
          const data = JSON.parse(responseText);
          console.log("Fetched visits:", data);

          const addNewOption = itineraireSelect.querySelector(
            'option[value="add-new"]'
          );

          data.forEach((visit) => {
            const option = document.createElement("option");
            option.value = visit.id;
            option.textContent = `${visit.titre} - ${visit.ville}`;
            option.setAttribute("data-fetched", true);
            addNewOption.insertAdjacentElement("afterend", option);
          });
        } catch (error) {
          console.error("Failed to parse JSON:", error);
        }
      })
      .catch((error) => {
        console.error("Erreur à l'heure de récupérer les visites:", error);
        showErrors(["Pas possible de récupérer les visites, ressayez plus tard"]);
      });
  });
}

// Enregistrement du gestionnaire d'événement pour le bouton d'ajout de visite
function registerAddVisitEvent()
{
  const itineraireSelect = document.getElementById("itineraire");
  const addVisitButton = document.getElementById("addVisit");
  const selectedVisits = document.getElementById("selectedVisits");
  const selectedVisitsTitle = document.getElementById("selectedVisitsTitle");

  addVisitButton.addEventListener("click", () => {
    const selectedVisit =
      itineraireSelect.options[itineraireSelect.selectedIndex];
    if (selectedVisit.value === "add-new") {
      window.location.href =
        "index.php?controleur=visite&methode=redirectCreer&isExcursion=1";
      return;
    }
    if (!selectedVisit || selectedVisit.value === "") {
      showErrors(["Veuillez sélectionner une visite valide."]);
      return;
    }

    const visitId = selectedVisit.value;
    const visitTitle = selectedVisit.textContent;

    addVisitToList(visitId, visitTitle);
    feather.replace();
  });

  document.querySelectorAll(".remove-visit").forEach((button) => {
    button.addEventListener("click", function () {
      const listItem = this.closest("li");
      listItem.remove();
      if (selectedVisits.children.length === 0) {
        selectedVisits.style.display = "none";
        selectedVisitsTitle.style.display = "none";
      }
    });
  });
}

// Enregistrement du gestionnaire d'événement pour le bouton d'envoi / de submit
function handleSubmitBtn() 
{
  var form = document.getElementById("excursionForm");
  var submitButton = document.getElementById("btn_maj");
  var excursionId = submitButton
    ? parseInt(submitButton.getAttribute("data-excursion-id"), 10)
    : null;
  console.log("Excursion ID:", excursionId);

  function makeFormData()
  {
    var formData = new FormData();
    formData.append("nom", document.getElementById("nom").value);
    formData.append("capacite", document.getElementById("capacite").value);
    formData.append("description", document.getElementById("description").value);
    
    // Ajouter l'image si elle est sélectionnée
    var fichierImage = document.getElementById("chemin_image").files[0];
    if (fichierImage) {
      formData.append("chemin_image", fichierImage);
    }

    var visitesElems = document.querySelectorAll("#selectedVisits li");
    
    // Regrouper les visites dans un tableau
    var visites = [];
    
    for (let i=0; i<visitesElems.length; i++) {
      let visitId = visitesElems[i].id.replace("visit-", "");
      let tempsSurPlace = visitesElems[i].querySelector(`#temps_sur_place_${visitId}`).value;

      visites.push({
        id: visitId,
        tempsSurPlace: tempsSurPlace,
        ordre: i
      });
    };

    // Ajouter les visites sous forme de JSON
    formData.append("visites", JSON.stringify(visites));

    console.log("Form data:", formData);
    // Ajouter l'ID de l'excursion si c'est une modification
    if (excursionId) {
      formData.append("excursionId", excursionId);
    }

    return formData;
  }

  function registerSubmitEvent()
  {
    // Gestionnaire d'événement du bouton submit
    form.addEventListener("submit", function (e) {
    e.preventDefault(); // Gérer la soumission manuellement
    
    var endpoint = excursionId
      ? "index.php?controleur=excursion&methode=modifier&id=" + excursionId
      : "index.php?controleur=excursion&methode=creer";

    var formData = makeFormData();
    
    fetch(endpoint, {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.text();
    })
    .then((text) => {
      /* Json_encode de PHP ajoute des retours à la ligne inutiles. 
         Tout caractère hors specs du format JSON fera échouer le JSON.parse()
         On trim à la main.*/
      const responseText = text.trim();
      console.log(responseText);
      return JSON.parse(responseText);
    })
      .then((data) => {
        console.log("Server response:", data);

        if (data.success) {
          if (data.redirect) {
           window.location.href = data.redirect;
          }
        } else {
          console.log("Errors:", data.errors);
          console.log(data);
          showErrors(
            data.errors
          );
        }
      })
      .catch((error) => {
        console.error("Erreur lors de la soumission du formulaire:", error);
        showErrors([
          "Une erreur est survenue lors de la soumission du formulaire. Veuillez réessayer.",
        ]);
      });
  });
  }

  registerSubmitEvent();
}

// Initialisation et enregistrement des gestionnaires d'événements
document.addEventListener("DOMContentLoaded", function () {
  if (!selectedVisits || selectedVisits.children.length === 0) {
    selectedVisits.style.display = "none";
    selectedVisitsTitle.style.display = "none";
  }
  
  initSortable();

  registerSelectEvent();
  registerAddVisitEvent();
  handleSubmitBtn();
});