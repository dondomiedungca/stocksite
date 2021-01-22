import Vue from "vue"
import vuetify from "../plugins"
import VueRouter from "vue-router"

Vue.component("breadcrumbs-vue", require("./../components/reusable/BreadCrumbs.vue").default)
Vue.component("sidebar-vue", require("./../components/reusable/SideBar.vue").default)
Vue.component("appbar-vue", require("./../components/reusable/AppBar.vue").default)

import ProductIndex from "./../components/product/Index.vue"

const routes = [{ path: "/admin/products", component: ProductIndex }]

const router = new VueRouter({
    routes,
    mode: "history"
})

const app = new Vue({
    el: "#app",
    vuetify,
    router
})
