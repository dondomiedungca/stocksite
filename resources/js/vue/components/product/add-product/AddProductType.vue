<template>
    <v-row>
        <v-col lg="6" md="6">
            <v-form v-model="valid">
                <v-text-field dense class="mt-3 mb-3" :rules="productNameRules" :hint="'Kind of product that you want to create (ex: Clothing, Computers, Mobile, Laptops, etc.)'" :label="'Product Type Name'" outlined v-model="product_name" type="text"></v-text-field>
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
        }
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
            productNameRules: [value => !!value || "This is required", value => value.length >= 5 || "At least 5 characters"]
        }
    }
}
</script>

<style></style>
