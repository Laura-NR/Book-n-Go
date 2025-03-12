function initMap() {
    const map = L.map('map').setView([47, 10], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const waypoints = [];
    const markers = [];
    let geocodingPromises = [];

    // Si les visites existent
    if (typeof visites !== 'undefined' && visites.length > 0) {

        // Pour chaque visite Essayer de récup les coordonnées
        visites.forEach((visite, index) => {
            if (visite.adresse) {
                // ajouter la villa à l'adresse
                let fullAddress = visite.adresse;
                if (visite.codePostal) {
                    fullAddress += `, ${visite.codePostal}`;
                }

                // Delai pour éviter le rate limiting
                const delay = index * 1500;

                const geocodingPromise = new Promise(resolve => {
                    setTimeout(() => {
                        geocodeAddress(fullAddress)
                            .then(coordinates => {
                                if (coordinates) {

                                    // stocker les coordonnées avec l'index
                                    waypoints.push({
                                        index: index,
                                        coords: coordinates
                                    });

                                    // creer un marqueur
                                    const marker = L.marker(coordinates).addTo(map);

                                    let popupContent = `<b>${visite.titre || 'Visite ' + (index + 1)}</b><br>${visite.adresse}`;
                                    if (visite.ville) {
                                        popupContent += `<br>${visite.ville}`;
                                    }
                                    marker.bindPopup(popupContent);
                                    markers.push(marker);
                                    resolve({
                                        index: index,
                                        coords: coordinates
                                    });
                                } else {
                                    resolve(null);
                                }
                            })
                            .catch(error => {
                                resolve(null);
                            });
                    }, delay);
                });

                geocodingPromises.push(geocodingPromise);
            } else {
                console.warn("Address missing for visite:", visite);
                geocodingPromises.push(Promise.resolve(null));
            }
        });

        Promise.all(geocodingPromises)
            .then(results => {
                // filtrer les résultats nuls et trier
                const validResults = results.filter(result => result !== null);

                if (validResults.length > 0) {

                    validResults.sort((a, b) => a.index - b.index);

                    // recuperer les coordonnées
                    const polylineCoords = validResults.map(result => result.coords);

                    // creer la polyline
                    L.polyline(polylineCoords, {color: 'blue'}).addTo(map);

                    // ajuster la vue
                    if (polylineCoords.length > 1) {
                        const bounds = L.latLngBounds(polylineCoords);
                        map.fitBounds(bounds, {padding: [50, 50]});
                    } else if (polylineCoords.length === 1) {
                        map.setView(polylineCoords[0], 13);
                    }
                } else {
                    map.setView([47, 10], 5);
                }
            })
            .catch(error => {
                console.error("Error processing geocoding results:", error);
                map.setView([47, 10], 5);
            });
    } else {
        console.log("No visites data available.");
        map.setView([47, 10], 5);
    }
}


function geocodeAddress(address) {

    const encodedAddress = encodeURIComponent(address);
    const nominatimUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&limit=1`;

    return fetch(nominatimUrl, {
        headers: {
            'User-Agent': 'BookNGoApp/1.0'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Geocoding failed: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                return [lat, lon];
            } else {
                console.warn(`No geocoding results found for address: ${address}`);
                return null;
            }
        })
        .catch(error => {
            console.error(`Error during geocoding for address: ${address}`, error);
            return null;
        });
}

function initFlatpickr() {
    // Vérifier que datesExistantes est reconnu et pas vide
    if (typeof datesExistantes === 'undefined' || !Array.isArray(datesExistantes) || datesExistantes.length === 0) {
        console.error("datesExistantes n'est pas définie ou est vide");
        return;
    }

    // Initialiser flatpickr avec "enable" pour n'afficher que les dates disponibles à la réservation
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        enable: datesExistantes,
        minDate: datesExistantes[0],
        maxDate: datesExistantes[datesExistantes.length - 1]
    });

    console.log("Flatpickr initialized with allowed dates:", datesExistantes);
}


// initialisation
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    initFlatpickr();
});


const datePicker = document.getElementById('datepicker');
const dateSelect = document.getElementById('date-select');
const idEngagementInput = document.getElementById('id_engagement_input');

datePicker.addEventListener('change', () => {
    const filterDate = datePicker.value;
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

function initMap() {
    const map = L.map('map').setView([47, 10], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const waypoints = [];
    const markers = [];
    let geocodingPromises = [];

    // Si les visites existent
    if (typeof visites !== 'undefined' && visites.length > 0) {
        console.log("Total visites to process:", visites.length);

        // Pour chaque visite Essayer de récup les coordonnées
        visites.forEach((visite, index) => {
            console.log(visite, index);
            if (visite.adresse) {
                // ajouter la villa à l'adresse
                let fullAddress = visite.adresse;
                if (visite.codePostal) {
                    fullAddress += `, ${visite.codePostal}`;
                }
                console.log(`Geocoding address ${index + 1}:`, fullAddress);

                // Delai pour éviter le rate limiting
                const delay = index * 1500;

                const geocodingPromise = new Promise(resolve => {
                    setTimeout(() => {
                        geocodeAddress(fullAddress)
                            .then(coordinates => {
                                if (coordinates) {
                                    console.log(`Received coordinates for ${fullAddress}:`, coordinates);

                                    // stocker les coordonnées avec l'index
                                    waypoints.push({
                                        index: index,
                                        coords: coordinates
                                    });

                                    // Create a marker for this location
                                    const marker = L.marker(coordinates).addTo(map);

                                    // Add popup with visite information
                                    let popupContent = `<b>${visite.titre || 'Visite ' + (index + 1)}</b><br>${visite.adresse}`;
                                    if (visite.ville) {
                                        popupContent += `<br>${visite.ville}`;
                                    }
                                    marker.bindPopup(popupContent);
                                    markers.push(marker);
                                    resolve({
                                        index: index,
                                        coords: coordinates
                                    });
                                } else {
                                    console.warn(`No coordinates found for ${fullAddress}`);
                                    resolve(null);
                                }
                            })
                            .catch(error => {
                                console.error(`Error geocoding address: ${fullAddress}`, error);
                                resolve(null);
                            });
                    }, delay);
                });

                geocodingPromises.push(geocodingPromise);
            } else {
                console.warn("Address missing for visite:", visite);
                geocodingPromises.push(Promise.resolve(null));
            }
        });

        Promise.all(geocodingPromises)
            .then(results => {
                // Filter out any null results
                const validResults = results.filter(result => result !== null);
                console.log("Valid geocoding results:", validResults);

                if (validResults.length > 0) {
                    // Sort waypoints by their original index to maintain the correct order
                    validResults.sort((a, b) => a.index - b.index);

                    // Extract just the coordinates for the polyline
                    const polylineCoords = validResults.map(result => result.coords);

                    // Create a polyline connecting all waypoints
                    L.polyline(polylineCoords, {color: 'blue'}).addTo(map);

                    // Adjust map view to fit all markers
                    if (polylineCoords.length > 1) {
                        const bounds = L.latLngBounds(polylineCoords);
                        map.fitBounds(bounds, {padding: [50, 50]});
                    } else if (polylineCoords.length === 1) {
                        map.setView(polylineCoords[0], 13); // Zoom level 13 for a single point
                    }

                    // Log the number of markers created
                    console.log(`Created ${markers.length} markers on the map`);
                } else {
                    console.log("No valid waypoints available after geocoding.");
                    // Default view if no valid waypoints
                    map.setView([47, 10], 5);
                }
            })
            .catch(error => {
                console.error("Error processing geocoding results:", error);
                // Default view if error occurs
                map.setView([47, 10], 5);
            });
    } else {
        console.log("No visites data available.");
        // Default view if no visites data
        map.setView([47, 10], 5);
    }
}

/**
 * Geocode an address to get coordinates using OpenStreetMap Nominatim API
 * @param {string} address - The address to geocode
 * @returns {Promise} - A promise that resolves to [latitude, longitude] or null if geocoding fails
 */
function geocodeAddress(address) {
    // Encode the address for URL
    const encodedAddress = encodeURIComponent(address);

    // Use OpenStreetMap Nominatim API for geocoding
    const nominatimUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&limit=1`;

    return fetch(nominatimUrl, {
        headers: {
            // Add a user agent as required by Nominatim usage policy
            'User-Agent': 'BookNGoApp/1.0'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Geocoding failed: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                return [lat, lon];
            } else {
                console.warn(`No geocoding results found for address: ${address}`);
                return null;
            }
        })
        .catch(error => {
            console.error(`Error during geocoding for address: ${address}`, error);
            return null;
        });
}

// Initialize the map when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});

// document.addEventListener('DOMContentLoaded', function() {
//     initMap();
// });