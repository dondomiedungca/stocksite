<template>
    <div>
        <v-main>
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container>
                <v-row>
                    <v-col lg="12">
                        <stepper-vue :step_count="3" :headers="headers">
                            <template v-slot:step_content_1>
                                <add-product-type></add-product-type>
                            </template>
                            <template v-slot:step_content_2>
                                <manage-columns></manage-columns>
                            </template>
                            <template v-slot:step_content_3>
                                <h1>Hello 3</h1>
                            </template>
                        </stepper-vue>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import AddProductName from "./AddProductType.vue"
import ManageColumns from "./ManageColumns.vue"

export default {
    components: {
        "add-product-type": AddProductName,
        "manage-columns": ManageColumns
    },
    data() {
        return {
            headers: [
                {
                    step_name: "Product Type",
                    step_level: 1
                },
                {
                    step_name: "Manage Column(s)",
                    step_level: 2
                },
                {
                    step_name: "Verify",
                    step_level: 3
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
