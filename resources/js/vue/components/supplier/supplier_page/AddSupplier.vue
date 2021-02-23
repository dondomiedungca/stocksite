<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card style="width: 100%;">
            <v-card-title>Add Supplier</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Details</h4>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('supplier_name')" @blur="$v.supplier_name.$touch()" dense label="Supplier Name" outlined v-model="supplier_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('supplier_email')" @blur="$v.supplier_email.$touch()" dense label="Supplier Email" outlined v-model="supplier_email" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('supplier_phone_number')" @blur="$v.supplier_phone_number.$touch()" dense label="Supplier Phone Number" outlined v-model="supplier_phone_number" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="manufacturer_types" v-model="manufacturer" label="Manufacturer Type" dense></v-select>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Address Details</h4>
                    </v-col>
                </v-row>
                <address-form class="form-row" ref="address_form" @getValues="getValues"></address-form>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" small @click="close">
                    close
                </v-btn>
                <v-btn color="primary" small @click="createSupplier">
                    Create
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { validationMixin } from "vuelidate"
import { required, numeric, email } from "vuelidate/lib/validators"
import AddressForm from "./../../reusable/AddressForm.vue"

const isPhone = value => /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value)

export default {
    components: {
        AddressForm
    },
    data() {
        return {
            address: {},
            isOpen: false,
            supplier_name: "",
            supplier_email: "",
            supplier_phone_number: "",
            manufacturer: 4,
            manufacturer_types: []
        }
    },
    mixins: [validationMixin],
    props: ["isAddOpen"],
    watch: {
        isAddOpen(data) {
            this.isOpen = data
        }
    },
    created() {
        this.getManufacturerTypes()
    },
    methods: {
        close() {
            this.$emit("close")
        },
        customErrors(property_name) {
            var errors = []
            if (!this.$v[property_name].$dirty) return (errors = [])
            !this.$v[property_name].required && errors.push(`${property_name} field is required`)
            if (Object.prototype.hasOwnProperty.call(this.$v[property_name], "phoneValid")) {
                !this.$v[property_name].phoneValid && errors.push(`Valid phone number for ${property_name} is required`)
            }
            if (Object.prototype.hasOwnProperty.call(this.$v[property_name], "email")) {
                !this.$v[property_name].email && errors.push(`Valid email is required`)
            }
            return errors
        },
        createSupplier: function() {
            var self = this
            this.$v.$touch()
            this.$refs.address_form.getValues()
            if (!this.$v.$invalid && this.$refs.address_form.valid == true) {
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
        },
        getValues: function(address) {
            this.address = address
        },
        getManufacturerTypes: function() {
            axios.get("/admin/manufacturer/get-manufacturer-types").then(result => {
                this.manufacturer_types = result.data.manufacturer_types.map(man => {
                    return {
                        text: man.manufacturer_type_name,
                        value: man.id
                    }
                })
            })
        }
    },
    validations: {
        supplier_name: {
            required
        },
        supplier_email: {
            required,
            email
        },
        supplier_phone_number: {
            required,
            phoneValid: isPhone
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 1%;
}
</style>
