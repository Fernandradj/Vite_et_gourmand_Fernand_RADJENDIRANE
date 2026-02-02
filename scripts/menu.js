
function themeChanged() {
    var theme = document.getElementById("theme-select").value;
    console.log(theme);
}
function personneSelect() {
    var personneMin = document.getElementById("personne-select").value;
    console.log(personneMin);
}
function regimeSelect() {
    var regime = document.getElementById("regime-select").value;
    console.log(regime);
}



function prixMin() {
    var prixMinimum = document.getElementById("prix-minimum").value;
    // console.log(prixMinimum);
    var menuBox = document.getElementById("menuBox");
    for (let child of menuBox.children) {
        const prix = child.querySelectorAll('p.prix_personne')[0].textContent;
        if (parseFloat(prix) >= parseFloat(prixMinimum)) {
            child.style.display = "block";
        }
        else {
            child.style.display = "none";
        }
    }
}

function prixMax() {
    var prixMaximum = document.getElementById("prix-maximum").value;
    // console.log(prixMaximum);
    var menuBox = document.getElementById("menuBox");
    for (let child of menuBox.children) {
        const prix = child.querySelectorAll('p.prix_personne')[0].textContent;
        if (parseFloat(prix) <= parseFloat(prixMaximum)) {
            child.style.display = "block";
        }
        else {
            child.style.display = "none";
        }
    }
}


function refresh() {

    // Prix min
    var prixMinimum = document.getElementById("prix-minimum").value;

    // Prix max
    var prixMaximum = document.getElementById("prix-maximum").value;

    // Personne min
    var personneMin = document.getElementById("personne-select").value;

    // Regime
    var regimeSelect = document.getElementById("regime-select").value;
    // Theme
     var themeSelect = document.getElementById("theme-select").value;


    var menuBox = document.getElementById("menuBox");
    for (let child of menuBox.children) {

        var display = true;

        // 1
        const prix = child.querySelectorAll('p.prix_personne')[0].textContent;
        if (parseFloat(prix) < parseFloat(prixMinimum)) {
            display = false;
        }
        if (parseFloat(prix) > parseFloat(prixMaximum)) {
            display = false;
        }

        // 2
        const persMin = child.querySelectorAll('p.personne_min')[0].textContent;
        if (parseFloat(persMin) < parseFloat(personneMin)) {
            display = false;
        }

        // 3
        const regime = child.querySelectorAll('p.regime')[0].textContent;
        if ((regimeSelect != "") && (regime != regimeSelect)) {
            display = false;
        }
        // 4
        const  theme = child.querySelectorAll('p.theme')[0].textContent;
        if ((themeSelect != "") && (theme != themeSelect)) {
            display = false;
        }


        if (display) {
            child.style.display = "block";
        }
        else {
            child.style.display = "none";
        }



    }
}









