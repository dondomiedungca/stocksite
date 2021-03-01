<template>
    <div>
        <v-main id="v-main">
            <div class="custom-top-navigation">
                <v-container fluid>
                    <div class="nav-title font-weight-thin"><v-icon class="nav-icon mr-5">mdi-view-list</v-icon>PRODUCT LISTS</div>
                </v-container>
                <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            </div>
            <v-container fluid>
                <v-row>
                    <v-col lg="3">
                        <v-select outlined :items="product_type_selections" v-model="selected_product_type_id" label="Inventory Type" dense></v-select>
                    </v-col>
                    <v-col lg="3">
                        <v-btn small class="mt-1" color="dark" @click="isSearchOpen = true"> <v-icon>mdi-feature-search-outline</v-icon> Advance Search </v-btn>
                        <search-dialog @search="search" :currency="currency" :inventory_status_selections="inventory_status_selections" :inventory_cosmetic_selections="inventory_cosmetic_selections" :product_type="product_type" :isOpen="isSearchOpen" @close="isSearchOpen = false"></search-dialog>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col lg="12">
                        <span v-if="Object.keys(products).length">
                            <small v-if="products.total">{{ products.from }} - {{ products.to }} of {{ products.total }} records</small>
                        </span>
                        <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="products.total" :headers="headers" :items="products.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                            <template v-slot:item="{ index, item }">
                                <tr>
                                    <td align="center">
                                        <small># {{ index + 1 }}</small>
                                    </td>
                                    <td align="center">
                                        <small>
                                            <!-- <v-btn @click="removeAttempt(item)" icon>
                                                <v-icon>mdi-trash-can</v-icon>
                                            </v-btn> -->
                                            <asker icon_header="mdi-alert" :icon="true" :fab="false" icon_header_color="red" :tooltip_show="false" title="Are you sure? you want to delete this item?" @proceed="remove(item)">
                                                <template v-slot:togglerIcon>
                                                    <v-icon>
                                                        mdi-delete
                                                    </v-icon>
                                                </template>
                                            </asker>
                                            <v-btn @click="editProduct(item)" icon>
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn @click="renderDetails(item.details)" icon>
                                                <v-icon>mdi-eye</v-icon>
                                            </v-btn>
                                        </small>
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
                    to: "/admin/products",
                    icon: "mdi-warehouse"
                },
                {
                    text: "Product Lists",
                    disabled: true,
                    to: "product-list",
                    icon: "mdi-view-list"
                }
            ],
            headers: [
                {
                    text: "#",
                    align: "center",
                    sortable: false,
                    value: "index"
                },
                {
                    text: "Actions",
                    align: "center",
                    sortable: false,
                    value: "actions"
                },
                {
                    text: "Stock No.",
                    align: "center",
                    sortable: false,
                    value: "stock_number"
                },
                { text: "Product Type", value: "product_type", align: "center", sortable: false },
                { text: "Inventory Status", value: "status", align: "center", sortable: false },
                { text: "Cosmetic", value: "cosmetic", align: "center", sortable: false },
                { text: "Cosmetic Description", value: "item_cosmetic_description", align: "center", sortable: false },
                { text: "Description", value: "item_description", align: "center", sortable: false },
                { text: "Origin Price", value: "origin_price", align: "center", sortable: false },
                { text: "Selling Price", value: "selling_price", align: "center", sortable: false },
                { text: "Discout Percentage", value: "discount_percentage", align: "center", sortable: false },
                { text: "Date Added", value: "date_created", align: "center", sortable: false }
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
        ...mapMutations(["setEditOpen", "setSnackbar"]),
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
        remove: function(product) {
            var self = this
            axios
                .post("/admin/products/remove-product", {
                    inventory: product
                })
                .then(response => {
                    this.$store.commit("setSnackbar", {
                        isVisible: true,
                        type: response.data.success ? "success" : "error",
                        text: response.data.message
                    })
                    self.getProducts(self.products.current_page)
                })
        }
    }
}
</script>

<style></style>
