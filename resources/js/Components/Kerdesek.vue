<template>
    <div class="container mt-4">
        <h1>Kérdések</h1>
        <ul v-if="kerdesek.length" class="list-group">
            <li v-for="kerdes in kerdesek" :key="kerdes.id" class="list-group-item">
            {{ kerdes.kerdes }}
            </li>
        </ul>
        <p v-else>Betöltés...</p>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return { kerdesek: [] };
    },
    mounted() {
      axios.get("http://127.0.0.1:8000/api/kerdesek")
        .then(res => this.kerdesek = res.data)
        .catch(err => console.error(err));
    }
  };
  </script>
  