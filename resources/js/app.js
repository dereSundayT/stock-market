require("./bootstrap");
window.Vue = require("vue").default;

import VueRouter from "vue-router";
Vue.use(VueRouter);
import router from "./router";

// Vue.component("articles", require("./components/Stocks.vue").default);

const app = new Vue({
    el: "#app",
    router,
});
