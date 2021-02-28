<template>
    <v-container fluid style="margin-left: 0;">
        <v-row v-if="basis != 'purchasing' && product_types_selection.length" class="mt-5">
            <v-col lg="6" md="6" sm="6" xs="12">
                <v-select outlined :items="product_types_selection" v-model="selected_product_type" label="Inventory Type" dense></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="3" md="3" sm="6" xs="12" v-if="willShow()">
                <v-file-input :error-messages="photoErrors" id="upload-photo" ref="uploadPhoto" small-chips accept="image/*" v-model="photo" show-size counter label="Product Photo" outlined dense></v-file-input>
            </v-col>
            <v-col lg="12" md="12" sm="12" xs="12">
                <h4>Primary Fields</h4>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-text-field disabled outlined :value="item_data.stock_number" label="Stock Number" dense></v-text-field>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-select outlined :items="statuses" v-model="item_data.item_status_id" label="Inventory Status" dense></v-select>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-select outlined :items="cosmetics" v-model="item_data.item_cosmetic_id" label="Inventory Cosmetic" dense></v-select>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-text-field :error-messages="originPriceErrors" @blur="$v.item_data.origin_price.$touch()" outlined v-model="item_data.origin_price" label="Origin Price" dense></v-text-field>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-text-field :error-messages="sellingPriceErrors" @blur="$v.item_data.selling_price.$touch()" outlined v-model="item_data.selling_price" label="Selling Price" dense></v-text-field>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-text-field :error-messages="discountPercentageErrors" @blur="$v.item_data.discount_percentage.$touch()" outlined v-model="item_data.discount_percentage" label="Discount Percentage" dense></v-text-field>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-textarea rows="2" dense label="Inventory Cosmetic Description" outlined v-model="item_data.item_cosmetic_description" type="text"></v-textarea>
            </v-col>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-textarea rows="2" dense label="Inventory Description" outlined v-model="item_data.item_description" type="text"></v-textarea>
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="12">
                <h4>Specification Fields</h4>
            </v-col>
        </v-row>
        <v-row v-if="Object.keys(base_product_type).length && item_data.stock_number != ''">
            <v-col v-for="(column, i) in base_product_type.product_attributes" v-bind:key="i" lg="3" md="3" sm="6" xs="12">
                <div v-if="column.product_column_input_type == 'INPUT'">
                    <div v-if="column.product_column_data_type == 'STRING'">
                        <v-text-field :error-messages="customErrors[column.product_column_name]" type="text" @blur="$v.item_data.details[column.product_column_name].$touch()" outlined v-model="item_data.details[column.product_column_name]" :label="column.product_column_name" dense></v-text-field>
                    </div>
                    <div v-else>
                        <v-text-field style="cursor: pointer;" @click="setPropertyNameForDate(column.product_column_name)" :error-messages="customErrors[column.product_column_name]" @blur="$v.item_data.details[column.product_column_name].$touch()" v-model="item_data.details[column.product_column_name]" :label="column.product_column_name" prepend-icon="mdi-calendar" outlined dense readonly></v-text-field>
                    </div>
                </div>
                <div v-else>
                    <v-select outlined :error-messages="customErrors[column.product_column_name]" :items="customSelections(column.column_selections)" v-model="item_data.details[column.product_column_name]" :label="column.product_column_name" dense></v-select>
                </div>
            </v-col>
            <v-dialog v-model="dateDialog" ref="dialog" :return-value.sync="item_data.details[date_modal_property_name]" persistent width="290px">
                <v-date-picker v-model="item_data.details[date_modal_property_name]" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dateDialog = false" color="secondary">
                        Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.dialog.save(item_data.details[date_modal_property_name])">
                        OK
                    </v-btn>
                </v-date-picker>
            </v-dialog>
        </v-row>
        <v-row>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-btn small color="dark" @click="saveItem"> <v-icon>mdi-check</v-icon>Save Item </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { Fragment } from "vue-fragment"
