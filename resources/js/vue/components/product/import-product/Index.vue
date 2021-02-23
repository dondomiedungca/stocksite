<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <v-tabs color="#000" slider-size="4" align-with-title fixed-tabs slider-color="blue accent-3">
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
                    to: "/admin/products",
                    icon: "mdi-warehouse"
                },
                {
                    text: "Import Product",
                    disabled: true,
                    to: "import-product",
                    icon: "mdi-file-import"
                }
            ],
            tab: null
        }
    }
}
</script>

<style></style>
