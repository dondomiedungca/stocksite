<template>
    <div v-if="Object.keys(base_product_type).length && item_data.stock_number != '' && Object.keys(purchasing_type).length">
        <div class="row">
            <div class="col-md-12">
                <h4 v-if="purchasing_type.photo == null">* Product Photo</h4>
                <div v-if="purchasing_type.photo == null" class="row">
                    <div class="form-group col-md-4">
                        <label for="">Choose file</label>
                        <input id="upload-photo" ref="uploadPhoto" @change="parseFilePhoto($event)" type="file" class="form-control form-control-sm" :class="{ 'is-invalid': $v.photo.$error }" />
                        <div class="invalid-feedback" v-if="$v.photo.$error">This photo is required</div>
                    </div>
                </div>
                <h4>* Primary Details</h4>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Stock No.</label>
                        <input type="text" readonly class="form-control form-control-sm" :value="item_data.stock_number" />
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Status</label>
                        <select class="custom-select custom-select-sm" v-model="item_data.item_status_id">
                            <option v-for="(status, i) in statuses" v-bind:key="i" :value="status.id">-- {{ status.name }} --</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Cosmetic</label>
                        <select class="custom-select custom-select-sm" v-model="item_data.item_cosmetic_id">
                            <option v-for="(cosmetic, i) in cosmetics" v-bind:key="i" :value="cosmetic.id">-- {{ cosmetic.name }} --</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Cosmetic Description</label>
                        <textarea class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.item_cosmetic_description.$error }" name v-model="$v.item_data.item_cosmetic_description.$model" @change="item_data.item_cosmetic_description = $event.target.value" rows="3"></textarea>
                        <div class="invalid-feedback" v-if="$v.item_data.item_cosmetic_description.$error">This field is required</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Description</label>
                        <textarea class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.item_description.$error }" name v-model="$v.item_data.item_description.$model" @change="item_data.item_description = $event.target.value" rows="3"></textarea>
                        <div class="invalid-feedback" v-if="$v.item_data.item_description.$error">This field is required</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Origin Price</label>
                        <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.origin_price.$error }" name v-model="$v.item_data.origin_price.$model" @change="item_data.origin_price = $event.target.value" />
                        <div class="invalid-feedback" v-if="$v.item_data.origin_price.$error">This field is required</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label><span style="color: red;">* </span>Item Selling Price</label>
                        <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.selling_price.$error }" name v-model="$v.item_data.selling_price.$model" @change="item_data.selling_price = $event.target.value" />
                        <div class="invalid-feedback" v-if="$v.item_data.selling_price.$error">This field is required</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Item Discount Percentage</label>
                        <input type="number" min="0" max="100" class="form-control form-control-sm" v-model="item_data.discount_percentage" />
                    </div>
                </div>
                <h4>* Item Specification</h4>
                <div class="row">
                    <div v-for="(column, i) in base_product_type.product_attributes" v-bind:key="i" class="form-group col-md-4">
                        <label><span v-if="column.product_column_is_required" style="color: red;">* </span>{{ column.product_column_name }}</label>
                        <fragment v-if="column.product_column_input_type == 'INPUT'">
                            <fragment v-if="column.product_column_data_type == 'STRING'">
                                <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.details[column.product_column_name].$error }" name v-model="$v.item_data.details[column.product_column_name].$model" @change="item_data.details[column.product_column_name] = $event.target.value" />
                            </fragment>
                            <fragment v-else-if="column.product_column_data_type == 'INTEGER'">
                                <input type="number" class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.details[column.product_column_name].$error }" name v-model="$v.item_data.details[column.product_column_name].$model" @change="item_data.details[column.product_column_name] = $event.target.value" />
                            </fragment>
                            <fragment v-else>
                                <input type="date" class="form-control form-control-sm" :class="{ 'is-invalid': $v.item_data.details[column.product_column_name].$error }" name v-model="$v.item_data.details[column.product_column_name].$model" @change="item_data.details[column.product_column_name] = $event.target.value" />
                            </fragment>
                        </fragment>
                        <fragment v-else>
                            <select class="custom-select custom-select-sm" :class="{ 'is-invalid': $v.item_data.details[column.product_column_name].$error }" name v-model="$v.item_data.details[column.product_column_name].$model" @change="item_data.details[column.product_column_name] = $event.target.value">
                                <option v-for="(selection, i) in column.column_selections" v-bind:key="i" :value="selection.selection_name">-- {{ selection.selection_name }} --</option>
                            </select>
                        </fragment>
                        <div class="invalid-feedback" v-if="$v.item_data.details[column.product_column_name].$error">This field is required</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button @click="saveItem" class="btn btn-sm btn-primary"><i class="fa fa-check"></i>Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Fragment } from "vue-fragment"
import { required } from "vuelidate/lib/validators"

export default {
    components: { Fragment },
    props: ["basis", "product_type", "purchasing_type_id", "transaction_id"],
    data() {
        return {
            photo: "",
            purchasing_type: {},
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
            cosmetics: []
        }
    },
    created() {
        this.initialize()
        this.createValidation()
        this.createValidationPhoto()
        this.getStockNumber()
        this.getCosmetics()
        this.getStatuses(), this.getPurchasing()
    },
    methods: {
        getProductTypes: function() {},
        initialize: function() {
            if (this.basis != "purchasing") {
                this.getProductTypes()
            } else {
                this.base_product_type = this.product_type
            }
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
                this.cosmetics = res.data.cosmetics
            })
        },
        getStatuses: function() {
            axios.get("/admin/products/get-item-statuses").then(res => {
                this.statuses = res.data.statuses
            })
        },
        createValidationPhoto: function() {
            var validations = {}

            if (this.purchasing_type.photo == null) {
                validations = {
                    required
                }
            }

            return validations
        },
        createValidation: function() {
            var validations = {}
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
            })

            return validations
        },
        parseFilePhoto: function() {
            let uploadFiles = document.getElementById("upload-photo")
            this.photo = this.$refs.uploadPhoto.files[0]
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
                item_cosmetic_description: {
                    required
                },
                item_description: {
                    required
                },
                origin_price: {
                    required
                },
                selling_price: {
                    required
                },
                details: this.createValidation()
            }
        }
    }
}
</script>

<style></style>
