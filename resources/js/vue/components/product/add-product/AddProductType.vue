<template>
    <v-row>
        <v-col lg="3" md="3">
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
        ...mapGetters("add_product", ["getProductName"])
    },
    methods: {
        ...mapMutations(["add_product/setProductName", "setStepper"]),
        testProductName(val) {
            if (val) {
                this.$store.commit("setStepper", {
                    canContinue: true
                })
            } else {
                this.$store.commit("setStepper", {
                    canContinue: false
                })
            }
        },
        setProductname: function() {
            this.$store.commit("add_product/setProductName", this.product_name)
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
