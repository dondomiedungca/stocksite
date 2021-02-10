<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <v-tabs fixed-tabs color="primary">
                            <v-tab href="#hello">
                                Import Manually
                            </v-tab>
                            <v-tab href="#world">
                                Import Via CSV / XLSX
                            </v-tab>
                            <v-tab-item id="hello">
                                <v-card color="basil" flat>
                                    <import-manually :basis="'manual'"></import-manually>
                                </v-card>
                            </v-tab-item>
                            <v-tab-item id="world">
                                <v-card color="basil" flat>
                                    <import-via-file :basis="'manual'"></import-via-file>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import ImportManually from "./ImportManually"
import ImportViaFile from "./ImportViaFile"
export default {
    components: {
        ImportManually,
        ImportViaFile
    },
    computed: {},
    methods: {},
    data() {
        return {
            items: [
                {
                    text: "Products",
                    disabled: false,
                    exact: true,
                    to: "/admin/products"
                },
                {
                    text: "Import Product",
                    disabled: true,
                    to: "import-product"
                }
            ],
            tab: null
        }
    }
}
</script>

<style></style>
