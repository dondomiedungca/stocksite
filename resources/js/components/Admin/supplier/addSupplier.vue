<template>
    <div>
        <div class="form-group">
            <label for="formGroupExampleInput2">Manufacturer Type</label>
            <select class="form-control form-control-sm mb-3" v-model="manufacturer">
                <option v-for="(man, i) in manufacturer_types" :value="man.id" v-bind:key="i">{{ man.manufacturer_type_name }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput">Supplier Name</label>
            <input type="text" :class="{ 'is-invalid': $v.supplier_name.$error }" name v-model="$v.supplier_name.$model" @change="supplier_name = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.supplier_name.$error">This field is required and only alpha is allowed</div>
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput2">Supplier Phone</label>
            <input type="text" :class="{ 'is-invalid': $v.supplier_phone.$error }" name v-model="$v.supplier_phone.$model" @change="supplier_phone = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.supplier_phone.$error">This field is required and only valid phone is allowed</div>
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput2">Supplier Email</label>
            <input type="email" :class="{ 'is-invalid': $v.supplier_email.$error }" name v-model="$v.supplier_email.$model" @change="supplier_email = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.supplier_email.$error">This field is required and only valid email is allowed</div>
        </div>
        <address-form ref="address" :title="'Supplier Address'" @getValues="getValues"></address-form>
    </div>
</template>

<script>
import { required, email } from "vuelidate/lib/validators"

const isPhone = value => /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value)

export default {
    data() {
        return {
            supplier_name: "",
            supplier_phone: "",
            supplier_email: "",
            manufacturer: 15,
            manufacturer_types: [],
            address: {}
        }
    },
    validations: {
        supplier_name: {
            required
        },
        supplier_phone: {
            required,
            phoneValid: isPhone
        },
        supplier_email: {
            required,
            email
        }
    },
    created() {
        this.getManufacturerTypes()
    },
    methods: {
        getManufacturerTypes: function() {
            axios.get("/admin/manufacturer/get-manufacturer-types").then(result => {
                this.manufacturer_types = result.data.manufacturer_types
            })
        },
        getValues: function(address) {
            this.address = address
        },
        createSupplier: function() {
            var self = this
            this.$v.$touch()
            this.$refs.address.getValues()
            if (!this.$v.$invalid && this.$refs.address.valid == true) {
                var params = {
                    address: self.address,
                    supplier_name: self.supplier_name,
                    supplier_email: self.supplier_email,
                    supplier_phone: self.supplier_phone,
                    manufacturer: self.manufacturer
                }
                swal.queue([
                    {
                        title: "New Supplier",
                        text: "This will create new supplier that can use for Purchasing Order, proceed?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, create now",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/admin/supplier/add-supplier", params).then(result => {
                                swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                                self.$refs.address.reset()
                                self.$emit("getSuppliers")
                            })
                        }
                    }
                ])
            }
        }
    }
}
</script>

<style></style>
