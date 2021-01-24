import Vue from "vue"
import vuetify from "../plugins"
import VueRouter from "vue-router"

// Children Routes
import product_related_routes from "./../components/product/routes"

// Reusable
Vue.component("breadcrumbs-vue", require("./../components/reusable/BreadCrumbs.vue").default)
Vue.component("sidebar-vue", require("./../components/reusable/SideBar.vue").default)
Vue.component("appbar-vue", require("./../components/reusable/AppBar.vue").default)
Vue.component("menucard-vue", require("./../components/reusable/MenuCard.vue").default)

import ProductIndex from "./../components/product/Index.vue"
import AddProductType from "./../components/product/add-product/AddProductType"
const routes = [
    { path: "/admin/products", component: ProductIndex },
    {
        path: "/admin/products/add-product-type",
        component: AddProductType
    },
    {
        path: "/admin/products/product-list",
        component: AddProductType
    },
    {
        path: "/admin/products/product-import",
        component: AddProductType
    }
]

const router = new VueRouter({
    routes,
    mode: "history"
})

const app = new Vue({
    el: "#app",
    vuetify,
    router
})
