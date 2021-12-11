<template>
    <v-container fluid style="margin-left: 0;">
        <v-form v-model="valid" ref="form" @submit.prevent>
            <v-row v-if="basis != 'purchasing' && product_types.length" class="mt-5">
                <v-col lg="6" md="6" sm="6" xs="12">
                    <Select :items="product_types" v-model="selected_product_type_id_local" label="Inventory Type" />
                </v-col>
            </v-row>
            <v-row>
                <v-col lg="6" md="12" sm="6" xs="12" v-if="photoVisibility">
                    <InputFile :rules="requiredFile" id="upload-photo" ref="uploadPhoto" accept="image/*" v-model="photo" label="Product Photo" />
                </v-col>
                <v-col lg="12" md="12" sm="12" xs="12">
                    <h4>Primary Fields</h4>
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextField disabled :value="stock_number" label="Stock Number" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <Select :items="statuses" v-model="item_data.item_status_id" label="Inventory Status" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <Select :items="cosmetics" v-model="item_data.item_cosmetic_id" label="Inventory Cosmetic" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextField :rules="requiredAndDigitRule" v-model="item_data.origin_price" label="Origin Price" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextField :rules="requiredAndDigitRule" v-model="item_data.selling_price" label="Selling Price" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextField :rules="requiredAndDigitMaxRule" v-model="item_data.discount_percentage" label="Discount Percentage" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextArea label="Inventory Cosmetic Description" v-model="item_data.item_cosmetic_description" />
                </v-col>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <TextArea label="Inventory Description" v-model="item_data.item_description" />
                </v-col>
            </v-row>
            <v-row>
                <v-col lg="12">
                    <h4>Specification Fields</h4>
                </v-col>
            </v-row>
            <v-row v-if="Object.keys(selected_product_type).length && stock_number != ''">
                <v-col v-for="(column, i) in selected_product_type.product_attributes" v-bind:key="i" lg="3" md="3" sm="6" xs="12">
                    <div v-if="column.product_column_input_type == 'INPUT'">
                        <div v-if="column.product_column_data_type == 'STRING'">
                            <TextField v-model="item_data.details[column.product_column_name]" :rules="column.product_column_is_required ? required : null" :label="column.product_column_name" />
                        </div>
                        <div v-else-if="column.product_column_data_type == 'INTEGER'">
                            <NumberField v-model="item_data.details[column.product_column_name]" :rules="column.product_column_is_required ? required : null" :label="column.product_column_name" />
                        </div>
                        <div v-else>
                            <DatePicker v-model="item_data.details[column.product_column_name]" :rules="column.product_column_is_required ? required : null" :label="column.product_column_name" />
                        </div>
                    </div>
                    <div v-else>
                        <Select :items="customSelections(column.column_selections)" :rules="column.product_column_is_required ? required : null" v-model="item_data.details[column.product_column_name]" :label="column.product_column_name" />
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col lg="3" md="3" sm="6" xs="12">
                    <Asker icon_header="mdi-information" :loading="isLoading" :icon="false" :fab="false" icon_header_color="primary" :tooltip_show="false" title="This will be add to database and add to your purchasing, Do you want to proceed?" @proceed="saveItem">
                        <template v-slot:togglerIcon>
                            <v-icon>
                                mdi-check
                            </v-icon>
                            Save item
                        </template>
                    </Asker>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex"

import Select from "./../../reusable/Select"
import TextField from "./../../reusable/TextField"
import NumberField from "./../../reusable/NumberField"
import DatePicker from "./../../reusable/DatePicker"
import InputFile from "./../../reusable/InputFile"
import TextArea from "./../../reusable/TextArea"
import Asker from "./../../reusable/Asker"

export default {
    components: {
        Select,
        TextField,
        NumberField,
        DatePicker,
        InputFile,
        TextArea,
        Asker
    },
    props: {
        basis: {
            type: String,
            required: true
        },
        product_type: {
            type: Object,
            default: null
        },
        purchasing_type_id: {
            type: Number,
            default: null
        },
        transaction_id: {
            type: Number,
            default: null
        }
    },
    data() {
        return {
            photo: [],
            valid: false,

            item_data: {
                item_status_id: 1,
                item_cosmetic_id: 1,
                item_cosmetic_description: "",
                item_description: "",
                origin_price: "",
                selling_price: "",
                discount_percentage: 0,
                details: {}
            },

            requiredFile: [v => !!v || "File is required", v => (v && v.size > 0) || "File is required"],
            required: [value => !!value || "This field is required"],
            requiredAndDigitRule: [value => !!value || "This field is required", value => /^[\d\,\.]+$/.test(value) || "Price format (eg. 100,000)"],
            requiredAndDigitMaxRule: [value => /^[\d]+$/.test(value) || "Discount format (eg. 10, 50, 75)", value => value <= 100 || "Discount not exceed to 100"]
        }
    },
    mounted() {
        this.doManipulate()
    },
    computed: {
        ...mapState("product_import", ["selected_product_type_id", "isLoading", "stock_number", "product_types", "photoVisibility", "cosmetics", "statuses", "selected_product_type"]),
        selected_product_type_id_local: {
            get() {
                return this.selected_product_type_id
            },
            set(v) {
                this.SET_PRODUCT_TYPE_ID(v)
            }
        }
    },
    methods: {
        ...mapActions("product_import", ["setForPurchasing", "saveManually"]),
        ...mapMutations("product_import", ["SET_PRODUCT_TYPE_ID"]),
        doManipulate() {
            if (this.basis == "purchasing") {
                const data = {
                    product_type: this.product_type,
                    purchasing_type_id: this.purchasing_type_id
                }
                this.setForPurchasing(data)
            }
        },
        customSelections(data) {
            return data.map(selection => selection.selection_name)
        },
        saveItem: function() {
            this.$refs.form.validate()
            if (this.valid) {
                var self = this
                let data = {
                    item_data: self.item_data,
                    photo: self.photo,
                    basis: self.basis
                }
                this.saveManually(data)
            }
        }
    }
}
</script>

<style></style>
