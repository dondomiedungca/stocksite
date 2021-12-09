<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <template v-slot:activator="{ on, attrs }">
            <v-btn fab v-bind="attrs" v-on="on" color="primary">
                <v-icon>
                    mdi-magnify
                </v-icon>
            </v-btn>
        </template>
        <v-card style="width: 100%;">
            <v-card-title>Advance Search</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Product Group</h4>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <Select :items="product_types" v-model="selected_product_type" label="Product Type" />
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Fields</h4>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <TextField label="Stock Number" v-model="search_fields.stock_number" />
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <Select :items="status_selections" v-model="search_fields.inventory_status_id" label="Inventory Status" />
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <Select :items="cosmetic_selections" v-model="search_fields.inventory_cosmetic_id" label="Inventory Cosmetic" />
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <RangeSlider v-model="search_fields.origin_price" label="Origin Price Range">
                            <template #digitPrepend>
                                <span v-html="currency.symbol"></span>
                            </template>
                            <template #digitAppend>
                                <span v-html="currency.symbol"></span>
                            </template>
                        </RangeSlider>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <RangeSlider v-model="search_fields.selling_price" label="Selling Price Range">
                            <template #digitPrepend>
                                <span v-html="currency.symbol"></span>
                            </template>
                            <template #digitAppend>
                                <span v-html="currency.symbol"></span>
                            </template>
                        </RangeSlider>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Specification Fields</h4>
                    </v-col>
                    <v-col v-for="(column, i) in product_type.product_attributes" v-bind:key="i" lg="4" md="4" sm="6" xs="12">
                        <div v-if="column.product_column_input_type == 'INPUT'">
                            <div v-if="column.product_column_data_type == 'STRING'">
                                <TextField v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" />
                            </div>
                            <div v-else-if="column.product_column_data_type == 'INTEGER'">
                                <NumberField v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" />
                            </div>
                            <div v-else>
                                <DatePicker :range="true" v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" />
                            </div>
                        </div>
                        <div v-else>
                            <Select :items="customSelections(column.column_selections)" v-model="search_fields.details[column.product_column_name]" :label="column.product_column_name" />
                        </div>
                    </v-col>
                </v-row>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn small color="dark" @click="close"> <v-icon>mdi-close</v-icon>close </v-btn>
                <v-btn small color="dark" @click="reset"><v-icon>mdi-eraser</v-icon>Reset</v-btn>
                <v-btn small color="dark" @click="search"><v-icon>mdi-layers-search</v-icon>Search</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex"
import RangeSlider from "./../../../reusable/RangeSlider"
import TextField from "./../../../reusable/TextField"
import NumberField from "./../../../reusable/NumberField"
import Select from "./../../../reusable/Select"
import DatePicker from "./../../../reusable/DatePicker"

export default {
    components: {
        RangeSlider,
        TextField,
        Select,
        NumberField,
        DatePicker
    },
    data() {
        return {
            isOpen: false,
            search_fields: {
                stock_number: "",
                inventory_status_id: 0,
                inventory_cosmetic_id: 0,
                origin_price: [0, 10000],
                selling_price: [0, 10000],
                discount_percentage: "",
                details: {}
            }
        }
    },
    computed: {
        ...mapGetters("product_list", ["product_type", "selected_product_type_id_selected", "product_types", "currency", "cosmetics_options", "statuses_options"]),
        cosmetic_selections() {
            let local = [...this.cosmetics_options]
            local.push({
                text: "All",
                value: 0
            })

            return local
        },
        status_selections() {
            let local = [...this.statuses_options]
            local.push({
                text: "All",
                value: 0
            })

            return local
        },
        selected_product_type: {
            set(value) {
                this.SET_PRODUCT_TYPE(value)
            },
            get() {
                return this.selected_product_type_id_selected
            }
        }
    },
    methods: {
        ...mapMutations("product_list", ["SET_PRODUCT_TYPE", "SET_SEARCH_PARAMS"]),
        ...mapActions("product_list", ["fetchData"]),
        close() {
            this.isOpen = false
        },
        async search() {
            let params = {
                search_fields: this.search_fields,
                selected_product_type_id: this.selected_product_type_id_selected
            }
            this.SET_SEARCH_PARAMS(params)
            await this.fetchData()
            this.close()
        },
        reset() {
            var reset_details = {}
            if (Object.keys(this.product_type).length) {
                this.product_type.product_attributes.map(column => {
                    reset_details = {
                        ...reset_details,
                        [column.product_column_name]: ""
                    }
                })
            }
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
        }
    }
}
</script>

<style></style>
