<template>
    <Container title="Create Product Type" icon="mdi-view-list" :breadCrumbs="breadCrumbs">
        <Stepper :step_count="2" :headers="headers">
            <template v-slot:step_content_1>
                <AddProductName />
            </template>
            <template v-slot:step_content_2>
                <ManageColumns />
            </template>
            <template v-slot:finish_button>
                <Asker :disabled="!getStepper.canFinish" icon_header_color="primary" icon_header="mdi-folder-information" :tooltip_show="false" :fab="false" title="Save this new product type to your inventory?" color="dark" @proceed="save">
                    <template v-slot:togglerIcon> <v-icon>mdi-check</v-icon> Finish </template>
                </Asker>
            </template>
        </Stepper>
    </Container>
</template>

<script>
import { mapGetters, mapMutations } from "vuex"
import { headers, breadCrumbs } from "./assets/constant"
import AddProductName from "./AddProductType.vue"
import ManageColumns from "./ManageColumns.vue"

import Container from "./../../reusable/Container"
import Stepper from "./../../reusable/Stepper"
import Asker from "./../../reusable/Asker"

export default {
    components: {
        AddProductName,
        ManageColumns,
        Stepper,
        Container,
        Asker
    },
    computed: {
        ...mapGetters("add_product", ["getProductName", "getColumns"]),
        ...mapGetters("stepper", ["getStepper"])
    },
    methods: {
        ...mapMutations(["setSnackbar"]),
        save: function() {
            var self = this
            var params = { columns: self.getColumns, product_name: self.getProductName }
            axios.post("/admin/products/add-product-type", params).then(result => {
                // swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                this.$store.commit("setSnackbar", {
                    isVisible: true,
                    type: result.data.success ? "success" : "error",
                    text: result.data.message
                })
            })
        }
    },
    data() {
        return {
            breadCrumbs: [],
            headers: []
        }
    },
    created() {
        this.headers = headers
        this.breadCrumbs = breadCrumbs
    }
}
</script>

<style></style>
