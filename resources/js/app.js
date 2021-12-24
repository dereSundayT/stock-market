require("./bootstrap");
window.Vue = require("vue").default;

import VueRouter from "vue-router";
Vue.use(VueRouter);
import router from "./router";

import Vuetify from "../plugins/vuetify";

const app = new Vue({
    el: "#app",
    vuetify: Vuetify,
    router,
});
//9:59
