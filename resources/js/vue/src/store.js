import Vuex from "vuex"

// pages
import * as add_product from "./../components/product/add-product/assets/store"
import * as product_list from "./../components/product/product-list/assets/store"
import * as product_import from "./../components/product/import-product/assets/store"

// reusable
import * as table from "./../components/reusable/Table/store"
import * as snackBar from "../components/reusable/SnackBar/store"
import * as stepper from "./../components/reusable/Stepper/store"

const modules = {
    // pages
    add_product,
    product_list,
    product_import,
    // reusable
    table,
    snackBar,
    stepper
}

// export default store

export default new Vuex.Store({
    modules
})
