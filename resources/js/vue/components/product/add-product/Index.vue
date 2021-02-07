<template>
    <div>
        <v-main>
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
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
                                <v-btn @click="save" :disabled="!getStepper.canFinish" color="primary">
                                    Finish
                                </v-btn>
                            </template>
                        </stepper-vue>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import AddProductName from "./AddProductType.vue"
import ManageColumns from "./ManageColumns.vue"

export default {
    components: {
        "add-product-type": AddProductName,
        "manage-columns": ManageColumns
    },
    computed: {
        ...mapGetters(["getStepper", "getProductName", "getColumns"])
    },
    methods: {
        save: function() {
            var self = this
            var params = { columns: self.getColumns, product_name: self.getProductName }
            swal.queue([
                {
                    title: "Create New Product Type",
                    text: "Are you sure? All details is correct?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, save it",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post("/admin/products/add-product-type", params).then(result => {
                            swal.fire(result.data.heading, result.data.message, result.data.success ? "success" : "error")
                        })
                    }
                }
            ])
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
                    to: "/admin/products"
                },
                {
                    text: "Add Product Type",
                    disabled: true,
                    to: "add-product-type"
                }
            ]
        }
    }
}
</script>

<style></style>
