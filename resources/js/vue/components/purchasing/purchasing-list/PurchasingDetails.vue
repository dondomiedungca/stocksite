<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row v-if="Object.keys(purchase_order_details).length">
                    <v-col lg="6" md="6" sm="12" xs="12">
                        <h4>
                            <b>{{ purchase_order_details.supplier.supplier_name }}</b>
                        </h4>
                        <small>
                            <i>{{ purchase_order_details.supplier.address.address_type.address_type_name }} - {{ purchase_order_details.supplier.address.address_no_or_house_building_no }} {{ purchase_order_details.supplier.address.address_st }} {{ purchase_order_details.supplier.address.address_city }} {{ purchase_order_details.supplier.address.address_state }} {{ purchase_order_details.supplier.address.address_country }} {{ purchase_order_details.supplier.address.address_post_code }}</i>
                        </small>
                        <br />
                        <small>{{ purchase_order_details.supplier.supplier_email }}</small>
                        <br />
                        <small>{{ purchase_order_details.supplier.supplier_phone_number }}</small>
                    </v-col>
                    <v-col lg="6" md="6" sm="12" xs="12">
                        <h4>Purchase Order</h4>
                        <small
                            ><b>{{ purchase_order_details.transaction_code }}</b></small
                        >
                        <br />
                        <small>{{ date_format(purchase_order_details.created_at) }}</small>
                        <br /><br />
                        <h6>
                            Payment Status: <span :class="'badge ' + payment_status(purchase_order_details)">{{ purchase_order_details.payment_status.payment_status_name }}</span>
                        </h6>
                        <h6>
                            Delivery Status: <span :class="'badge ' + delivery_status(purchase_order_details)">{{ purchase_order_details.delivery_status.name }}</span>
                        </h6>
                        <h6>
                            Item(s) Completion Status: <span :class="'badge ' + item_status(purchase_order_details)">{{ purchase_order_details.item_status.name }}</span>
                        </h6>
                    </v-col>
                    <v-col v-if="!willReceive" lg="12" md="12" sm="12" xs="12">
                        <div style="overflow-x:auto;">
                            <table class="table-simple">
                                <tbody>
                                    <tr>
                                        <td rowspan="4">
                                            Receiver Details
                                        </td>
                                        <td colspan="7">{{ purchase_order_details.purchase_orders.receiver.receiver_first_name }} {{ purchase_order_details.purchase_orders.receiver.receiver_last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">{{ purchase_order_details.purchase_orders.receiver.receiver_email }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">{{ purchase_order_details.purchase_orders.receiver.receiver_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <i>{{ purchase_order_details.purchase_orders.receiver.address.address_type.address_type_name }} - {{ purchase_order_details.purchase_orders.receiver.address.address_no_or_house_building_no }} {{ purchase_order_details.purchase_orders.receiver.address.address_st }} {{ purchase_order_details.purchase_orders.receiver.address.address_city }} {{ purchase_order_details.purchase_orders.receiver.address.address_state }} {{ purchase_order_details.purchase_orders.receiver.address.address_country }} {{ purchase_order_details.purchase_orders.receiver.address.address_post_code }}</i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            Inventories
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Product Type</th>
                                        <th>Product Description</th>
                                        <th>Quantity</th>
                                        <th>Total Received</th>
                                        <th>Total Item(s) To Receive</th>
                                        <th>Total Price</th>
                                        <th></th>
                                    </tr>
                                    <tr v-for="(inventory, i) in purchase_order_details.purchase_orders.purchase_order_types" v-bind:key="i">
                                        <td align="center">{{ inventory.product_types.product_name }}</td>
                                        <td align="center">{{ inventory.purchasing_description }}</td>
                                        <td align="center">{{ numberWithCommas(inventory.quantity) }}</td>
                                        <td align="center">{{ numberWithCommas(inventory.total_received) }}</td>
                                        <td align="center">{{ numberWithCommas(inventory.total_items_to_receive) }}</td>
                                        <td align="center"><span v-html="currency.symbol"></span> {{ numberWithCommas(inventory.inventory_total_price.toFixed(2)) }}</td>
                                        <td align="center">
                                            <!-- <button :disabled="inventory.total_items_to_receive == 0 ? true : false" @click="receive(inventory)" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"></i> Receive Items</button> -->
                                            <v-btn @click="receive(inventory)" :disabled="inventory.total_items_to_receive == 0 ? true : false" small color="primary"> <v-icon>mdi-arrow-down-thick</v-icon> Receive Item(s) </v-btn>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" colspan="5">
                                            Additional Cost
                                        </td>
                                        <td align="center"><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order_details.additional_cost.toFixed(2)) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td align="right" colspan="5">
                                            <b>Total Price</b>
                                        </td>
                                        <td align="center"><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order_details.total_price.toFixed(2)) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <asker icon_header="mdi-checkbox-multiple-marked" tooltip_message="Mark As Paid" title="Mark this Purchase Order as Paid?" color="teal accent-2" classes="mt-2 ml-3">
                            <template v-slot:togglerIcon>
                                <v-icon color="#404040">
                                    mdi-checkbox-multiple-marked
                                </v-icon>
                            </template>
                        </asker>
                        <asker icon_header="mdi-information" tooltip_message="Mark As Cancelled" title="Mark this Purchase Order as Cancelled?" color="orange lighten-2" classes="mt-2 ml-3">
                            <template v-slot:togglerIcon>
                                <v-icon color="#404040">
                                    mdi-file-cancel
                                </v-icon>
                            </template>
                        </asker>
                    </v-col>
                    <v-col v-else lg="12" md="12" sm="12" xs="12">
                        <v-btn class="mb-4" @click="willReceive = false" small color="primary"> <v-icon>mdi-arrow-left-thick</v-icon> Back </v-btn>
                        <div style="overflow-x:auto;">
                            <table class="table-simple">
                                <thead>
                                    <tr>
                                        <th align="center">Product Type</th>
                                        <th align="center">Item(s) Decription</th>
                                        <th align="center">Quantity</th>
                                        <th align="center">Total Received</th>
                                        <th align="center">Total Item(s) To Receive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="center">{{ forReceive.product_types.product_name }}</td>
                                        <td align="center">{{ forReceive.purchasing_description }}</td>
                                        <td align="center">{{ numberWithCommas(forReceive.quantity) }}</td>
                                        <td align="center">{{ numberWithCommas(forReceive.total_received) }}</td>
                                        <td align="center">{{ numberWithCommas(forReceive.total_items_to_receive) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <v-tabs class="mt-5" color="#000" align-with-title fixed-tabs slider-size="3" slider-color="blue accent-3">
                            <v-tab href="#hello">
                                Import Manually
                            </v-tab>
                            <v-tab href="#world">
                                Import Via CSV / XLSX
                            </v-tab>
                            <v-tab-item class="mt-5" id="hello">
                                <v-card color="basil" flat>
                                    <import-manually :basis="'purchasing'" @update_purchasing="updatePurchasing" :transaction_id="purchase_order_details.id" :purchasing_type_id="forReceive.id" :product_type="forReceive.product_types"></import-manually>
                                </v-card>
                            </v-tab-item>
                            <v-tab-item class="mt-5" id="world">
                                <v-card color="basil" flat>
                                    <import-via-file :basis="'purchasing'" :transaction_id="purchase_order_details.id" :purchasing_type_id="forReceive.id" :product_type="forReceive.product_types"></import-via-file>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import moment from "moment"
import ImportManually from "./../../product/import-product/ImportManually.vue"
import ImportViaFile from "./../../product/import-product/ImportViaFile.vue"
export default {
    props: ["purchase_order_details"],
    components: {
        ImportManually,
        ImportViaFile
    },
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
                    text: "Purchasing List",
                    disabled: false,
                    exact: true,
                    to: "/admin/purchasing/purchasing-list"
                },
                {
                    text: "Purchasing Details",
                    disabled: false
                }
            ],
            currency: {},
            forReceive: {},
            willReceive: false
        }
    },
    created() {
        this.getCurrency()
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
        getCurrency: function() {
            axios.get("/admin/currency/get-currency").then(result => {
                this.currency = result.data
            })
        },
        payment_status: function(purchase_order) {
            if (purchase_order.payment_status_id == 3) {
                return "badge-success"
            } else if (purchase_order.payment_status_id == 2) {
                return "badge-primary"
            } else {
                return "badge-secondary"
            }
        },
        delivery_status: function(purchase_order) {
            if (purchase_order.delivery_status_id == 4) {
                return "badge-success"
            } else if (purchase_order.delivery_status_id == 3) {
                return "badge-primary"
            } else if (purchase_order.delivery_status_id == 2) {
                return "badge-info"
            } else {
                return "badge-secondary"
            }
        },
        receive: function(inventory) {
            this.forReceive = inventory
            this.willReceive = true
        },
        item_status: function(purchase_order) {
            if (purchase_order.item_status_id == 3) {
                return "badge-success"
            } else if (purchase_order.item_status_id == 2) {
                return "badge-primary"
            } else {
                return "badge-secondary"
            }
        },
        updatePurchasing: function() {
            axios.get("/admin/purchasing/purchase-order-data/" + this.purchase_order_details.id).then(res => {
                this.purchase_order_details = res.data.purchase_order
                this.forReceive.total_items_to_receive--
                this.forReceive.total_received++
            })
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
.table-simple > tbody > tr > td,
.table-simple > tbody > tr > th {
    padding: 5px 10px;
    border: 1px solid #404040;
    vertical-align: middle;
}

.tables-margin {
    margin-top: -15px;
}
</style>
