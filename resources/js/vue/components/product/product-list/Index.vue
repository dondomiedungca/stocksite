<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="3">
                        <v-select outlined :items="product_type_selections" v-model="selected_product_type_id" label="Inventory Type" dense></v-select>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col lg="12">
                        <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="products.total" :headers="headers" :items="products.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                            <template v-slot:item.actions="{ item }">
                                <v-btn @click="removeAttempt(item)" icon>
                                    <v-icon>mdi-trash-can</v-icon>
                                </v-btn>
                                <v-btn @click="editProduct(item)" icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn @click="renderDetails(item.details)" icon>
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
                            </template>
                            <template v-slot:item.product_type="{ item }">
                                <small>
                                    {{ item.product_type.product_name }}
                                </small>
                            </template>
                            <template v-slot:item.cosmetic="{ item }">
                                <small>
                                    {{ item.cosmetic.name }}
                                </small>
                            </template>
                            <template v-slot:item.status="{ item }">
                                <small>
                                    {{ item.status.name }}
                                </small>
                            </template>
                            <template v-slot:item.date_created="{ item }">
                                <small>
                                    {{ item.date_created }}
                                </small>
                            </template>
                        </v-data-table>
                        <v-layout class="d-flex justify-space-between align-center pt-3">
                            <v-pagination :total-visible="7" v-model="page" :length="pageCount"></v-pagination>
                        </v-layout>
                        <edit-dialog :product_type="product_type" :cosmetics="cosmetics" :statuses="statuses" :forEdit="forEdit" @close="isEditOpen = !isEditOpen" :isEditOpen="isEditOpen"></edit-dialog>
                        <view-specs :details="details" @close="isViewOpen = !isViewOpen" :isViewOpen="isViewOpen"></view-specs>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import EditDialog from "./EditDialog"
import ViewSpecs from "./ViewSpecs"
import { mapMutations, mapActions, mapGetters, mapState } from "vuex"

export default {
    components: {
        EditDialog,
        ViewSpecs
    },
    async created() {
        this.getProductTypes()
        await this.getProducts()
        await this.getCosmetics()
        await this.getStatuses()
        if (this.products.data.length) {
            this.product_type = this.products.data[0].product_type
        }
    },
    watch: {
        selected_product_type_id: function(newVal, oldVal) {
            this.getProducts(1)
            this.product_type = this.product_types.find(pt => newVal == pt.id)
        },
        options() {
            this.getProducts()
        }
    },
    data() {
        return {
            items: [
                {
                    text: "Products",
                    disabled: false,
                    exact: true,
                    to: "/admin/products"
                },
                {
                    text: "Product Lists",
                    disabled: true,
                    to: "product-list"
                }
            ],
            headers: [
                {
                    text: "Actions",
                    align: "center",
                    sortable: false,
                    value: "actions",
                    class: "primary white--text"
                },
                {
                    text: "Stock No.",
                    align: "start",
                    sortable: false,
                    value: "stock_number",
                    class: "primary white--text"
                },
                { text: "Product Type", value: "product_type", align: "start", sortable: false, class: "primary white--text" },
                { text: "Inventory Status", value: "status", align: "start", sortable: false, class: "primary white--text" },
                { text: "Cosmetic", value: "cosmetic", align: "start", sortable: false, class: "primary white--text" },
                { text: "Cosmetic Description", value: "item_cosmetic_description", align: "start", sortable: false, class: "primary white--text" },
                { text: "Description", value: "item_description", align: "start", sortable: false, class: "primary white--text" },
                { text: "Origin Price", value: "origin_price", align: "start", sortable: false, class: "primary white--text" },
                { text: "Selling Price", value: "selling_price", align: "start", sortable: false, class: "primary white--text" },
                { text: "Discout Percentage", value: "discount_percentage", align: "start", sortable: false, class: "primary white--text" },
                { text: "Date Added", value: "date_created", align: "start", sortable: false, class: "primary white--text" }
            ],
            product_type_list: [],
            products: {},
            searches: {},
            forEdit: {},
            product_type: {},
            product_types: [],
            title: "",
            details: {},
            itemsPerPage: 10,
            page: 1,
            pageCount: 0,
            loading: true,
            options: {},
            //
            isEditOpen: false,
            isViewOpen: false,
            forEdit: {},
            cosmetics: [],
            statuses: [],
            selected_product_type_id: 1,
            product_type_selections: []
        }
    },
    methods: {
        ...mapMutations(["setEditOpen"]),
        numberWithCommas: function(x) {
            if (x == "" || x == null) {
                return 0
            } else {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            }
        },
        date_format: function(date) {
            return moment(date).format("MMMM DD, YYYY")
        },
        getProductTypes: function() {
            return axios.get("/admin/products/get-all-product-types").then(res => {
                this.product_types = res.data.product_types
                this.product_type_selections = res.data.product_types.map(product_type => {
                    return {
                        text: product_type.product_name,
                        value: product_type.id
                    }
                })
            })
        },
        getCosmetics: function() {
            return axios.get("/admin/products/get-cosmetics").then(res => {
                this.cosmetics = res.data.cosmetics
            })
        },
        getStatuses: function() {
            return axios.get("/admin/products/get-item-statuses").then(res => {
                this.statuses = res.data.statuses
            })
        },
        getProducts: function() {
            this.loading = true
            let { sortBy, sortDesc, page, itemsPerPage } = this.options

            var url = "/admin/products/get-products-via-product-types/" + this.selected_product_type_id + "/" + JSON.stringify(this.searches)
            return axios
                .get(url + "?page=" + page)
                .then(response => {
                    this.products = response.data
                })
                .then(() => (this.loading = false))
        },
        editProduct: function(product) {
            this.forEdit = product
            this.isEditOpen = !this.isEditOpen
        },
        renderDetails: function(details) {
            this.details = details
            this.isViewOpen = !this.isViewOpen
        },
        removeAttempt: function(product) {
            var self = this
            swal.queue([
                {
                    title: "Remove " + product.stock_number,
                    text: "This will permanently remove, proceed?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#42d1f5",
                    confirmButtonText: "Yes, remove now",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios
                            .post("/admin/products/remove-product", {
                                inventory: product
                            })
                            .then(response => {
                                swal.fire({
                                    title: response.data.heading,
                                    text: response.data.message,
                                    icon: response.data.isSuccess ? "success" : "error"
                                })
                                self.getProducts(self.products.current_page)
                            })
                    }
                }
            ])
        }
    }
}
</script>

<style></style>
