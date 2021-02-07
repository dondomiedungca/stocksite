<template>
    <div>
        <v-main>
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <v-data-table :headers="headers" :items="products.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
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
                        <div class="text-left pt-2">
                            <v-pagination v-model="page" :length="pageCount"></v-pagination>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
export default {
    created() {
        this.getProductTypes()
        this.getProducts()
    },
    watch: {
        selected_product_type_id: function(newVal, oldVal) {
            this.getProducts(1)
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
                    align: "start",
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
            selected_product_type_id: 1,
            forEdit: {},
            product_type: {},
            title: "",
            details: {},
            itemsPerPage: 10,
            page: 1,
            pageCount: 0
        }
    },
    methods: {
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
            axios.get("/admin/products/get-all-product-types").then(res => {
                this.product_type_list = res.data.product_types
            })
        },
        getProducts: function(page) {
            if (typeof page === "undefined") {
                page = 1
            }
            var url = "/admin/products/get-products-via-product-types/" + this.selected_product_type_id + "/" + JSON.stringify(this.searches)
            axios.get(url + "?page=" + page).then(response => {
                this.products = response.data
            })
        },
        renderDetails: function(name, details) {
            this.title = name
            this.details = details
            this.$refs.details.trigger()
        },
        editProduct: function(product) {
            this.title = product.stock_number
            this.forEdit = product
            this.product_type = product.product_type
            this.$refs.edit.trigger()
        },
        validateThenSAve: function() {
            this.$refs.validate.validate()
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
