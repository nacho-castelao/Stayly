"use strict";

let mapDiv = document.querySelector("#map");
const lat = parseFloat(mapDiv.dataset.lat);
const lng = parseFloat(mapDiv.dataset.lng);

var map = L.map("map").setView([lat, lng], 13);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

var marker = L.marker([lat, lng]).addTo(map);
