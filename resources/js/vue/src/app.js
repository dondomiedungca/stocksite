require("../../bootstrap")
import Vue from "vue"
import vuetify from "../plugins"
import store from "./store"

Vue.filter("numWithCommas", num => num && num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
Vue.filter("emptyFormatter", data => {
    if (data && data != "" && data != "null" && data != null && data.length != 0) return data
    return "-"
})

// Reusable
Vue.component("address-details", require("./../components/reusable/AddressDetails.vue").default)

import App from "./../components/App"
import ProductIndex from "./../components/product/Index.vue"
import AddProduct from "./../components/product/add-product/Index.vue"
import ProductLists from "./../components/product/product-list"
import ImportProduct from "./../components/product/import-product/Index.vue"

import PurchasingIndex from "./../components/purchasing/Index.vue"
import CreatePurchasing from "./../components/purchasing/create-purchasing/CreatePurchasing.vue"
import PurchasingList from "./../components/purchasing/purchasing-list/PurchasingList.vue"
import PurchasingDetails from "./../components/purchasing/purchasing-list/PurchasingDetails.vue"

import SupplierReceiverList from "./../components/supplier/SupplierReceiverList.vue"

import ReportIndex from "./../components/reports/ReportIndex.vue"
import QueueMonitoring from "./../components/reports/queue/QueueMonitoring.vue"

const app = new Vue({
    el: "#app",
    vuetify,
    store,
    components: {
        App, // main
        ProductIndex,
        AddProduct,
        ProductLists,
        ImportProduct,
        PurchasingIndex,
        CreatePurchasing,
        PurchasingList,
        PurchasingDetails,
        SupplierReceiverList,
        ReportIndex,
        QueueMonitoring
    }
})
