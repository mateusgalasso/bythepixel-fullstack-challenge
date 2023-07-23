<script setup>
import {ref, onMounted, reactive} from "vue";
import {FilterMatchMode} from "primevue/api";

const apiResponse = ref(null);
const users = ref([]);
const loading = ref(false);
const userDetails = reactive({show: false, data: null});

onMounted(async () => {
  await fetchData();
});

async function fetchData() {
  loading.value = true;
  const url = "http://localhost/users";
  apiResponse.value = await (await fetch(url)).json();
  users.value = apiResponse.value.users;
  loading.value = false;
}

const fetchDetails = async (user) => {
  loading.value = true;
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
  loading.value = false;
};
const closeDetails = () => {
  userDetails.show = false;
};
const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  name: {value: null, matchMode: FilterMatchMode.STARTS_WITH},
});
</script>

<template>
  <div class="container mx-auto">
    <DataTable
      v-model:filters="filters"
      :value="users"
      dataKey="id"
      paginator
      :rows="10"
      filterDisplay="row"
      :loading="loading"
    >
      <template #empty> No customers found.</template>
      <template #loading> Loading customers data. Please wait.</template>
      <Column field="name" header="Name">
        <template #filter="{ filterModel, filterCallback }">
          <InputText
            v-model="filterModel.value"
            type="text"
            @input="filterCallback()"
            class="p-column-filter"
            placeholder="Search by name"
          />
        </template>
      </Column>
      <Column header="Actions">
        <template #body="slotProps">
          <Button
            class="bg-blue-500"
            type="button"
            label="Show Details"
            @click="fetchDetails(slotProps.data)"
          />
        </template>
      </Column>
    </DataTable>
  </div>
  <div v-if="loading" class="fixed inset-0 flex items-center justify-center z-10 p-5"
  >
    <div class="bg-white rounded shadow-lg w-full max-w-lg mx-auto flex flex-col items-start p-5"
    >
      <Skeleton class="w-9 sm:w-16rem xl:w-10rem shadow-2 h-6rem block xl:block mx-auto border-round"
      />
    </div>
  </div>

  <div v-if="userDetails.show" class="fixed inset-0 flex items-center justify-center z-10 p-5 bg-black bg-opacity-50"
  >
    <div class="bg-white rounded shadow-lg w-full max-w-lg mx-auto flex flex-col items-start p-5"
    >
      <button @click="closeDetails" class="self-end font-bold mb-3">X</button>
      <h2 class="text-2xl font-bold mb-3">
        {{ userDetails.data.name }} Detailed Weather:
      </h2>
      <div class="text-lg">
        Main Condition: {{ userDetails.data.weather.main }}
      </div>
      <div class="text-lg">
        Description: {{ userDetails.data.weather.description }}
      </div>
      <div class="text-lg">Temperature: {{ userDetails.data.main.temp }} F</div>
      <div class="text-lg">Humidity: {{ userDetails.data.main.humidity }}%</div>
      <div class="text-lg">
        Minimum Temperature: {{ userDetails.data.main.temp_min }} F
      </div>
      <div class="text-lg">
        Maximum Temperature: {{ userDetails.data.main.temp_max }} F
      </div>
    </div>
  </div>
</template>
