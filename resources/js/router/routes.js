const routes = [
    {
        path: "",
        component: () => import("../pages/Login.vue"),
        name: "home",
    },
    {
        path: "/login",
        component: () => import("../pages/Login.vue"),
        name: "login",
    },
    {
        path: "/stocks",
        component: () => import("../pages/stock/Stock.vue"),
        name: "stock",
    },
    {
        path: "/dashboard",
        component: () => import("../pages/Dashboard.vue"),
        name: "dashboard",
    },
    {
        path: "/virtual-investment",
        component: () => import("../pages/virtual/VirtualInvestment.vue"),
        name: "virtualinvestment",
    },
    {
        path: "/virtual-investment/clients/:clientID",
        component: () => import("../pages/virtual/ClientsStock.vue"),
        name: "clientstock",
    },
    {
        path: "*",
        component: () => import("../pages/PageNotFound.vue"),
    },
];
export default routes;
