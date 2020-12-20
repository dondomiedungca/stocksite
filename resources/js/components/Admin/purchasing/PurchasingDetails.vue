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
                    <b>{{ purchase_order.supplier.supplier_name }}</b>
                </h4>
                <small>
                    <i>{{ purchase_order.supplier.address.address_type.address_type_name }} - {{ purchase_order.supplier.address.address_no_or_house_building_no }} {{ purchase_order.supplier.address.address_st }} {{ purchase_order.supplier.address.address_city }} {{ purchase_order.supplier.address.address_state }} {{ purchase_order.supplier.address.address_country }} {{ purchase_order.supplier.address.address_post_code }}</i>
                </small>
                <br />
                <small>{{ purchase_order.supplier.supplier_email }}</small>
                <br />
                <small>{{ purchase_order.supplier.supplier_phone_number }}</small>
            </div>
            <div class="col-md-6">
                <h4>Purchase Order</h4>
                <small
                    ><b>{{ purchase_order.transaction_code }}</b></small
                >
                <br />
                <small>{{ date_format(purchase_order.created_at) }}</small>
            </div>
            <div class="col-md-12 mt-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <tr>
                            <td rowspan="4">
                                Receiver Details
                            </td>
                            <td colspan="7">{{ purchase_order.purchase_orders.receiver.receiver_first_name }} {{ purchase_order.purchase_orders.receiver.receiver_last_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">{{ purchase_order.purchase_orders.receiver.receiver_email }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">{{ purchase_order.purchase_orders.receiver.receiver_phone }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <i>{{ purchase_order.purchase_orders.receiver.address.address_type.address_type_name }} - {{ purchase_order.purchase_orders.receiver.address.address_no_or_house_building_no }} {{ purchase_order.purchase_orders.receiver.address.address_st }} {{ purchase_order.purchase_orders.receiver.address.address_city }} {{ purchase_order.purchase_orders.receiver.address.address_state }} {{ purchase_order.purchase_orders.receiver.address.address_country }} {{ purchase_order.purchase_orders.receiver.address.address_post_code }}</i>
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
                            <th>Item(s) Received</th>
                            <th>Item(s) To Receive</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th></th>
                        </tr>
                        <tr v-for="(inventory, i) in purchase_order.purchase_orders.purchase_order_types" v-bind:key="i">
                            <td>{{ inventory.product_types.product_name }}</td>
                            <td>{{ inventory.purchasing_description }}</td>
                            <td>{{ inventory.total_received }}</td>
                            <td>{{ inventory.total_items_to_receive }}</td>
                            <td>{{ inventory.quantity }}</td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(inventory.inventory_total_price.toFixed(2)) }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"></i> Receive Items</button>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5">
                                Additional Cost
                            </td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order.additional_cost.toFixed(2)) }}</td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5">
                                <b>Total Price</b>
                            </td>
                            <td><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order.total_price.toFixed(2)) }}</td>
                        </tr>
                    </table>
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
    },
    data() {
        return {
            currency: {}
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
        }
    }
}
</script>

<style>
small {
    font-size: 14px;
}
</style>
