<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Purchase Order</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        Print Details
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
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
            </div>
            <div class="col-md-6">
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
            </div>
            <div v-if="!willReceive" class="col-md-12 mt-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
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
                            <td>{{ inventory.product_types.product_name }}</td>
                            <td>{{ inventory.purchasing_description }}</td>
                            <td>{{ numberWithCommas(inventory.quantity) }}</td>
                            <td>{{ numberWithCommas(inventory.total_received) }}</td>
                            <td>{{ numberWithCommas(inventory.total_items_to_receive) }}</td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(inventory.inventory_total_price.toFixed(2)) }}</td>
                            <td>
                                <button @click="receive(inventory)" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"></i> Receive Items</button>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5">
                                Additional Cost
                            </td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order_details.additional_cost.toFixed(2)) }}</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5">
                                <b>Total Price</b>
                            </td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order_details.total_price.toFixed(2)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div v-else class="col-md-12">
                <button @click="willReceive = false" class="btn btn-sm btn-primary">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <br />
                <table class="table table-bordered table-md mt-3">
                    <thead>
                        <th>Product Type</th>
                        <th>Item(s) Decription</th>
                        <th>Quantity</th>
                        <th>Total Received</th>
                        <th>Total Item(s) To Receive</th>
                    </thead>
                    <tbody>
                        <td>{{ forReceive.product_types.product_name }}</td>
                        <td>{{ forReceive.purchasing_description }}</td>
                        <td>{{ numberWithCommas(forReceive.quantity) }}</td>
                        <td>{{ numberWithCommas(forReceive.total_received) }}</td>
                        <td>{{ numberWithCommas(forReceive.total_items_to_receive) }}</td>
                    </tbody>
                </table>
                <h4 class="mt-3 m-3">
                    Import Item(s)
                </h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="home" aria-selected="true">Import Manually</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#file" role="tab" aria-controls="profile" aria-selected="false">Import CSV / XLSX</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row ml-3 mr-3 mb-5 mt-3">
                            <div class="col-md-12">
                                <import-manual :basis="'purchasing'" @update_purchasing="updatePurchasing" :transaction_id="purchase_order_details.id" :purchasing_type_id="forReceive.id" :product_type="forReceive.product_types"></import-manual>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="file" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row ml-3 mr-3 mb-3 mt-3">
                            <div class="col-md-12">
                                <import-file :basis="'purchasing'" :transaction_id="purchase_order_details.id" :purchasing_type_id="forReceive.id" :product_type="forReceive.product_types"></import-file>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment"
export default {
    props: ["purchase_order"],
    created() {
        this.getCurrency()
        this.purchase_order_details = this.purchase_order
    },
    data() {
        return {
            currency: {},
            forReceive: {},
            willReceive: false,
            purchase_order_details: {}
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
small {
    font-size: 14px;
}

.fa-arrow-left {
    font-size: 15px;
}
</style>
