import { createApp } from "vue";
import { createPinia } from "pinia";
import PrimeVue from "primevue/config";

import App from "./App.vue";
import router from "./router";

import "./assets/main.css";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup"; // optional
import Row from "primevue/row";
import Skeleton from "primevue/skeleton";
import InputText from "primevue/inputtext"; // optional

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue);
app.component("DataTable", DataTable);
app.component("Column", Column);
app.component("ColumnGroup", ColumnGroup);
app.component("Row", Row);
app.component('Button', Button);
app.component('Skeleton', Skeleton);
app.component("InputText", InputText);

app.mount("#app");
