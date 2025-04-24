<template>
    <div id="app">
      <div id="map"></div>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from "vuex";
  import L from 'leaflet';
  import axios from 'axios';
  
  export default {
    data() {
      return {
        map: null,
        localisations: [], // Liste des localisations
        apiBaseURL: this.apiBaseURL, // Remplacez par votre URL API
      };
    },
    mounted() {
      this.initMap();
      this.fetchMarkers(); // Récupérer les données des marqueurs
    },
    methods: {
      initMap() {
        // Initialiser la carte
        this.map = L.map('map').setView([48.8566, 2.3522], 13); // Coordonnées de Paris
  
        // Ajouter une couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap'
        }).addTo(this.map);
      },
      createCustomIcon(refProduit) {
        return L.icon({
          iconUrl: 'path/to/icon.png', // Remplacez par le chemin de votre icône
          iconSize: [25, 41], // Taille de l'icône
          iconAnchor: [12, 41], // Point d'ancrage de l'icône
          popupAnchor: [1, -34], // Point d'ancrage de la popup
        });
      },
      async fetchMarkers() {
        try {
          const response = await axios.get(`${this.apiBaseURL}/tvente_localisation_produit`);
          
          // Supposons que les données soient dans response.data.data
          this.localisations = response.data.data; 
  
          this.addMarkers(); // Ajouter les marqueurs après avoir récupéré les données
        } catch (error) {
          console.error('Erreur lors de la récupération des données:', error);
        }
      },
      addMarkers() {
        // Ajouter des marqueurs sur la carte
        this.localisations.forEach(localisation => {
          const marker = L.marker([localisation.latitude, localisation.longitude], {
            icon: this.createCustomIcon(localisation.refProduit) // Utiliser une icône personnalisée
          }).addTo(this.map)
            .bindPopup(`Produit ID: ${localisation.refProduit}`); // Afficher le produit dans une popup
        });
      }
    }
  };
  </script>
  
  <style>
  html, body {
    height: 100%;
    margin: 0;
  }
  
  #app {
    height: 100%;
  }
  
  #map {
    height: 100%; /* Assurez-vous que la carte prend toute la hauteur */
  }
  </style>
  