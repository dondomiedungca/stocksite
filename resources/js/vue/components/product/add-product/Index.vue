<template>
    <div>
        <v-main id="v-main">
            <div class="custom-top-navigation">
                <v-container fluid>
                    <div class="nav-title font-weight-thin"><v-icon class="nav-icon mr-5">mdi-group</v-icon>ADD PRODUCT TYPE</div>
                </v-container>
                <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            </div>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <stepper-vue :step_count="2" :headers="headers">
                            <template v-slot:step_content_1>
                                <add-product-type></add-product-type>
                            </template>
                            <template v-slot:step_content_2>
                                <manage-columns></manage-columns>
                            </template>
                            <template v-slot:finish_button>
                                <asker :disabled="!getStepper.canFinish" icon_header_color="primary" icon_header="mdi-folder-information" :tooltip_show="false" :fab="false" title="Save this new product type to your inventory?" color="dark" @proceed="save">
                                    <template v-slot:togglerIcon> <v-icon>mdi-check</v-icon> Finish </template>
                                </asker>
                            </template>
                        </stepper-vue>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex"
import AddProductName from "./AddProductType.vue"
import ManageColumns from "./ManageColumns.vue"

export default {
    components: {
        "add-product-type": AddProductName,
        "manage-columns": ManageColumns
    },
    computed: {
        ...mapGetters("add_product", ["getProductName", "getColumns"]),
        ...mapGetters(["getStepper"])
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
            showConfirm: false,
            headers: [
                {
                    step_name: "Product Type",
                    step_level: 1
                },
                {
                    step_name: "Manage Column(s)",
                    step_level: 2
                }
            ],
            items: [
                {
                    text: "Products",
                    disabled: false,
                    exact: true,
                    to: "/admin/products",
                    icon: "mdi-warehouse"
                },
                {
                    text: "Add Product Type",
                    disabled: true,
                    to: "add-product-type",
                    icon: "mdi-group"
                }
            ]
        }
    }
}
</script>

<style></style>