import { validationMixin } from "vuelidate"
import { required, numeric } from "vuelidate/lib/validators"
export default {
    mixins: [validationMixin],
    components: {
        Fragment
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
    computed: {
        sellingPriceErrors() {
            const errors = []
            if (!this.$v.item_data.selling_price.$dirty) return errors
            !this.$v.item_data.selling_price.required && errors.push("Selling Price is required")
            !this.$v.item_data.selling_price.numeric && errors.push("Only digit is allowed")
            return errors
        },
        originPriceErrors() {
            const errors = []
            if (!this.$v.item_data.origin_price.$dirty) return errors
            !this.$v.item_data.origin_price.required && errors.push("Origin Price is required")
            !this.$v.item_data.origin_price.numeric && errors.push("Only digit is allowed")
            return errors
        },
        discountPercentageErrors() {
            const errors = []
            if (!this.$v.item_data.discount_percentage.$dirty) return errors
            !this.$v.item_data.discount_percentage.numeric && errors.push("Only digit is allowed")
            return errors
        },
        photoErrors() {
            const errors = []
            if (!this.$v.photo.$dirty) return errors
            !this.$v.photo.required && errors.push("Product Photo is required")
            return errors
        },
        customErrors() {
            var error_by_property_name = {}
            this.base_product_type.product_attributes.map(property_name => {
                var errors = []
                if (!this.$v.item_data.details[property_name.product_column_name].$dirty) return (errors = [])
                !this.$v.item_data.details[property_name.product_column_name].required && errors.push(`${property_name.product_column_name} field is required`)
                if (Object.prototype.hasOwnProperty.call(this.$v.item_data.details[property_name.product_column_name], "numeric")) {
                    !this.$v.item_data.details[property_name.product_column_name].numeric && errors.push("Only digit is allowed")
                }
                error_by_property_name = {
                    ...error_by_property_name,
                    [property_name.product_column_name]: errors
                }
            })

            return error_by_property_name
        }
    },
    data() {
        return {
            photo: [],
            purchasing_type: {},
            selected_product_type: null,
            product_types_selection: [],
            base_product_type: {},
            item_data: {
                stock_number: "",
                item_status_id: 1,
                item_cosmetic_id: 1,
                item_cosmetic_description: "",
                item_description: "",
                origin_price: "",
                selling_price: "",
                discount_percentage: "",
                details: {}
            },
            statuses: [],
            cosmetics: [],
            product_types: [],
            dateDialog: false,
            date_modal_property_name: ""
        }
    },
    watch: {
        product_types: function() {
            this.selected_product_type = 0
        },
        selected_product_type: function(newVal, oldVal) {
            this.base_product_type = this.product_types[newVal]
            this.createValidation()
        }
    },
    created() {
        this.initialize()
        this.createValidation()
        this.createValidationPhoto()
        this.getStockNumber()
        this.getCosmetics()
        this.getStatuses()
        this.getPurchasing()
    },
    methods: {
        willShow: function() {
            if (this.basis != "purchasing") {
                return true
            } else {
                if (Object.keys(this.purchasing_type).length) {
                    if (this.purchasing_type.photo == null) {
                        return true
                    } else {
                        return false
                    }
                }
            }
        },
        initialize: function() {
            if (this.basis != "purchasing") {
                this.getProductTypes()
            } else {
                this.base_product_type = this.product_type
            }
        },
        setPropertyNameForDate: function(name) {
            this.date_modal_property_name = name
            this.dateDialog = true
        },
        getProductTypes: function() {
            axios.get("/admin/products/get-all-product-types").then(res => {
                this.product_types = res.data.product_types
                this.product_types_selection = res.data.product_types.map((product_type, i) => {
                    return {
                        text: product_type.product_name,
                        value: i
                    }
                })
            })
        },
        getPurchasing: function() {
            axios.get("/admin/purchasing/get-purchasing-type/" + this.purchasing_type_id).then(res => {
                this.purchasing_type = res.data.purchasing
            })
        },
        getStockNumber: function() {
            axios.get("/admin/products/get-stock-number").then(res => {
                this.item_data.stock_number = res.data.stock_number
            })
        },
        getCosmetics: function() {
            axios.get("/admin/products/get-cosmetics").then(res => {
                this.cosmetics = res.data.cosmetics.map(cosmetic => {
                    return {
                        text: cosmetic.name,
                        value: cosmetic.id
                    }
                })
            })
        },
        getStatuses: function() {
            axios.get("/admin/products/get-item-statuses").then(res => {
                this.statuses = res.data.statuses.map(status => {
                    return {
                        text: status.name,
                        value: status.id
                    }
                })
            })
        },
        customSelections(data) {
            return data.map(selection => selection.selection_name)
        },
        createValidationPhoto: function() {
            var validations = {}

            if (this.basis == "purchasing") {
                if (this.purchasing_type.photo == null) {
                    validations = {
                        required
                    }
                }
            } else {
                validations = {
                    required
                }
            }

            return validations
        },
        createValidation: function() {
            var validations = {}
            this.item_data.details = {}
            if (Object.keys(this.base_product_type).length) {
                this.base_product_type.product_attributes.map(column => {
                    this.item_data.details = {
                        ...this.item_data.details,
                        [column.product_column_name]: ""
                    }
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
            }
            return validations
        },
        parseFilePhoto: function() {
            this.photo = this.$refs.uploadPhoto.files
        },
        saveItem: function() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                var self = this
                let formData = new FormData()
                formData.append("photo", self.photo)
                formData.append("inventory", JSON.stringify(self.item_data))
                formData.append("purchasing_type_id", self.purchasing_type_id)
                formData.append("basis", self.basis)
                formData.append("transaction_id", self.transaction_id)
                formData.append("product_type_id", self.base_product_type.id)
                swal.queue([
                    {
                        title: "Save Item",
                        text: self.basis != "purchasing" ? "This will be add to database and add to your purchasing, Do you want to proceed?" : "This will be add to database, Do you want to proceed?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios
                                .post(`/admin/products/save-manual-item`, formData, {
                                    headers: { "Content-Type": "multipart/form-data" }
                                })
                                .then(result => {
                                    swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                                    if (this.basis == "purchasing") {
                                        this.$emit("update_purchasing")
                                    }
                                })
                        }
                    }
                ])
            }
        }
    },
    validations() {
        return {
            photo: this.createValidationPhoto(),
            item_data: {
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
                details: this.createValidation()
            }
        }
    }
}
</script>

<style></style>
