<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <span v-if="Object.keys(purchaseOrders).length">
                            <p v-if="purchaseOrders.total">{{ purchaseOrders.from }} to {{ purchaseOrders.to }} of {{ purchaseOrders.total }} purchasing</p>
                        </span>
                        <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="purchaseOrders.total" :headers="headers" :items="purchaseOrders.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                            <template v-slot:item="{ index, item }">
                                <tr>
                                    <td align="center">
                                        <small># {{ index + 1 }}</small>
                                    </td>
                                    <td align="center">
                                        <small
                                            ><v-btn @click="removePurchasing(item.id)" icon>
                                                <v-icon>mdi-trash-can</v-icon>
                                            </v-btn></small
                                        >
                                    </td>
                                    <td align="center">
                                        <small>
                                            <router-link :to="'/admin/purchasing/purchase-order/' + item.id">{{ item.transaction_code }}</router-link>
                                        </small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.transaction_status.transaction_status_name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.delivery_status.name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.item_status.name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.supplier.supplier_name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.purchase_orders.receiver.receiver_first_name }} {{ item.purchase_orders.receiver.receiver_last_name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.purchase_orders.purchase_order_types.length }}</small>
                                    </td>
                                    <td align="center">
                                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(item.total_price.toFixed(2)) }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ item.purchase_orders.user.first_name }} {{ item.purchase_orders.user.last_name }}</small>
                                    </td>
                                    <td align="center">
                                        <small>{{ date_format(item.created_at) }}</small>
                                    </td>
                                </tr>
                            </template>
                        </v-data-table>
                        <v-layout class="d-flex justify-space-between align-center pt-3">
                            <v-pagination :total-visible="7" v-model="page" :length="pageCount"></v-pagination>
                        </v-layout>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import moment from "moment"
export default {
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
            itemsPerPage: 10,
            page: 1,
            pageCount: 0,
            loading: true,
            options: {},
            //
            purchaseOrders: {},
            currency: {},
            headers: [
                {
                    text: "#",
                    align: "center",
                    sortable: false,
                    class: "primary white--text"
                },
                {
                    text: "Trash",
                    align: "center",
                    sortable: false,
                    class: "primary white--text"
                },
                {
                    text: "Purchase Order #",
                    align: "center",
                    sortable: false,
                    class: "primary white--text"
                },
                { text: "Transaction Status", align: "center", sortable: false, class: "primary white--text" },
                { text: "Delivery Status", align: "center", sortable: false, class: "primary white--text" },
                { text: "Item(s) Status", align: "center", sortable: false, class: "primary white--text" },
                { text: "Supplier", align: "center", sortable: false, class: "primary white--text" },
                { text: "Receiver", align: "center", sortable: false, class: "primary white--text" },
                { text: "No. of Inventory", align: "center", sortable: false, class: "primary white--text" },
                { text: "Total Cost", align: "center", sortable: false, class: "primary white--text" },
                { text: "Created By", align: "center", sortable: false, class: "primary white--text" },
                { text: "Date Created", align: "center", sortable: false, class: "primary white--text" }
            ]
        }
    },
    watch: {
        options() {
            this.getList()
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
        getList: function() {
            this.loading = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.options

            var url = "/admin/purchasing/purchasing-all-list"
            axios
                .get(url + "?page=" + page)
                .then(response => {
                    this.purchaseOrders = response.data
                })
                .then(() => (this.loading = false))
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

<style></style>
