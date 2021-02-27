<template>
    <v-dialog v-model="isDialogOpen" persistent max-width="850px">
        <v-card style="width: 100%;">
            <v-card-title>Advance Search</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field dense label="Transaction Code" outlined v-model="transaction_code" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field dense label="Supplier" outlined v-model="supplier_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(total_price[0].toFixed(2)) }} - <span v-html="currency.symbol"></span> {{ numberWithCommas(total_price[1].toFixed(2)) }}</small>
                        <v-range-slider label="Total Price Range" v-model="total_price" step="10" max="100000"></v-range-slider>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="transaction_statuses" v-model="transaction_status" label="Transaction Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="payment_statuses" v-model="payment_status" label="Payment Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="delivery_statuses" v-model="delivery_status" label="Delivery Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="item_statuses" v-model="transaction_status" label="Item Status" dense></v-select>
                    </v-col>
                </v-row>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn small color="secondary" @click="close">
                    close
                </v-btn>
                <v-btn small color="primary" @click="reset">Reset</v-btn>
                <v-btn small color="primary" @click="search">Search</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["isOpen", "currency", "delivery_statuses", "item_statuses", "payment_statuses", "transaction_statuses"],
    data() {
        return {
            transaction_code: "",
            supplier_name: "",
            total_price: [0, 10000],
            payment_status: 0,
            item_status: 0,
            delivery_status: 0,
            transaction_status: 0,
            isDialogOpen: false
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
        close() {
            this.$emit("close")
        },
        search() {
            var fields = {
                transaction_code: this.transaction_code,
                supplier_name: this.supplier_name,
                total_price: this.total_price,
                payment_status: this.payment_status,
                item_status: this.item_status,
                delivery_status: this.delivery_status,
                transaction_status: this.transaction_status
            }
            this.$emit("search", fields)
            this.close()
        },
        reset() {}
    },
    watch: {
        isOpen(data) {
            this.isDialogOpen = data
        }
    }
}
</script>

<style></style>
