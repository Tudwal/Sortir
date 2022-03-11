/*!
    * Start Bootstrap - SB Admin v7.0.4 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    //Script pour l'API City Location
	let cities = {};
	let url = '../api/';
	fetch(url).then(res = res.json()).then(data => {
	cities = data
	console.log(data);
	afficherLocation(cities[0].id);
	});

	function afficherCity() { // alimenter select city
	let baliseCity = document.querySelector('#city');
	for (let c of cities) {
	let option = document.createElement('option'); // <option></option>
	option.innerHTML = c.name; // <option>Rennes</option>
	option.setAttribute('value', c.id); // <option value="1">Rennes</option>
	baliseCity.appendChild(option);
	}
	}
	// alimenter select Location
	function afficherLocation(cityId) {
	let baliseLocation = document.querySelector('#location');
	// vider la balise select du Location
	baliseLocation.innerHTML = '';
	// rechercher ts les Locations correspondant à la city
	for (let c of cities) {
	if (c.id == cityId) {
	for (let l of c.locations) {
	let option = document.createElement('option'); // <option></option>
	option.innerHTML = l.name; // <option>rouge</option>
	option.setAttribute('value', l.id); // <option value="1">rouge</option>
	baliseLocation.appendChild(option);
	}
	}
	}
	}
	// condition de depart
	// afficherLocation(data.cities[0].id);

	document.querySelector('#city').onchange = function () {
	let id = document.querySelector('#city').value;
	console.log(id);
	afficherLocation(id);
	}



});
