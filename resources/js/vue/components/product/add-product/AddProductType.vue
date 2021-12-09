<template>
    <v-row>
        <v-col lg="3" md="3">
            <v-form v-model="valid">
                <TextField class="mt-3 mb-3" :rules="productNameRules" @blur="setProductname" :hint="'Kind of product that you want to create (ex: Clothing, Computers, Mobile, Laptops, etc.)'" label="Product Type Name" v-model="product_name" />
            </v-form>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"

import TextField from "./../../reusable/TextField"

export default {
    components: {
        TextField
    },
    computed: {
        ...mapGetters("add_product", ["getProductName"])
    },
    methods: {
        ...mapMutations("add_product", ["SET_PRODUCT_NAME"]),
        ...mapMutations("stepper", ["SET_STEPPER"]),
        testProductName(val) {
            if (val) {
                this.SET_STEPPER({
                    canContinue: true
                })
            } else {
                this.SET_STEPPER({
                    canContinue: false
                })
            }
        },
        setProductname: function() {
            this.SET_PRODUCT_NAME(this.product_name)
        }
    },
    created() {
        this.product_name = this.getProductName
    },
    watch: {
        valid: function(newVal, oldVal) {
            this.testProductName(newVal)
        }
    },
    data: () => {
        return {
            valid: false,
            product_name: "",
            productNameRules: [value => !!value || "This is required", value => value.length >= 5 || "At least 5 characters", value => /^[a-zA-Z][A-Za-z0-9_]*$/im.test(value) || "Only alphanumeric and underscore is allowed"]
        }
    }
}
</script>

<style></style>
