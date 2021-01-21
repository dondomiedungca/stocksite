/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap")

window.Vue = require("vue")

import Vuelidate from "vuelidate"
import VueNotifications from "vue-notifications"
import iziToast from "izitoast"
import "izitoast/dist/css/iziToast.min.css"

const toastTypes = {
    success: "success",
    error: "error",
    info: "info",
    warn: "warn"
}

function toast({ title, message, type, timeout, position, cb }) {
    if (type === VueNotifications.types.warn) type = "warning"
    return iziToast[type]({ title, message, timeout, position })
}

const options = {
    success: toast,
    error: toast,
    info: toast,
    warn: toast
}

Vue.use(VueNotifications, options)
Vue.use(Vuelidate)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// ADMIN
Vue.component("dashboard-index", require("./components/Admin/dashboard/Index.vue").default)

Vue.component("purchasing-index", require("./components/Admin/purchasing/Index.vue").default)
Vue.component("purchasing-order", require("./components/Admin/purchasing/Purchasing.vue").default)
Vue.component("purchasing-list", require("./components/Admin/purchasing/PurchasingList.vue").default)
Vue.component("purchasing-details", require("./components/Admin/purchasing/PurchasingDetails.vue").default)

Vue.component("products-index", require("./components/Admin/products/Index.vue").default)
Vue.component("product-list", require("./components/Admin/products/product_list/ProductList.vue").default)
Vue.component("product-types", require("./components/Admin/products/addProductType.vue").default)
Vue.component("import-manual", require("./components/Admin/products/ImportManual.vue").default)
Vue.component("import-file", require("./components/Admin/products/ImportFile.vue").default)
Vue.component("product-details", require("./components/Admin/products/product_list/Details.vue").default)
Vue.component("edit-product", require("./components/Admin/products/product_list/EditProduct.vue").default)
Vue.component("product-import", require("./components/Admin/products/product_list/ProductImport.vue").default)

Vue.component("reports-index", require("./components/Admin/reports/Index.vue").default)
Vue.component("queue-monitoring-index", require("./components/Admin/reports/QueueMonitoring.vue").default)

// REUSABLE
Vue.component("modal", require("./components/Reusable/Modal.vue").default)
Vue.component("address-form", require("./components/Reusable/AddressForm.vue").default)
Vue.component("pagination", require("./components/Reusable/Pagination.vue").default)
Vue.component("bread-crumbs", require("./components/Reusable/BreadCrumbs.vue").default)

// SUPPLIER
Vue.component("add-supplier", require("./components/Admin/supplier/addSupplier.vue").default)

// CUSTOMER

// MODULES
Vue.component("columns-managing", require("./components/Admin/products/ManageColumns.vue").default)
Vue.component("edit-product-type", require("./components/Admin/products/EditProductTypes.vue").default)
Vue.component("add-receiver", require("./components/Admin/purchasing/AddReceiver.vue").default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
})
