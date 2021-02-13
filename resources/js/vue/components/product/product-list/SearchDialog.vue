<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card style="width: 100%;">
            <v-card-title>Advance Search</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Fields</h4>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field dense label="Stock Number" outlined v-model="search_fields.stock_number" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="status_selections" v-model="search_fields.inventory_status_id" label="Inventory Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="cosmetic_selections" v-model="search_fields.inventory_cosmetic_id" label="Inventory Cosmetic" dense></v-select>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(search_fields.origin_price[0].toFixed(2)) }} - <span v-html="currency.symbol"></span> {{ numberWithCommas(search_fields.origin_price[1].toFixed(2)) }}</small>
                        <v-range-slider label="Origin Price Range" v-model="search_fields.origin_price" step="10" max="100000"></v-range-slider>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <small><span v-html="currency.symbol"></span> {{ numberWithCommas(search_fields.selling_price[0].toFixed(2)) }} - <span v-html="currency.symbol"></span> {{ numberWithCommas(search_fields.selling_price[1].toFixed(2)) }}</small>
                        <v-range-slider label="Selling Price Range" v-model="search_fields.selling_price" step="10" max="100000"></v-range-slider>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Specification Fields</h4>
                    </v-col>
                    <v-col v-for="(column, i) in product_type.product_attributes" v-bind:key="i" lg="4" md="4" sm="6" xs="12">
                        <div v-if="column.product_column_input_type == 'INPUT'">
                            <div v-if="column.product_column_data_type == 'STRING'">
                                <v-text-field type="text" outlined v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" dense></v-text-field>
                            </div>
                            <div v-else>
                                <v-text-field style="cursor: pointer;" @click="setPropertyNameForDate(column.product_column_name)" v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" prepend-icon="mdi-calendar" outlined dense readonly></v-text-field>
                            </div>
                        </div>
                        <div v-else>
                            <v-select outlined :items="customSelections(column.column_selections)" v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" dense></v-select>
                        </div>
                    </v-col>
                    <v-dialog v-model="dateDialog" ref="dialog" :return-value.sync="search_fields.details[date_modal_property_name]" persistent width="290px">
                        <v-date-picker v-model="search_fields.details[date_modal_property_name]" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text @click="dateDialog = false" color="secondary">
                                Cancel
                            </v-btn>
                            <v-btn text color="primary" @click="$refs.dialog.save(search_fields.details[date_modal_property_name])">
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-dialog>
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
    props: ["isOpen", "currency", "product_type", "inventory_cosmetic_selections", "inventory_status_selections"],
    data() {
        return {
            search_fields: {
                stock_number: "",
                inventory_status_id: 0,
                inventory_cosmetic_id: 0,
                origin_price: [0, 10000],
                selling_price: [0, 10000],
                discount_percentage: "",
                details: {}
            },
            status_selections: [],
            cosmetic_selections: [],
            dateDialog: false,
            date_modal_property_name: ""
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
            this.$emit("search", this.search_fields)
            this.close()
        },
        reset() {
            var reset_details = {}
            this.product_type.product_attributes.map(column => {
                reset_details = {
                    ...reset_details,
                    [column.product_column_name]: ""
                }
            })
            this.search_fields = {
                stock_number: "",
                inventory_status_id: 0,
                inventory_cosmetic_id: 0,
                origin_price: [0, 10000],
                selling_price: [0, 10000],
                discount_percentage: "",
                details: reset_details
            }
        },
        customSelections(data) {
            return data.map(selection => selection.selection_name)
        }
    },
    watch: {
        product_type(data) {
            this.search_fields.details = {}
            if (Object.keys(data).length) {
                data.product_attributes.map(column => {
                    this.search_fields.details = {
                        ...this.search_fields.details,
                        [column.product_column_name]: ""
                    }
                })
            }
        },
        inventory_cosmetic_selections(data) {
            this.cosmetic_selections = [...data]
            this.cosmetic_selections.push({
                text: "All",
                value: 0
            })
        },
        inventory_status_selections(data) {
            this.status_selections = [...data]
            this.status_selections.push({
                text: "All",
                value: 0
            })
        }
    }
}
</script>

<style></style>
