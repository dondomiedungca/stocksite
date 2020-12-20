<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Create Purchasing</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button type="button" @click="showReceiver" class="btn btn-sm btn-outline-secondary">
                        Create Receiver
                    </button>
                    <button type="button" @click="showSupplier" class="btn btn-sm btn-outline-secondary">
                        Add Supplier
                    </button>
                    <modal ref="addsupplier" :keyId="'addSupplier'" :title="'Add Supplier'" :show_submit="true" :icon="'fa fa-check'" :submit_name="'Add Supplier'" @met="saveSupplier">
                        <add-supplier @getSuppliers="getSuppliers" ref="addsupplierpage"></add-supplier>
                    </modal>
                    <modal ref="addreceiver" :keyId="'addReceiver'" :title="'Create New Receiver'" :show_submit="true" :icon="'fa fa-check'" :submit_name="'Add Receiver'" @met="saveReceiver">
                        <add-receiver @getReceiver="getReceivers" ref="addreceiverpage"></add-receiver>
                    </modal>
                </div>
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
                        <strong><strong>Supplier Details</strong></strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group col-6">
                            <label for="formGroupExampleInput2">Select Supplier</label>
                            <select :class="{ 'is-invalid': $v.selected_supplier.$error }" v-model="selected_supplier" class="custom-select custom-select-sm">
                                <option v-for="(supplier, i) in suppliers" :value="supplier" v-bind:key="i">{{ supplier.supplier_name }}</option>
                            </select>
                            <div class="invalid-feedback" v-if="$v.selected_supplier.$error">This field is required</div>
                        </div>
                        <div class="form-group col-8">
                            <table style="background: #fff;" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="3">* Primary Supplier Details</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Supplier Name</b></td>
                                        <td v-if="Object.keys(selected_supplier).length">{{ selected_supplier.supplier_name }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Supplier Emai</b>l</td>
                                        <td v-if="Object.keys(selected_supplier).length">{{ selected_supplier.supplier_email }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Supplier Phone</b></td>
                                        <td v-if="Object.keys(selected_supplier).length">{{ selected_supplier.supplier_phone_number }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">* Supplier Address</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Full Address</b></td>
                                        <td v-if="Object.keys(selected_supplier).length">
                                            <b>{{ selected_supplier.address.address_type.address_type_name }}</b> - {{ selected_supplier.address.address_no_or_house_building_no }} {{ selected_supplier.address.address_st }} {{ selected_supplier.address.address_city }} {{ selected_supplier.address.address_state }} {{ selected_supplier.address.address_country }} {{ selected_supplier.address.address_post_code }}
                                        </td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">* Manufacture Type</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Manufacturer</b></td>
                                        <td v-if="Object.keys(selected_supplier).length">{{ selected_supplier.manufacturer.manufacturer_type_name }}</td>
                                        <td v-else></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-5">
                <div class="card bg-light">
                    <div class="card-header">
                        <strong><strong>Receiver Details</strong></strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group col-6">
                            <label for="formGroupExampleInput2">Select Receiver</label>
                            <select :class="{ 'is-invalid': $v.selected_receiver.$error }" v-model="selected_receiver" class="custom-select custom-select-sm">
                                <option v-for="(receiver, i) in receivers" :value="receiver" v-bind:key="i">{{ receiver.receiver_last_name }}, {{ receiver.receiver_first_name }}</option>
                            </select>
                            <div class="invalid-feedback" v-if="$v.selected_receiver.$error">This field is required</div>
                        </div>
                        <div class="form-group col-8">
                            <table style="background: #fff;" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="3">* Primary Receiver Details</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Receiver Name</b></td>
                                        <td v-if="Object.keys(selected_receiver).length">{{ selected_receiver.receiver_first_name }} {{ selected_receiver.receiver_last_name }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Receiver Emai</b>l</td>
                                        <td v-if="Object.keys(selected_receiver).length">{{ selected_receiver.receiver_email }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Receiver Phone</b></td>
                                        <td v-if="Object.keys(selected_receiver).length">{{ selected_receiver.receiver_phone }}</td>
                                        <td v-else></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">* receiver Address</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><b>Full Address</b></td>
                                        <td v-if="Object.keys(selected_receiver).length">
                                            <b>{{ selected_receiver.address.address_type.address_type_name }}</b> - {{ selected_receiver.address.address_no_or_house_building_no }} {{ selected_receiver.address.address_st }} {{ selected_receiver.address.address_city }} {{ selected_receiver.address.address_state }} {{ selected_receiver.address.address_country }} {{ selected_receiver.address.address_post_code }}
                                        </td>
                                        <td v-else></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-5">
                <div class="card bg-light">
                    <div class="card-header">
                        <strong><strong>Inventory Details</strong></strong>
                    </div>
                    <div class="card-body">
                        <button @click="moreItems" class="btn btn-primary btn-sm mb-2"><i class="fa fa-plus"></i> Add Items for Purchasing</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Remove</th>
                                    <th>Inventory Type</th>
                                    <th>Inventory Description</th>
                                    <th>Item(s) Quantity</th>
                                    <th>Unit Amount</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(inventory, i) in inventories" v-bind:key="i">
                                    <td align="center">
                                        <button @click="removeInventory(i)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <select class="custom-select custom-select-sm" :class="{ 'is-invalid': $v.inventories.$each[i.toString()].inventory_type.$error }" name v-model="$v.inventories.$each[i.toString()].inventory_type.$model" @change="inventory.inventory_type = $event.target.value">
                                            <option disabled selected value="">-- Select Inventory Type --</option>
                                            <option v-for="(product_type, i) in product_types" :value="product_type.id" v-bind:key="i">{{ product_type.product_name }}</option>
                                        </select>
                                        <div class="invalid-feedback" v-if="$v.inventories.$each[i.toString()].inventory_type.$error">This field is required</div>
                                    </td>
                                    <td>
                                        <input type="text" :class="{ 'is-invalid': $v.inventories.$each[i.toString()].inventory_description.$error }" name v-model="$v.inventories.$each[i.toString()].inventory_description.$model" @change="inventory.inventory_description = $event.target.value" class="form-control form-control-sm" />
                                        <div class="invalid-feedback" v-if="$v.inventories.$each[i.toString()].inventory_description.$error">This field is required</div>
                                    </td>
                                    <td>
                                        <input type="number" :class="{ 'is-invalid': $v.inventories.$each[i.toString()].inventory_quantity.$error }" name v-model="$v.inventories.$each[i.toString()].inventory_quantity.$model" @change="inventory.inventory_quantity = $event.target.value" class="form-control form-control-sm" />
                                        <div class="invalid-feedback" v-if="$v.inventories.$each[i.toString()].inventory_quantity.$error">This field is required and make it greater that 0</div>
                                    </td>
                                    <td>
                                        <input type="number" :class="{ 'is-invalid': $v.inventories.$each[i.toString()].inventory_unit_amount.$error }" name v-model="$v.inventories.$each[i.toString()].inventory_unit_amount.$model" @change="inventory.inventory_unit_amount = $event.target.value" class="form-control form-control-sm" />
                                        <div class="invalid-feedback" v-if="$v.inventories.$each[i.toString()].inventory_unit_amount.$error">This field is required and make it greater that 0</div>
                                    </td>
                                    <td>
                                        <center><span v-html="currency.symbol"></span> {{ numberWithCommas(subtotal_price[i]) }}</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="5">
                                        Sub Total
                                    </td>
                                    <td>
                                        <center><span v-html="currency.symbol"></span> {{ numberWithCommas(subTotal) }}</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="3">
                                        Additional Cost
                                    </td>
                                    <td>
                                        <input type="number" :class="{ 'is-invalid': $v.additional_cost.$error }" name v-model="$v.additional_cost.$model" @change="additional_cost = $event.target.value" class="form-control form-control-sm" />
                                        <div class="invalid-feedback" v-if="$v.additional_cost.$error">Only digit is allowed</div>
                                    </td>
                                    <td align="right">
                                        <b>Total</b>
                                    </td>
                                    <td>
                                        <center>
                                            <span v-html="currency.symbol"></span> <b>{{ numberWithCommas(total) }}</b>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button @click="createPurchaseOrder" class="btn btn-primary btn-sm mb-2"><i class="fa fa-check"></i> Create Purchase Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { required, decimal } from "vuelidate/lib/validators"

const greater = value => value > 0

export default {
    data() {
        return {
            currency: {},
            suppliers: [],
            selected_supplier: {},
            receivers: [],
            selected_receiver: {},
            product_types: [],
            additional_cost: 0,
            inventories: [
                {
                    inventory_type: "",
                    inventory_description: "",
                    inventory_quantity: 0,
                    inventory_unit_amount: 0,
                    inventory_total_price: ""
                }
            ]
        }
    },
    created() {
        this.getSuppliers()
        this.getReceivers()
        this.getProductTypes()
        this.getCurrency()
    },
    computed: {
        subtotal_price: function() {
            var inventories = this.inventories
            var subTotals = []
            inventories.map((inventory, i) => {
                var total = (Number(inventory.inventory_quantity) * Number(inventory.inventory_unit_amount)).toFixed(2)
                this.inventories[i].inventory_total_price = total
                subTotals.push(total)
            })

            return subTotals
        },
        subTotal: function() {
            var inventories = this.inventories
            var subTotals = 0
            inventories.map(inventory => {
                var total = Number(inventory.inventory_quantity) * Number(inventory.inventory_unit_amount)
                subTotals += total
            })

            return subTotals.toFixed(2)
        },
        total: function() {
            var inventories = this.inventories
            var subTotals = 0
            inventories.map(inventory => {
                var total = Number(inventory.inventory_quantity) * Number(inventory.inventory_unit_amount)
                subTotals += total
            })
            var grand_total = Number(this.additional_cost) + Number(subTotals)
            return grand_total.toFixed(2)
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
        showSupplier: function() {
            this.$refs.addsupplier.trigger()
        },
        saveSupplier: function(data) {
            this.$refs.addsupplierpage.createSupplier()
        },
        saveReceiver: function(data) {
            this.$refs.addreceiverpage.createReceiver()
        },
        getSuppliers: function() {
            axios.get("/admin/supplier/get-suppliers").then(result => {
                this.suppliers = result.data.suppliers
            })
        },
        getProductTypes: function() {
            axios.get("/admin/products/get-all-product-types").then(result => {
                this.product_types = result.data.product_types
            })
        },
        getReceivers: function() {
            axios.get("/admin/receiver/get-receivers").then(result => {
                this.receivers = result.data.receivers
            })
        },
        showReceiver: function() {
            this.$refs.addreceiver.trigger()
        },
        moreItems: function() {
            this.inventories = [
                ...this.inventories,
                {
                    inventory_type: "",
                    inventory_description: "",
                    inventory_quantity: 0,
                    inventory_unit_amount: 0,
                    inventory_total_price: ""
                }
            ]
        },
        removeInventory: function(index) {
            if (index != 0) {
                this.inventories.splice(index, 1)
            }
        },
        createPurchaseOrder: function() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                var params = {
                    supplier: this.selected_supplier,
                    receiver: this.selected_receiver,
                    inventories: this.inventories,
                    total: this.total,
                    additional_cost: this.additional_cost
                }
                swal.queue([
                    {
                        title: "Create Transaction",
                        text: "Are you sure? All details is correct?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonColor: "#42d1f5",
                        confirmButtonText: "Yes, create now",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/admin/purchasing/create", params).then(response => {
                                swal.fire({
                                    title: response.data.heading,
                                    text: response.data.message,
                                    icon: "success",
                                    showCancelButton: true,
                                    cancelButtonColor: "#03a1fc",
                                    confirmButtonText: "View Transaction",
                                    cancelButtonText: "Not now"
                                }).then(view => {
                                    if (view.isConfirmed) {
                                        window.location.href = "/admin/purchasing/purchase-order/" + response.data.transaction_id
                                    }
                                })
                            })
                        }
                    }
                ])
            }
        },
        getCurrency: function() {
            axios.get("/admin/currency/get-currency").then(result => {
                this.currency = result.data
            })
        }
    },
    validations: {
        selected_supplier: {
            required
        },
        selected_receiver: {
            required
        },
        additional_cost: {
            decimal
        },
        inventories: {
            $each: {
                inventory_type: {
                    required
                },
                inventory_description: {
                    required
                },
                inventory_quantity: {
                    required,
                    greater
                },
                inventory_unit_amount: {
                    required,
                    greater
                }
            }
        }
    }
}
</script>

<style></style>
