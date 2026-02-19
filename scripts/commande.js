const PRICE_PER_KM = 0.59;

// Format result in the search input autocomplete
var formatResult = function (feature, el) {
    var title = document.createElement("strong");
    el.appendChild(title);
    var detailsContainer = document.createElement("small");
    el.appendChild(detailsContainer);
    var details = [];
    title.innerHTML = feature.properties.label || feature.properties.name;
    var types = {
        housenumber: "numéro",
        street: "rue",
        locality: "lieu-dit",
        municipality: "commune",
    };
    if (types[feature.properties.type]) {
        var spanType = document.createElement("span");
        spanType.className = "type";
        title.appendChild(spanType);
        spanType.innerHTML = types[feature.properties.type];
    }
    if (
        feature.properties.city &&
        feature.properties.city !== feature.properties.name
    ) {
        details.push(feature.properties.city);
    }
    if (feature.properties.context) {
        details.push(feature.properties.context);
    }
    detailsContainer.innerHTML = details.join(", ");
};

// Function to show you can do something with the returned elements
function myHandler(featureCollection) {
    // console.log(featureCollection);
}

async function getDistanceEntreAdresses(adr1, adr2, apiKey) {
    try {
        // 1. Fonction interne pour géocoder une adresse
        const geocode = async (adresse) => {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresse)}&limit=1`;
            const response = await fetch(url, { headers: { 'User-Agent': 'MonAppDistance/1.0' } });
            const data = await response.json();
            if (data.length === 0) throw new Error(`Adresse non trouvée : ${adresse}`);
            return [data[0].lon, data[0].lat]; // ORS utilise le format [Lon, Lat]
        };

        console.log("Géocodage en cours...");
        const coord1 = await geocode(adr1);
        const coord2 = await geocode(adr2);

        // 2. Calcul de la distance avec OpenRouteService
        const osrUrl = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${apiKey}&start=${coord1.join(',')}&end=${coord2.join(',')}`;

        const osrResponse = await fetch(osrUrl);
        const osrData = await osrResponse.json();

        if (!osrData.features) throw new Error("Erreur lors du calcul d'itinéraire.");

        const distanceMetres = osrData.features[0].properties.summary.distance;
        const dureeSecondes = osrData.features[0].properties.summary.duration;

        return {
            distanceKm: (distanceMetres / 1000).toFixed(2),
            dureeMinutes: Math.round(dureeSecondes / 60)
        };

    } catch (error) {
        console.error("Erreur :", error.message);
    }
}



// We reused the default function to center and zoom on selected feature.
// You can make your own. For instance, you could center, zoom
// and add a point on the map
function onSelected(feature) {
    // feature : selected address
    console.log(feature);

    // display selected address
    p = document.getElementById('adresse');
    p.value = feature['properties']['label'];

    updateLivraison(feature);
}

function updateLivraison(feature) {

    console.log('update livraison : ' + feature);

    if (feature == null) {
        document.getElementById("totale_livraison").value = 0;
        document.getElementById("distance_livraison").value = 0;
        update_totale_price();
        return;
    }

    // display delivery charge for city
    ville = feature['properties']['city'];
    frais = 0;
    if (ville != "Bordeaux") {
        frais = 5;
    }
    document.getElementById("totale_livraison").value = frais;

    // display delivery charge for km
    let kmprice = 0;
    if (ville != "Bordeaux") {
        const apiKey = 'eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImUyODU4ZDI2NjQxYzQ5NzM5ZTdkNzgwMDZlZjE5YjFmIiwiaCI6Im11cm11cjY0In0=';

        let addresseResto = "12 Quai Virginie Hériot, 33300 Bordeaux";
        let adresseClient = feature['properties']['label'];

        // --- EXEMPLE D'UTILISATION ---
        getDistanceEntreAdresses(addresseResto, adresseClient, apiKey)
            .then(result => {
                if (result) {
                    // console.log(`Résultat : ${result.distanceKm} km (environ ${result.dureeMinutes} min)`);

                    dist = result.distanceKm;
                    if ((dist == null) || (dist == undefined) || (dist == NaN)) {
                        dist = 0;
                    }
                    dist = Math.ceil(dist);
                    console.log(dist);

                    kmprice = Math.round(dist * PRICE_PER_KM * 100) / 100;
                    console.log(kmprice);
                    document.getElementById("distance_livraison").value = kmprice;

                    update_totale_price();
                }
            });
    }
    console.log('distance displayed : ' + kmprice);
    document.getElementById("distance_livraison").value = kmprice;

    update_totale_price();

}


addSearchBar = false;
showSearchBar = document.getElementsByClassName("showSearchBar");
// console.log('showSearchBar : ' + showSearchBar);
if (showSearchBar.length > 0) {
    if (showSearchBar[0].innerHTML == true) {
    addSearchBar = true;
    }
}
console.log('add search bar : ' + addSearchBar);

if (addSearchBar) {
    // URL for API
    var API_URL = "//api-adresse.data.gouv.fr";

    // Create search by adresses component
    var container = new Photon.Search({
        resultsHandler: myHandler,
        onSelected: onSelected,
        placeholder: "Tapez une adresse",
        formatResult: formatResult,
        url: API_URL + "/search/?",
        feedbackEmail: null,
    });
    // create div tag
    const element = document.createElement("div");

    // give class to div element
    element.className = "photon-geocoder-autocomplete ol-unselectable ol-control";



    // add search input inside div element 
    element.appendChild(container);

    // add div element into body tag 
    search_bar = document.getElementById('search_bar');
    search_bar.appendChild(element);
}


// to calculate price
function update_price() {
    // console.log('ddsddsdd');

    // commande
    let nbPer = document.getElementById("personne-select").value;
    let prxper = document.getElementById("prix_personne").innerHTML;
    let totale = nbPer * prxper;
    console.log(totale);
    document.getElementById("totale_commande").value = totale;

    // reduction
    let reduction = 0;
    let personne_min = document.getElementById("personne_min").innerHTML;

    if ((parseInt(personne_min) + 5) <= nbPer) {
        reduction = 10;
    }
    document.getElementById("reduction").value = reduction;

    update_totale_price();

}


function update_totale_price() {
    let prix_totale = 0;
    let dist_liv = parseFloat(document.getElementById("distance_livraison").value);
    let totale_liv = parseInt(document.getElementById("totale_livraison").value);
    let totale_commande = parseFloat(document.getElementById("totale_commande").value);
    let reduction = parseInt(document.getElementById("reduction").value);

    prix_totale = totale_commande + totale_liv + dist_liv;
    let rest = (100 - reduction) / 100;
    prix_totale = Math.round(prix_totale * rest * 100) / 100;
    document.getElementById("prix_totale").value = prix_totale;
}


// main
update_price();
// updateLivraison(null);


