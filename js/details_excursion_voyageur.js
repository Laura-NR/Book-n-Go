const filterDateInput = document.getElementById('filter-date');
const dateSelect = document.getElementById('date-select');
const idEngagementInput = document.getElementById('id_engagement_input');

filterDateInput.addEventListener('change', () => {
    const filterDate = filterDateInput.value;
    dateSelect.querySelectorAll('option').forEach(option => {
        if (option.value !== "") {
            if (!filterDate || option.value === filterDate) { // on vérifie si filterDate est vide
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    });
    dateSelect.selectedIndex = 0;
    idEngagementInput.value = '';
});

dateSelect.addEventListener('change', () => {
    const selectedOption = dateSelect.selectedOptions[0];
    idEngagementInput.value = selectedOption.dataset.engagementId;
});