//Affichage des erreurs
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

document.addEventListener("DOMContentLoaded", function () {
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
});

document.addEventListener("DOMContentLoaded", function () {
  const itineraireSelect = document.getElementById("itineraire");
  const addVisitButton = document.getElementById("addVisit");
  const selectedVisits = document.getElementById("selectedVisits");
  const selectedVisitsTitle = document.getElementById("selectedVisitsTitle");

  // Cacher la liste des visites selectionées si elle est vide
  if (!selectedVisits || selectedVisits.children.length === 0) {
    selectedVisitsTitle.classList.add("d-none");
    selectedVisits.classList.add("d-none");
  }

  // Fonction pour ajouter les visites selectionées à la liste
  function addVisitToList(visitId, visitTitle, tempsSurPlace = "") {
    if (document.querySelector(`#visit-${visitId}`)) {
      showErrors(["Cette visite est déjà sélectionnée"]);
      return;
    }

    const listItem = document.createElement("li");
    listItem.id = `visit-${visitId}`;
    listItem.classList.add("mb-2");
    listItem.innerHTML = `
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <strong>${visitTitle}</strong>
              </div>
              <div class="d-flex gap-3">
                  <div>
                      <label for="temps_sur_place_${visitId}" class="form-label mb-0">Temps sur place :</label>
                      <input
                          type="time"
                          id="temps_sur_place_${visitId}"
                          name="temps_sur_place_${visitId}"
                          class="form-control form-control-sm"
                          value="${tempsSurPlace}"
                      />
                  </div>
                  <button type="button" class="btn btn-danger remove-visit">Supprimer</button>
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
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("excursionForm");
  const submitButton = document.getElementById("btn_maj");
  const excursionId = submitButton
    ? parseInt(submitButton.getAttribute("data-excursion-id"), 10)
    : null;
  console.log("Excursion ID:", excursionId);
  const errorContainer = document.getElementById("errorContainer");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    if (excursionId) {
      formData.append("id", excursionId);
    }

    const endpoint = excursionId
      ? "index.php?controleur=excursion&methode=modifier&id=" + excursionId
      : "index.php?controleur=excursion&methode=creer";

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
        return response.json();
      })
      .then((data) => {
        console.log("Server response:", data);

        if (data.success) {
          if (data.redirect) {
            window.location.href = data.redirect;
          }
        } else {
          showErrors(
            data.errors || [data.message || "Une erreur est survenue."]
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
});