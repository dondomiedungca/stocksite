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
                            <div v-if="product_attribute.product_column_input_type == 'INPUT'">
                                <div v-if="product_attribute.product_column_data_type == 'STRING'">
                                    <v-text-field :error-messages="customErrors[product_attribute.product_column_name]" @blur="$v.forEdit.details[product_attribute.product_column_name].$touch()" dense :label="product_attribute.product_column_name" outlined v-model="forEdit.details[product_attribute.product_column_name]" type="text"></v-text-field>
                                </div>
                                <div v-else>
                                    <v-text-field style="cursor: pointer;" @click="setPropertyNameForDate(product_attribute.product_column_name)" :error-messages="customErrors[product_attribute.product_column_name]" @blur="$v.forEdit.details[product_attribute.product_column_name].$touch()" v-model="forEdit.details[product_attribute.product_column_name]" :label="product_attribute.product_column_name" prepend-icon="mdi-calendar" outlined dense readonly></v-text-field>
                                </div>
                            </div>
                            <div v-else>
                                <v-select outlined :error-messages="customErrors[product_attribute.product_column_name]" :items="customSelections(product_attribute.column_selections)" v-model="forEdit.details[product_attribute.product_column_name]" :label="product_attribute.product_column_name" dense></v-select>
                            </div>
                        </v-col>
                    </template>
                    <v-dialog v-model="dateDialog" ref="dialog" :return-value.sync="forEdit.details[date_modal_property_name]" persistent width="290px">
                        <v-date-picker v-model="forEdit.details[date_modal_property_name]" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text @click="dateDialog = false" color="secondary">
                                Cancel
                            </v-btn>
                            <v-btn text color="primary" @click="$refs.dialog.save(forEdit.details[date_modal_property_name])">
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-row>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" small @click="close">
                    close
                </v-btn>
                <v-btn color="primary" small @click="updateItem">
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
            cosmetic_selections: [],
            modal: false,
            dateDialog: false,
            date_modal_property_name: ""
        }
    },
    methods: {
        updateItem: function() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                var self = this
                swal.queue([
                    {
                        title: "Update This Product",
                        text: "Save changes in this item now?",
                        icon: "info",
                        showCancelButton: true,
                        cancelButtonColor: "#d33",
                        confirmButtonColor: "#42d1f5",
                        confirmButtonText: "Yes, update now",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios
                                .post("/admin/products/update-product", {
                                    inventory: self.forEdit
                                })
                                .then(response => {
                                    swal.fire({
                                        title: response.data.heading,
                                        text: response.data.message,
                                        icon: response.data.isSuccess ? "success" : "error"
                                    })
                                })
                        }
                    }
                ])
            }
        },
        setPropertyNameForDate: function(name) {
            this.date_modal_property_name = name
            this.dateDialog = true
        },
        close: function() {
            this.isOpen = !this.isOpen
            this.$emit("close")
        },
        customSelections(data) {
            return data.map(selection => selection.selection_name)
        },
        createValidationForDetails() {
            var validations = {}
            this.product_type.product_attributes.map(column => {
                if (column.product_column_is_required) {
                    validations = {
                        ...validations,
                        [column.product_column_name]: {
                            required
                        }
                    }
                }
                if (column.product_column_data_type == "INTEGER") {
                    validations = {
                        ...validations,
                        [column.product_column_name]: {
                            ...validations[column.product_column_name],
                            numeric
                        }
                    }
                }
            })

            return validations
        }
    },
    validations() {
        return {
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
                },
                details: this.createValidationForDetails()
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
        },
        customErrors() {
            var error_by_property_name = {}
            this.product_type.product_attributes.map(property_name => {
                var errors = []
                if (!this.$v.forEdit.details[property_name.product_column_name].$dirty) return (errors = [])
                !this.$v.forEdit.details[property_name.product_column_name].required && errors.push(`${property_name.product_column_name} field is required`)
                if (Object.prototype.hasOwnProperty.call(this.$v.forEdit.details[property_name.product_column_name], "numeric")) {
                    !this.$v.forEdit.details[property_name.product_column_name].numeric && errors.push("Only digit is allowed")
                }
                error_by_property_name = {
                    ...error_by_property_name,
                    [property_name.product_column_name]: errors
                }
            })

            return error_by_property_name
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 1%;
}
</style>
