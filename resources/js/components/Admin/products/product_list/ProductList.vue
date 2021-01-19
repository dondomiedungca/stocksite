<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Manage Product Lists</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <bread-crumbs :urls="urls"></bread-crumbs>
        <div class="row">
            <div class="col-md-4">
                <label for="">Navigate Product Type</label>
                <select class="custom-select custom-select-sm" v-model="selected_product_type_id">
                    <option v-for="(product_type, i) in product_type_list" :value="product_type.id" v-bind:key="i">{{ product_type.product_name }}</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <small v-if="Object.keys(products).length">Showing {{ products.from == null ? 0 : products.from }} to {{ products.to == null ? 0 : products.to }} of {{ numberWithCommas(products.total) }} inventories(s)</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Stocks No.</th>
                                <th>Product Type</th>
                                <th>Inventory Status</th>
                                <th>Cosmetic</th>
                                <th>Cosmetic Description</th>
                                <th>Description</th>
                                <th>Origin Price</th>
                                <th>Selling Price</th>
                                <th>Discount Percentage</th>
                                <th>Full Details</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody v-if="Object.keys(products).length">
                            <tr v-if="!products.data.length">
                                <td colspan="13" align="center">No Results Found</td>
                            </tr>
                            <tr v-else v-for="(product, i) in products.data" v-bind:key="i">
                                <td align="center">#{{ i + 1 }}</td>
                                <td align="center">
                                    <button @click="removeAttempt(product)" class="btn btn-sm btn-danger">
                                        <i class="lni lni-trash"></i>
                                    </button>
                                    <button @click="editProduct(product)" class="btn btn-sm btn-primary">
                                        <i class="lni lni-pencil-alt"></i>
                                    </button>
                                </td>
                                <td align="center">
                                    {{ product.stock_number }}
                                </td>
                                <td align="center">
                                    {{ product.product_type.product_name }}
                                </td>
                                <td align="center">
                                    {{ product.status.name }}
                                </td>
                                <td align="center">
                                    {{ product.cosmetic.name }}
                                </td>
                                <td align="center">
                                    {{ product.item_cosmetic_description }}
                                </td>
                                <td align="center">
                                    {{ product.item_description }}
                                </td>
                                <td align="center">
                                    {{ product.origin_price }}
                                </td>
                                <td align="center">
                                    {{ product.selling_price }}
                                </td>
                                <td align="center">
                                    {{ product.discount_percentage }}
                                </td>
                                <td align="center">
                                    <button @click="renderDetails(product.stock_number, product.details)" class="btn btn-sm btn-primary">
                                        <i class="lni lni-eye"></i>
                                    </button>
                                </td>
                                <td align="center">
                                    {{ date_format(product.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <paginate :data="products" :limit="3" v-on:pagination-change-page="getProducts"></paginate>
            </div>
        </div>
        <modal ref="details" :keyId="'details'" :title="title + ' - Full Details'" :show_submit="false">
            <product-details :details="details"></product-details>
        </modal>
        <modal ref="edit" :keyId="'edit'" :title="title + ' - Edit Mode'" :show_submit="true" :icon="'fa fa-check'" :submit_name="'Save Changes'" @met="validateThenSAve">
            <edit-product ref="validate" :product="forEdit" :product_type="product_type"></edit-product>
        </modal>
    </div>
</template>

<script>
import moment from "moment"
import Pagination from "./../../../Reusable/Pagination"
export default {
    components: {
        paginate: Pagination
    },
    watch: {
        selected_product_type_id: function(newVal, oldVal) {
            this.getProducts(1)
        }
    },
    created() {
        this.getProductTypes()
        this.getProducts()
    },
    data() {
        return {
            forEdit: {},
            product_type: {},
            title: "",
            details: {},
            urls: [
                {
                    name: "Products",
                    url: "/admin/products",
                    active: false
                },
                {
                    name: "Product Lists",
                    active: true
                }
            ],
            product_type_list: [],
            selected_product_type_id: 1,
            products: {},
            searches: {}
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
                            })
                    }
                }
            ])
        }
    }
}
</script>

<style></style>
