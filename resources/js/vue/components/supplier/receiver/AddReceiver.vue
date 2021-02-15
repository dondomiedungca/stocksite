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
                        <v-text-field :error-messages="customErrors('receiver_first_name')" @blur="$v.receiver_first_name.$touch()" dense label="Receiver First Name" outlined v-model="receiver_first_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field dense label="Receiver Middle Name" outlined v-model="receiver_middle_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('receiver_last_name')" @blur="$v.receiver_last_name.$touch()" dense label="Receiver Last Name" outlined v-model="receiver_last_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('receiver_email')" @blur="$v.receiver_email.$touch()" dense label="Receiver Email" outlined v-model="receiver_email" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('receiver_phone')" @blur="$v.receiver_phone.$touch()" dense label="Receiver Phone Number" outlined v-model="receiver_phone" type="text"></v-text-field>
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
                <v-btn color="primary" small @click="createReceiver">
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
            receiver_first_name: "",
            receiver_middle_name: "",
            receiver_last_name: "",
            receiver_email: "",
            receiver_phone: ""
        }
    },
    mixins: [validationMixin],
    props: ["isAddOpen"],
    watch: {
        isAddOpen(data) {
            this.isOpen = data
        }
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
        createReceiver: function() {
            var self = this
            this.$v.$touch()
            this.$refs.address_form.getValues()
            if (!this.$v.$invalid && this.$refs.address_form.valid == true) {
                var params = {
                    address: self.address,
                    receiver_first_name: self.receiver_first_name,
                    receiver_middle_name: self.receiver_middle_name,
                    receiver_last_name: self.receiver_last_name,
                    receiver_phone: self.receiver_phone,
                    receiver_email: self.receiver_email
                }
                swal.queue([
                    {
                        title: "New Receiver",
                        text: "This will create new receiver that can use for Purchasing Order, proceed?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, create now",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/admin/receiver/add-receiver", params).then(result => {
                                swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                                self.$refs.address.reset()
                                self.$emit("getReceiver")
                            })
                        }
                    }
                ])
            }
        },
        getValues: function(address) {
            this.address = address
        }
    },
    validations: {
        receiver_first_name: {
            required
        },
        receiver_last_name: {
            required
        },
        receiver_email: {
            required,
            email
        },
        receiver_phone: {
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
