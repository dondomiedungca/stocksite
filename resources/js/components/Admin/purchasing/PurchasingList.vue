<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Purchasing List</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        Print List
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <small v-if="Object.keys(purchaseOrders).length">Showing {{ purchaseOrders.from == null ? 0 : purchaseOrders.from }} to {{ purchaseOrders.to == null ? 0 : purchaseOrders.to }} of {{ numberWithCommas(purchaseOrders.total) }} purchase order(s)</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Trash</th>
                                <th>Purchase Order #</th>
                                <th>Transaction Status</th>
                                <th>Delivery Status</th>
                                <th>Item(s) Status</th>
                                <th>Supplier</th>
                                <th>Receiver</th>
                                <th>No. Of Inventory</th>
                                <th>Total Cost</th>
                                <th>Created By</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody v-if="Object.keys(purchaseOrders).length">
                            <tr v-if="!purchaseOrders.data.length">
                                <td colspan="12" align="center">No Results Found</td>
                            </tr>
                            <tr v-else v-for="(purchase_order, i) in purchaseOrders.data" v-bind:key="i">
                                <td align="center">#{{ i + 1 }}</td>
                                <td align="center">
                                    <button @click="removePurchasing(purchase_order.id)" class="btn btn-danger btn-sm">
                                        <i class="lni lni-trash"></i>
                                    </button>
                                </td>
                                <td align="center">
                                    <a :href="'/admin/purchasing/purchase-order/' + purchase_order.id">{{ purchase_order.transaction_code }}</a>
                                </td>
                                <td align="center">{{ purchase_order.transaction_status.transaction_status_name }}</td>
                                <td align="center">{{ purchase_order.delivery_status.name }}</td>
                                <td align="center">{{ purchase_order.item_status.name }}</td>
                                <td align="center">{{ purchase_order.supplier.supplier_name }}</td>
                                <td align="center">{{ purchase_order.purchase_orders.receiver.receiver_first_name }} {{ purchase_order.purchase_orders.receiver.receiver_last_name }}</td>
                                <td align="center">{{ purchase_order.purchase_orders.purchase_order_types.length }}</td>
                                <td align="center"><span v-html="currency.symbol"></span> {{ numberWithCommas(purchase_order.total_price.toFixed(2)) }}</td>
                                <td align="center">{{ purchase_order.purchase_orders.user.first_name }} {{ purchase_order.purchase_orders.user.last_name }}</td>
                                <td align="center">{{ date_format(purchase_order.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <paginate :data="purchaseOrders" :limit="3" v-on:pagination-change-page="getList"></paginate>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment"
import Pagination from "./../../Reusable/Pagination"

export default {
    components: {
        paginate: Pagination
    },
    data() {
        return {
            purchaseOrders: {},
            currency: {}
        }
    },
    created() {
        this.getList()
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
        getList: function(page) {
            if (typeof page === "undefined") {
                page = 1
            }
            var url = "/admin/purchasing/purchasing-all-list"
            axios.get(url + "?page=" + page).then(response => {
                this.purchaseOrders = response.data
            })
        },
        getCurrency: function() {
            axios.get("/admin/currency/get-currency").then(result => {
                this.currency = result.data
            })
        },
        removePurchasing: function(transaction_id) {
            var self = this
            swal.queue([
                {
                    title: "Remove Purchasing",
                    text: "All inventories under this purchasing will remove, proceed?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#42d1f5",
                    confirmButtonText: "Yes, remove now",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.get("/admin/purchasing/remove-purchasing/" + transaction_id).then(response => {
                            swal.fire({
                                title: response.data.heading,
                                text: response.data.message,
                                icon: response.data.isSuccess ? "success" : "error"
                            })
                            self.getList(self.purchaseOrders.current_page)
                        })
                    }
                }
            ])
        }
    }
}
</script>

<style>
table.table-bordered > thead > th {
    vertical-align: middle;
}
table.table-bordered > tbody > tr > td {
    vertical-align: middle;
}
</style>
