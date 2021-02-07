<template>
    <v-row>
        <v-col lg="6" md="6">
            <v-form v-model="valid">
                <v-text-field dense class="mt-3 mb-3" :rules="productNameRules" @blur="setProductname" :hint="'Kind of product that you want to create (ex: Clothing, Computers, Mobile, Laptops, etc.)'" :label="'Product Type Name'" outlined v-model="product_name" type="text"></v-text-field>
            </v-form>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"
import { mapActions } from "vuex"

export default {
    computed: {
        ...mapGetters(["getProductName"])
    },
    methods: {
        ...mapMutations(["setProductName", "setStepper"]),
        testProductName(val) {
            if (val) {
                this.setStepper({
                    canContinue: true
                })
            } else {
                this.setStepper({
                    canContinue: false
                })
            }
        },
        setProductname: function() {
            this.setProductName(this.product_name)
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
