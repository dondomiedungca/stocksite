<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col lg="12">
                        <v-tabs background-color="blue lighten-2" color="#000" align-with-title fixed-tabs slider-color="secondary">
                            <v-tab href="#hello">
                                Supplier Page
                            </v-tab>
                            <v-tab href="#world">
                                Receiver Page
                            </v-tab>
                            <v-tab-item id="hello">
                                <v-card color="basil" flat>
                                    <supplier-page />
                                </v-card>
                            </v-tab-item>
                            <v-tab-item id="world">
                                <v-card color="basil" flat>
                                    <receiver-page />
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
import ReceiverPage from "./receiver/ReceiverPage.vue"
import SupplierPage from "./supplier_page/SupplierPage.vue"
export default {
    components: {
        ReceiverPage,
        SupplierPage
    },
    data() {
        return {
            items: [
                {
                    text: "Supplier Receiver Lists",
                    disabled: true
                }
            ]
        }
    }
}
</script>

<style></style>
