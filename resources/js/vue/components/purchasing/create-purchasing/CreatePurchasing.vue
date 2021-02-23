<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="6" md="6">
                        <v-row>
                            <v-col lg="6" md="6" sm="6" xs="12">
                                <v-select :error-messages="supplier_errors" :items="supplier_selections" label="Select Supplier" v-model="selected_supplier" outlined dense></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="tables-margin" lg="12" md="12" sm="12" xs="12">
                                <v-simple-table dense class="table-simple">
                                    <template v-slot:default>
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
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col lg="6" md="6">
                        <v-row>
                            <v-col lg="6" md="6" sm="6" xs="12">
                                <v-select :error-messages="receiver_errors" :items="receiver_selections" label="Select Receiver" v-model="selected_receiver" outlined dense></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="tables-margin" lg="12" md="12" sm="12" xs="12">
                                <v-simple-table dense class="table-simple">
                                    <template v-slot:default>
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
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row class="mt-5">
                    <v-col class="d-flex justify-space-between">
                        <v-btn @click="moreItems" color="primary"> <v-icon>mdi-plus</v-icon> Add Inventory </v-btn>
                        <v-btn @click="createPurchaseOrder" color="primary"> <v-icon>mdi-check</v-icon> Create Purchasing </v-btn>
                    </v-col>
                    <v-col class="mb-5" lg="12" md="12" sm="12" xs="12">
                        <div style="overflow-x:auto;">
                            <table class="table-simple">
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
                                            <v-btn :disabled="!i" @click="removeInventory(i)" icon color="red darken-1">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </td>
                                        <td>
                                            <v-select class="mt-5" :error-messages="customErrors(i, 'inventory_type')" :items="product_types_selections" v-model="inventories[i].inventory_type" dense outlined label="Inventory Type"></v-select>
                                        </td>
                                        <td>
                                            <v-text-field class="mt-5" :error-messages="customErrors(i, 'inventory_description')" type="text" @blur="$v.inventories.$each[i.toString()].inventory_description.$touch()" outlined v-model="inventories[i].inventory_description" label="Inventory Description" dense></v-text-field>
                                        </td>
                                        <td>
                                            <v-text-field class="mt-5" :error-messages="customErrors(i, 'inventory_quantity')" type="text" @blur="$v.inventories.$each[i.toString()].inventory_quantity.$touch()" outlined v-model="inventories[i].inventory_quantity" label="Inventory Quantity" dense></v-text-field>
                                        </td>
                                        <td>
                                            <v-text-field class="mt-5" :error-messages="customErrors(i, 'inventory_unit_amount')" type="text" @blur="$v.inventories.$each[i.toString()].inventory_unit_amount.$touch()" outlined v-model="inventories[i].inventory_unit_amount" label="Inventory Unit Amount" dense></v-text-field>
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
                                            <!-- <input type="number" :class="{ 'is-invalid': $v.additional_cost.$error }" name v-model="$v.additional_cost.$model" @change="additional_cost = $event.target.value" class="form-control form-control-sm" /> -->
                                            <v-text-field class="mt-5" :error-messages="additional_cost_errors" type="text" @blur="$v.additional_cost.$touch()" outlined v-model="additional_cost" label="Additional Cost" dense></v-text-field>
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
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import { validationMixin } from "vuelidate"
import { required, numeric, decimal } from "vuelidate/lib/validators"
const greater = value => value > 0

export default {
    mixins: [validationMixin],
    data() {
        return {
            items: [
                {
                    text: "Purchasing",
                    disabled: false,
                    exact: true,
                    to: "/admin/purchasing"
                },
                {
                    text: "Create Purchasing",
                    disabled: true,
                    to: "add-product-type"
                }
            ],
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
                    inventory_quantity: 1,
                    inventory_unit_amount: 1,
                    inventory_total_price: ""
                }
            ],
            supplier_selections: [],
            receiver_selections: [],
            product_types_selections: []
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
        },
        additional_cost_errors() {
            const errors = []
            if (!this.$v.additional_cost.$dirty) return errors
            !this.$v.additional_cost.numeric && errors.push("Only digit is allowed")
            return errors
        },
        supplier_errors() {
            const errors = []
            if (!this.$v.selected_supplier.$dirty) return errors
            !this.$v.selected_supplier.required && errors.push("This field is required")
            return errors
        },
        receiver_errors() {
            const errors = []
            if (!this.$v.selected_receiver.$dirty) return errors
            !this.$v.selected_receiver.required && errors.push("This field is required")
            return errors
        }
    },
    methods: {
        customErrors: function(index, property_name) {
            var errors = []
            if (!this.$v.inventories.$each[index.toString()][property_name].$dirty) return (errors = [])
            !this.$v.inventories.$each[index.toString()][property_name].required && errors.push(`${property_name} field is required`)
            if (property_name == "inventory_unit_amount" || property_name == "inventory_quantity") {
                !this.$v.inventories.$each[index.toString()][property_name].greater && errors.push(`${property_name} field required value is more than 0`)
            }

            return errors
        },
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
                this.supplier_selections = result.data.suppliers.map(supplier => {
                    return {
                        text: supplier.supplier_name,
                        value: supplier
                    }
                })
            })
        },
        getProductTypes: function() {
            axios.get("/admin/products/get-all-product-types").then(result => {
                this.product_types = result.data.product_types
                this.product_types_selections = result.data.product_types.map(productType => {
                    return {
                        text: productType.product_name,
                        value: productType.id
                    }
                })
            })
        },
        getReceivers: function() {
            axios.get("/admin/receiver/get-receivers").then(result => {
                this.receivers = result.data.receivers
                this.receiver_selections = result.data.receivers.map(receiver => {
                    return {
                        text: receiver.receiver_last_name + ", " + receiver.receiver_first_name,
                        value: receiver
                    }
                })
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
                    inventory_quantity: 1,
                    inventory_unit_amount: 1,
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
            numeric
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

<style>
.table-simple {
    width: 100%;
    min-width: 300px;
    border-collapse: collapse;
    border: 1px solid #404040;
}
.table-simple > thead {
    background: transparent;
    color: #404040;
}
.table-simple > thead > tr > th {
    padding: 5px 10px;
    border: 1px solid #404040;
    vertical-align: middle;
}
.table-simple > tbody > tr > td {
    padding: 5px 10px;
    border: 1px solid #404040;
    vertical-align: middle;
}

.tables-margin {
    margin-top: -15px;
}
</style>
