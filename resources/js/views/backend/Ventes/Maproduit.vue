<template>
  <l-map :zoom="zoom" :center="center" style="height: 500px; width: 100%;">
    <l-tile-layer
      url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      attribution="&copy; OpenStreetMap contributors"
    />
    <l-marker
      v-for="(marker, index) in markers"
      :key="index"
      :lat-lng="[marker.latitude, marker.longitude]"
    >
      <l-popup>
        Produit: {{ marker.designation }}
      </l-popup>
    </l-marker>
  </l-map>
</template>



<script>
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
import { LMap, LTileLayer, LMarker, LPopup } from "vue2-leaflet";
import "leaflet/dist/leaflet.css";

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});


export default {
  components: {
    LMap,
    LTileLayer,
    LMarker,LPopup,
  },
  data() {
    return {
      zoom: 6,
      center: [0, 0],
      markers: [],
    };
  },
  mounted() {
    this.editOrFetch(`${this.apiBaseURL}/localisation_produit`)
      .then(response => {
        this.markers = response.data;
        if (this.markers.length) {
          this.center = [this.markers[0].latitude, this.markers[0].longitude];
        }
      })
      .catch(error => {
        console.error("Erreur de chargement des coordonnées :", error);
      });
  },
};
</script>