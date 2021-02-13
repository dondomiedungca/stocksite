<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="3">
                        <v-select outlined :items="product_type_selections" v-model="selected_product_type_id" label="Inventory Type" dense></v-select>
                    </v-col>
                    <v-col lg="3">
                        <v-btn color="primary" @click="isSearchOpen = true"> <v-icon>mdi-feature-search-outline</v-icon> Advance Search </v-btn>
                        <search-dialog @search="search" :currency="currency" :inventory_status_selections="inventory_status_selections" :inventory_cosmetic_selections="inventory_cosmetic_selections" :product_type="product_type" :isOpen="isSearchOpen" @close="isSearchOpen = false"></search-dialog>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col lg="12">
                        <p v-if="Object.keys(products).length">{{ products.from }} to {{ products.to }} of {{ products.total }} records</p>
                        <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="products.total" :headers="headers" :items="products.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                            <template v-slot:item="{ index, item }">
                                <tr>
                                    <td align="center">
                                        <small># {{ index + 1 }}</small>
                                    </td>
                                    <td align="center">
                                        <small
                                            ><v-btn @click="removeAttempt(item)" icon>
                                                <v-icon>mdi-trash-can</v-icon>
                                            </v-btn>
                                            <v-btn @click="editProduct(item)" icon>
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn @click="renderDetails(item.details)" icon>
                                                <v-icon>mdi-eye</v-icon>
                                            </v-btn></small
                                        >
                                    </td>
                                    <td align="center">
                                        <small>{{ item.stock_number }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.product_type.product_name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.status.name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.cosmetic.name }}</small>
                                    </td>
                                    <td align="center">
                                        <small v-if="item.item_cosmetic_description.length <= 30">{{ item.item_cosmetic_description }}</small>
                                        <small v-else>
                                            <v-btn @click="showDescription(item.item_cosmetic_description, item.stock_number, 'item_cosmetic_description')" color="primary" text small>{{ item.item_cosmetic_description.substring(0, 10) }}...</v-btn>
                                        </small>
                                    </td>
                                    <td align="center">
                                        <small v-if="item.item_description.length <= 30">{{ item.item_description }}</small>
                                        <small v-else>
                                            <v-btn @click="showDescription(item.item_description, item.stock_number, 'item_description')" color="primary" text small>{{ item.item_description.substring(0, 10) }}...</v-btn>
                                        </small>
                                    </td>
                                    <td align="center">
                                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(item.origin_price.toFixed(2)) }}</small>
                                    </td>
                                    <td align="center">
                                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(item.selling_price.toFixed(2)) }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.discount_percentage }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.date_created }}</small>
                                    </td>
                                </tr>
                            </template>
                        </v-data-table>
                        <v-layout class="d-flex justify-space-between align-center pt-3">
                            <v-pagination :total-visible="7" v-model="page" :length="pageCount"></v-pagination>
                        </v-layout>
                        <edit-dialog :product_type="product_type" :cosmetics="cosmetics" :statuses="statuses" :forEdit="forEdit" @close="isEditOpen = !isEditOpen" :isEditOpen="isEditOpen"></edit-dialog>
                        <view-specs :details="details" @close="isViewOpen = !isViewOpen" :isViewOpen="isViewOpen"></view-specs>
                        <v-dialog v-model="isShowDescription" width="500">
                            <v-card>
                                <v-card-title class="headline grey lighten-2"> {{ item_all_description.item_name }} - {{ item_all_description.item_column_name }} </v-card-title>
                                <v-card-text class="mt-3">
                                    {{ item_all_description.description }}
                                </v-card-text>

                                <v-divider></v-divider>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" @click="isShowDescription = false">
                                        Close
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import EditDialog from "./EditDialog"
import SearchDialog from "./SearchDialog"
import ViewSpecs from "./ViewSpecs"
import { mapMutations, mapActions, mapGetters, mapState } from "vuex"

export default {
    components: {
        EditDialog,
        ViewSpecs,
        SearchDialog
    },
    async created() {
        this.getProductTypes()
        await this.getProducts()
        await this.getCosmetics()
        await this.getStatuses()
        await this.getCurrency()
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
            isSearchOpen: false,
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
                    text: "#",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "primary white--text"
                },
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
            product_type_selections: [],
            product_type_list: [],
            products: {},
            searches: {},
            forEdit: {},
            product_type: {},
            product_types: [],
            title: "",
            details: {},
            currency: {},
            inventory_cosmetic_selections: [],
            inventory_status_selections: [],
            isShowDescription: false,
            item_all_description: {
                description: "",
                item_name: "",
                item_column_name: ""
            }
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
        getCurrency: function() {
            axios.get("/admin/currency/get-currency").then(result => {
                this.currency = result.data
            })
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
        showDescription(desc, item_name, item_column_name) {
            this.item_all_description.description = desc
            this.item_all_description.item_name = item_name
            this.item_all_description.item_column_name = item_column_name
            this.isShowDescription = true
        },
        getCosmetics: function() {
            return axios.get("/admin/products/get-cosmetics").then(res => {
                this.cosmetics = res.data.cosmetics
                this.inventory_cosmetic_selections = res.data.cosmetics.map(cosmetic => {
                    return {
                        text: cosmetic.name,
                        value: cosmetic.id
                    }
                })
            })
        },
        getStatuses: function() {
            return axios.get("/admin/products/get-item-statuses").then(res => {
                this.statuses = res.data.statuses
                this.inventory_status_selections = res.data.statuses.map(status => {
                    return {
                        text: status.name,
                        value: status.id
                    }
                })
            })
        },
        getProducts: function(page_continue) {
            this.loading = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.options

            var url = "/admin/products/get-products-via-product-types"
            return axios
                .post(url, {
                    page: typeof page_continue == "undefined" ? page : 1,
                    selected_product_type_id: this.selected_product_type_id,
                    search: this.searches
                })
                .then(response => {
                    this.products = response.data
                })
                .then(() => (this.loading = false))
        },
        search(searchData) {
            this.searches = searchData
            this.getProducts(1)
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
