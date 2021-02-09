<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(forEdit).length" style="width: 100%;">
            <v-card-title>Edit {{ forEdit.stock_number }}</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Details</h4>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="status_selections" v-model="forEdit.inventory_status_id" label="Inventory Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="cosmetic_selections" v-model="forEdit.inventory_cosmetic_id" label="Item Cosmetic" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="discountPercentageErrors" @blur="$v.forEdit.discount_percentage.$touch()" dense label="Discount Percentage" outlined v-model="forEdit.discount_percentage" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="6" md="6" sm="6" xs="12">
                        <v-text-field :error-messages="originPriceErrors" @blur="$v.forEdit.origin_price.$touch()" dense label="Origin Price" outlined v-model="forEdit.origin_price" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="6" md="6" sm="6" xs="12">
                        <v-text-field :error-messages="sellingPriceErrors" @blur="$v.forEdit.selling_price.$touch()" dense label="Selling Price" outlined v-model="forEdit.selling_price" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="6" md="6" sm="6" xs="12">
                        <v-textarea rows="2" dense label="Inventory Cosmetic Description" outlined v-model="forEdit.item_cosmetic_description" type="text"></v-textarea>
                    </v-col>
                    <v-col lg="6" md="6" sm="6" xs="12">
                        <v-textarea rows="2" dense label="Inventory Description" outlined v-model="forEdit.item_description" type="text"></v-textarea>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Item Specifications</h4>
                    </v-col>
                    <template v-if="Object.keys(product_type).length">
                        <v-col lg="4" md="4" sm="6" xs="12" v-for="(product_attribute, i) in product_type.product_attributes" v-bind:key="i" class="col-4 mb-3">
                            <label><span v-if="product_attribute.product_column_is_required" style="color: red;">* </span>{{ product_attribute.product_column_name }}</label>
                            <div v-if="product_attribute.product_column_input_type == 'INPUT'">
                                <div v-if="product_attribute.product_column_data_type == 'STRING'">
                                    <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.product.details[product_attribute.product_column_name].$error }" name v-model="$v.product.details[product_attribute.product_column_name].$model" @change="product.details[product_attribute.product_column_name] = $event.target.value" />
                                    <div class="invalid-feedback" v-if="$v.product.details[product_attribute.product_column_name].$error">This field is required</div>
                                </div>
                                <div v-else-if="product_attribute.product_column_data_type == 'INTEGER'">
                                    <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': $v.product.details[product_attribute.product_column_name].$error }" name v-model="$v.product.details[product_attribute.product_column_name].$model" @change="product.details[product_attribute.product_column_name] = $event.target.value" />
                                    <div class="invalid-feedback" v-if="$v.product.details[product_attribute.product_column_name].$error">This field is required</div>
                                </div>
                                <div v-else>
                                    <input type="date" class="form-control form-control-sm" :class="{ 'is-invalid': $v.product.details[product_attribute.product_column_name].$error }" name v-model="$v.product.details[product_attribute.product_column_name].$model" @change="product.details[product_attribute.product_column_name] = $event.target.value" />
                                    <div class="invalid-feedback" v-if="$v.product.details[product_attribute.product_column_name].$error">This field is required</div>
                                </div>
                            </div>
                            <div v-else>
                                <select class="custom-select custom-select-sm" :class="{ 'is-invalid': $v.product.details[product_attribute.product_column_name].$error }" name v-model="$v.product.details[product_attribute.product_column_name].$model" @change="product.details[product_attribute.product_column_name] = $event.target.value">
                                    <option v-for="(selection, i) in product_attribute.column_selections" v-bind:key="i" :value="selection.selection_name">-- {{ selection.selection_name }} --</option>
                                </select>
                                <div class="invalid-feedback" v-if="$v.product.details[product_attribute.product_column_name].$error">This field is required</div>
                            </div>
                        </v-col>
                    </template>
                </v-row>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="gray darken-1" text @click="close">
                    close
                </v-btn>
                <v-btn color="green darken-1" text @click="updateItem">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { validationMixin } from "vuelidate"
import { required, numeric } from "vuelidate/lib/validators"
export default {
    mixins: [validationMixin],
    props: ["isEditOpen", "forEdit", "product_type", "statuses", "cosmetics"],
    watch: {
        isEditOpen(data) {
            this.isOpen = data
        },
        statuses(data) {
            this.status_selections = data.map(status => {
                return {
                    text: status.name,
                    value: status.id
                }
            })
        },
        cosmetics(data) {
            this.cosmetic_selections = data.map(cosmetic => {
                return {
                    text: cosmetic.name,
                    value: cosmetic.id
                }
            })
        }
    },
    data() {
        return {
            isOpen: false,
            status_selections: [],
            cosmetic_selections: []
        }
    },
    methods: {
        updateItem: function() {},
        close: function() {
            ;(this.isOpen = !this.isOpen), this.$emit("close")
        }
    },
    validations: {
        forEdit: {
            origin_price: {
                required,
                numeric
            },
            selling_price: {
                required,
                numeric
            },
            discount_percentage: {
                numeric
            }
        }
    },
    computed: {
        sellingPriceErrors() {
            const errors = []
            if (!this.$v.forEdit.selling_price.$dirty) return errors
            !this.$v.forEdit.selling_price.required && errors.push("Selling Price is required")
            !this.$v.forEdit.selling_price.numeric && errors.push("Only digit is allowed")
            return errors
        },
        originPriceErrors() {
            const errors = []
            if (!this.$v.forEdit.origin_price.$dirty) return errors
            !this.$v.forEdit.origin_price.required && errors.push("Origin Price is required")
            !this.$v.forEdit.origin_price.numeric && errors.push("Only digit is allowed")
            return errors
        },
        discountPercentageErrors() {
            const errors = []
            if (!this.$v.forEdit.discount_percentage.$dirty) return errors
            !this.$v.forEdit.discount_percentage.numeric && errors.push("Only digit is allowed")
            return errors
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 1%;
}
</style>
