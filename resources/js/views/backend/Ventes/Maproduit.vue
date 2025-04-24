<template>
  <l-map :zoom="13" :center="[47.41322, -1.219482]" style="height: 500px; width: 100%;">
    <l-tile-layer
      url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      attribution="&copy; OpenStreetMap contributors"
    />
    <l-marker :lat-lng="[47.41322, -1.219482]"></l-marker>
  </l-map>
</template>


<script>
import { LMap, LTileLayer, LMarker } from "vue2-leaflet";
import "leaflet/dist/leaflet.css";


export default {
     components: {
       LMap,
       LTileLayer,
       LMarker,
     },
   };
</script>
