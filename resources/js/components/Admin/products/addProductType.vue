<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Manage Product Types</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="card bg-light">
                    <div class="card-header">
                        <strong><strong>Create New Product Types</strong></strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group col-md-4">
                            <label>Product Type Name</label>
                            <input type="text" class="form-control from-control-sm" :class="{ 'is-invalid': $v.product_type_name.$error }" name v-model="$v.product_type_name.$model" @change="product_type_name = $event.target.value" />
                            <div class="invalid-feedback" v-if="$v.product_type_name.$error">This field is required and only alpha is allowed</div>
                        </div>
                        <div class="form-group col-md-4">
                            <button class="btn btn-primary btn-sm" @click="showColumnManaging"><i class="fa fa-edit"></i> Manage Columns</button>
                            <button v-if="columns.length" class="btn btn-primary btn-sm" @click="create_product_type"><i class="fa fa-check"></i> Create Product Type</button>
                        </div>
                        <br />
                        <br />
                        <br />
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Remove</th>
                                    <th scope="col">Column Name</th>
                                    <th scope="col">Data Type</th>
                                    <th scope="col">Is Manually</th>
                                    <th scope="col">Is Required</th>
                                    <th scope="col">Input Type</th>
                                    <th scope="col">Selection</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(column, i) in columns" v-bind:key="i">
                                    <td>
                                        <center>
                                            <button @click="removeColumn(i)" class="btn btn-sm btn-danger">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </center>
                                    </td>
                                    <td>{{ column.column_name }}</td>
                                    <td>{{ column.data_type }}</td>
                                    <td>{{ column.manual }}</td>
                                    <td>{{ column.required }}</td>
                                    <td>{{ column.input_type }}</td>
                                    <td>{{ column.selection }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <modal ref="modal" :title="'Product Type - ' + product_type_name" :show_submit="true" :icon="'fa fa-trash'" :submit_name="'Reset'" @met="reset">
                        <columns-managing ref="columns" @addColumn="insertColumn"></columns-managing>
                    </modal>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-header">
                        <strong><strong>Product Type List</strong></strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 v-if="Object.keys(product_types).length">
                                    Showing
                                    {{ product_types.from != null ? product_types.from : 0 }}
                                    to
                                    {{ product_types.to != null ? product_types.to : 0 }} of {{ product_types.total != null ? product_types.total : 0 }} product types
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Actions</th>
                                            <th scope="col">Product Type Name</th>
                                            <th scope="col">No. Of Columns</th>
                                            <th scope="col">Created At</th>
                                            <th scope="col">Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="Object.keys(product_types).length">
                                        <tr v-if="!product_types.data.length">
                                            <td colspan="5">
                                                <center>
                                                    No Product Types
                                                </center>
                                            </td>
                                        </tr>
                                        <tr v-for="(product_type, i) in product_types.data" v-bind:key="i">
                                            <td>
                                                <center>
                                                    <button @click="deleteProductType(product_type.id)" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="fa fa-info"></i>
                                                    </button>
                                                </center>
                                            </td>
                                            <td>{{ product_type.product_name }}</td>
                                            <td>{{ product_type.product_attributes.length }}</td>
                                            <td>{{ date_format(product_type.created_at) }}</td>
                                            <td>{{ product_type.user.first_name }} {{ product_type.user.last_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { required, alpha } from "vuelidate/lib/validators"
import moment from "moment"

export default {
    data() {
        return {
            product_type_name: "",
            columns: [],
            product_types: {}
        }
    },
    created() {
        this.getProductTypes()
    },
    methods: {
        date_format: function(date) {
            return moment(date).format("MMMM DD, YYYY")
        },
        getProductTypes: function(page) {
            if (typeof page == "undefined") {
                page = 1
            }

            var url = "/admin/products/get-product-types"
            axios.get(url + "?page=" + page).then(response => {
                this.product_types = response.data
            })
        },
        create_product_type: function() {
            var self = this
            if (this.columns.length) {
                var params = { columns: self.columns, product_name: self.product_type_name }
                swal.queue([
                    {
                        title: "Create New Product Type",
                        text: "Are you sure? All details is correct?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/admin/products/add-product-type", params).then(result => {
                                swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                                self.getProductTypes()
                            })
                        }
                    }
                ])
            } else {
                swal.fire("No Column Specified", "Please add column(s) for this product type", "error")
            }
        },
        showColumnManaging: function() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                this.$refs.modal.trigger()
            }
        },
        insertColumn: function(data) {
            var column_name = data[0]
            var data_type = data[1]
            var manual = data[2]
            var required = data[3]
            var input_type = data[4]
            var selection = data[5]

            var column = {
                column_name,
                data_type,
                manual,
                required,
                input_type,
                selection: input_type != "SELECTION" ? "Input Type" : selection
            }
            this.columns.push(column)
        },
        removeColumn: function(index) {
            this.columns.splice(index, 1)
        },
        reset: function() {
            this.$refs.columns.reset()
        },
        deleteProductType: function(id) {
            var self = this
            swal.queue([
                {
                    title: "Remove Product Type",
                    text: "This will remove all related columns, inventories and files, Do you want to proceed?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, remove it",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.get(`/admin/products/remove-product-type/${id}`).then(result => {
                            swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                            self.getProductTypes()
                        })
                    }
                }
            ])
        }
    },
    validations: {
        product_type_name: {
            required,
            alpha
        }
    }
}
</script>

<style></style>
