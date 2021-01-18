<template>
    <div v-if="Object.keys(product).length && Object.keys(product_type).length">
        <div class="row">
            <div class="col-md-12">
                <h4>* Primary Details</h4>
                <div class="row">
                    <div class="col-4">
                        <label for="">Inventory Status</label>
                        <select class="custom-select custom-select-sm" v-model="product.inventory_status_id">
                            <option v-for="(status, i) in statuses" v-bind:key="i" :value="status.id">-- {{ status.name }} --</option>
                        </select>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="">Inventory Cosmetic</label>
                        <select class="custom-select custom-select-sm" v-model="product.inventory_cosmetic_id">
                            <option v-for="(cosmetic, i) in cosmetics" v-bind:key="i" :value="cosmetic.id">-- {{ cosmetic.name }} --</option>
                        </select>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="">Inventory Cosmetic Description</label>
                        <input type="text" class="form-control form-control-sm" v-model="product.item_cosmetic_description" />
                    </div>
                    <div class="col-4 mb-3">
                        <label for="">Inventory Description</label>
                        <input type="text" class="form-control form-control-sm" v-model="product.item_description" />
                    </div>
                    <div class="col-4 mb-3">
                        <label for=""><span style="color: red;">* </span>Origin Price</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.product.origin_price.$error }" name v-model="$v.product.origin_price.$model" @change="product.origin_price = $event.target.value" />
                        <div class="invalid-feedback" v-if="$v.product.origin_price.$error">This field is required</div>
                    </div>
                    <div class="col-4 mb-3">
                        <label for=""><span style="color: red;">* </span>Selling Price</label>
                        <input type="text" class="form-control form-control-sm" :class="{ 'is-invalid': $v.product.selling_price.$error }" name v-model="$v.product.selling_price.$model" @change="product.selling_price = $event.target.value" />
                        <div class="invalid-feedback" v-if="$v.product.selling_price.$error">This field is required</div>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="">Discount Percentage</label>
                        <input type="text" class="form-control form-control-sm" v-model="product.discount_percentage" />
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>* Specification</h4>
                <div class="row">
                    <div v-for="(product_attribute, i) in product_type.product_attributes" v-bind:key="i" class="col-4 mb-3">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { required } from "vuelidate/lib/validators"

export default {
    props: ["product", "product_type"],
    data() {
        return {
            cosmetics: [],
            statuses: []
        }
    },
    created() {
        this.getCosmetics()
        this.getStatuses()
    },
    methods: {
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
        createValidation: function() {
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
            })

            return validations
        }
    },
    validations() {
        return {
            product: {
                inventory_status_id: {
                    required
                },
                inventory_cosmetic_id: {
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
