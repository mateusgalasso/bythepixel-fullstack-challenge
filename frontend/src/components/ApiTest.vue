<script setup>
import { ref, onMounted, reactive } from "vue";

const apiResponse = ref(null);
const users = ref([]);
const userDetails = reactive({ show: false, data: null });

onMounted(async () => {
  await fetchData();
});

async function fetchData() {
  const url = "http://localhost/users";
  apiResponse.value = await (await fetch(url)).json();
  users.value = apiResponse.value.users;
}

const fetchDetails = async (user) => {
  //convert latitude and longitude to user's location decimal with 2 decimal places
  let lat = parseFloat(user.latitude).toFixed(2);
  let lon = parseFloat(user.longitude).toFixed(2);
  let url;
  //random true of false just to test both endpoints
  if (Math.random() > 0.5) {
    url = `http://localhost/weather/${user.id}`;
  } else {
    url = `http://localhost/weather?lat=${lat}&lon=${lon}`;
  }
  const response = await (await fetch(url)).json();
  // if code 401, 403, 404, 500, etc. handle it here
  if (!response.cod || response.cod !== 200) {
    console.log("Error fetching weather data", response);
    return;
  }
  userDetails.data = {
    ...user,
    weather: response.weather[0], // first weather data
    main: response.main, // additional main weather data
  };
  userDetails.show = true;
};
const closeDetails = () => {
  userDetails.show = false;
};
</script>

<template>
  <div v-if="!users.length" class="text-center my-5 text-lg font-medium">Fetching users and their current weather...</div>

  <div v-else class="flex flex-col w-1/4 md:w-2/5 lg:w-1/5 ml-5 lg:ml-10">
    <div v-for="user in users" :key="user.id" class="p-5 flex flex-row items-center justify-between bg-white shadow rounded border border-gray-200 mb-4">
      <h2 class="text-lg font-medium mb-3">{{ user.name }}</h2>
      <button @click="fetchDetails(user)" class="px-3 py-2 bg-indigo-500 text-white rounded">Show Details</button>
    </div>
  </div>

  <div v-if="userDetails.show" class="fixed inset-0 flex items-center justify-center z-10 p-5">
    <div class="bg-white rounded shadow-lg w-full max-w-lg mx-auto flex flex-col items-start p-5">
      <button @click="closeDetails" class="self-end font-bold mb-3">X</button>
      <h2 class="text-2xl font-bold mb-3">{{ userDetails.data.name }} Detailed Weather:</h2>
      <div class="text-lg">Main Condition: {{ userDetails.data.weather.main }}</div>
      <div class="text-lg">Description: {{ userDetails.data.weather.description }}</div>
      <div class="text-lg">Temperature: {{ userDetails.data.main.temp }} F</div>
      <div class="text-lg">Humidity: {{ userDetails.data.main.humidity }}%</div>
      <div class="text-lg">Minimum Temperature: {{ userDetails.data.main.temp_min }} F</div>
      <div class="text-lg">Maximum Temperature: {{ userDetails.data.main.temp_max }} F</div>
    </div>
  </div>
</template>
