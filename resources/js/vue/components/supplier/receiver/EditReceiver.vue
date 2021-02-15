<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(forEdit).length" style="width: 100%;">
            <v-card-title>Edit - {{ forEdit.receiver_last_name }} {{ forEdit.receiver_first_name }}</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Details</h4>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'receiver_first_name')" @blur="$v.forEdit.receiver_first_name.$touch()" dense label="Receiver First Name" outlined v-model="forEdit.receiver_first_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field dense label="Receiver Middle Name" outlined v-model="forEdit.receiver_middle_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'receiver_last_name')" @blur="$v.forEdit.receiver_last_name.$touch()" dense label="Receiver Last Name" outlined v-model="forEdit.receiver_last_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'receiver_phone')" @blur="$v.forEdit.receiver_phone.$touch()" dense label="Receiver Phone Number" outlined v-model="forEdit.receiver_phone" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'receiver_email')" @blur="$v.forEdit.receiver_email.$touch()" dense label="Receiver Email" outlined v-model="forEdit.receiver_email" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Address Details</h4>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-select outlined :items="address_types" v-model="forEdit.address.address_type_id" label="Address Type" dense></v-select>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('address', 'address_no_or_house_building_no')" @blur="$v.forEdit.address.address_no_or_house_building_no.$touch()" dense label="Address Bldg. No." outlined v-model="forEdit.address.address_no_or_house_building_no" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('address', 'address_st')" @blur="$v.forEdit.address.address_st.$touch()" dense label="Address St." outlined v-model="forEdit.address.address_st" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('address', 'address_city')" @blur="$v.forEdit.address.address_city.$touch()" dense label="Address City" outlined v-model="forEdit.address.address_city" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('address', 'address_state')" @blur="$v.forEdit.address.address_state.$touch()" dense label="Address State" outlined v-model="forEdit.address.address_state" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-select outlined :items="countries" v-model="forEdit.address.address_country" label="Address Country" dense></v-select>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('address', 'address_post_code')" @blur="$v.forEdit.address.address_post_code.$touch()" dense label="Address Post Code" outlined v-model="forEdit.address.address_post_code" type="text"></v-text-field>
                    </v-col>
                </v-row>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" small @click="close">
                    close
                </v-btn>
                <v-btn color="primary" small @click="updateReceiver">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { validationMixin } from "vuelidate"
import { required, numeric, email } from "vuelidate/lib/validators"

const isPhone = value => /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value)

export default {
    mixins: [validationMixin],
    props: ["isEditOpen", "forEdit", "countries", "address_types"],
    watch: {
        isEditOpen(data) {
            this.isOpen = data
        }
    },
    data() {
        return {
            isOpen: false,
            status_selections: []
        }
    },
    methods: {
        close: function() {
            this.isOpen = !this.isOpen
            this.$emit("close")
        },
        updateReceiver() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                var self = this
                swal.queue([
                    {
                        title: "Update This Receiver",
                        text: "Save changes in this receiver now?",
                        icon: "info",
                        showCancelButton: true,
                        cancelButtonColor: "#d33",
                        confirmButtonColor: "#42d1f5",
                        confirmButtonText: "Yes, update now",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios
                                .post("/admin/receiver/update-receiver", {
                                    receiver: self.forEdit
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
        customErrors(base, property_name) {
            var errors = []
            if (base == "address") {
                if (!this.$v.forEdit.address[property_name].$dirty) return (errors = [])
                !this.$v.forEdit.address[property_name].required && errors.push(`${property_name} field is required`)
                if (Object.prototype.hasOwnProperty.call(this.$v.forEdit.address[property_name], "numeric")) {
                    !this.$v.forEdit.address[property_name].numeric && errors.push("Only digit is allowed")
                }
            } else {
                if (!this.$v.forEdit[property_name].$dirty) return (errors = [])
                !this.$v.forEdit[property_name].required && errors.push(`${property_name} field is required`)
                if (Object.prototype.hasOwnProperty.call(this.$v.forEdit[property_name], "phoneValid")) {
                    !this.$v.forEdit[property_name].phoneValid && errors.push(`Valid phone number for ${property_name} is required`)
                }
                if (Object.prototype.hasOwnProperty.call(this.$v.forEdit[property_name], "email")) {
                    !this.$v.forEdit[property_name].email && errors.push(`Valid email is required`)
                }
            }
            return errors
        }
    },
    validations: {
        forEdit: {
            receiver_first_name: {
                required
            },
            receiver_last_name: {
                required
            },
            receiver_phone: {
                required,
                phoneValid: isPhone
            },
            receiver_email: {
                required,
                email
            },
            address: {
                address_no_or_house_building_no: {
                    required
                },
                address_st: {
                    required
                },
                address_city: {
                    required
                },
                address_state: {
                    required
                },
                address_country: {
                    required
                },
                address_post_code: {
                    required
                }
            }
        }
    }
}
</script>

<style></style>
