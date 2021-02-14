<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(forEdit).length" style="width: 100%;">
            <v-card-title>Edit - {{ forEdit.supplier_name }}</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="12" md="12" sm="12" xs="12">
                        <h4>Primary Details</h4>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'supplier_name')" @blur="$v.forEdit.supplier_name.$touch()" dense label="Supplier Name" outlined v-model="forEdit.supplier_name" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'supplier_email')" @blur="$v.forEdit.supplier_email.$touch()" dense label="Supplier Email" outlined v-model="forEdit.supplier_email" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-text-field :error-messages="customErrors('primary', 'supplier_phone_number')" @blur="$v.forEdit.supplier_phone_number.$touch()" dense label="Supplier Phone Number" outlined v-model="forEdit.supplier_phone_number" type="text"></v-text-field>
                    </v-col>
                    <v-col lg="3" md="3" sm="6" xs="12">
                        <v-select outlined :items="manufacturer_types" v-model="forEdit.manufacturer_id" label="Manufacturer Type" dense></v-select>
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
                <v-btn color="primary" small @click="updateSupplier">
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
    props: ["isEditOpen", "forEdit", "countries", "address_types", "manufacturer_types"],
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
        updateSupplier() {},
        customErrors(property_name) {}
    },
    validations: {
        forEdit: {
            supplier_name: {
                required
            },
            supplier_phone_number: {
                required,
                phoneValid: isPhone
            },
            supplier_email: {
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
