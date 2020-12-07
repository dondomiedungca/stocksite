<template>
    <div>
        <div class="form-group">
            <label for="">First Name</label>
            <input type="text" :class="{ 'is-invalid': $v.receiver_first_name.$error }" name v-model="$v.receiver_first_name.$model" @change="receiver_first_name = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.receiver_first_name.$error">This field is required</div>
        </div>
        <div class="form-group">
            <label for="">Middle Name</label>
            <input type="text" :class="{ 'is-invalid': $v.receiver_middle_name.$error }" name v-model="$v.receiver_middle_name.$model" @change="receiver_middle_name = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.receiver_middle_name.$error">This field is required</div>
        </div>
        <div class="form-group">
            <label for="">Last Name</label>
            <input type="text" :class="{ 'is-invalid': $v.receiver_last_name.$error }" name v-model="$v.receiver_last_name.$model" @change="receiver_last_name = $event.target.value" class="form-control form-control-sm" />
            <div class="invalid-feedback" v-if="$v.receiver_last_name.$error">This field is required</div>
        </div>
        <div class="form-group">
            <label for="">Phone Number</label>
            <input type="text" class="form-control form-control-sm" />
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="text" class="form-control form-control-sm" />
        </div>
        <address-form ref="address" :title="'Receiver Address'" @getValues="getValues"></address-form>
    </div>
</template>

<script>
import { required, email } from "vuelidate/lib/validators"

const isPhone = value => /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value)

export default {
    data() {
        return {
            receiver_first_name: "",
            receiver_middle_name: "",
            receiver_last_name: "",
            receiver_phone: "",
            receiver_email: "",
            address: {}
        }
    },
    methods: {
        getValues: function(address) {
            this.address = address
        },
        createReceiver: function() {
            var self = this
            this.$v.$touch()
            this.$refs.address.getValues()
            if (!this.$v.$invalid && this.$refs.address.valid == true) {
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
        }
    },
    validations: {
        receiver_first_name: {
            required
        },
        receiver_middle_name: {
            required
        },
        receiver_last_name: {
            required
        }
    }
}
</script>

<style></style>
