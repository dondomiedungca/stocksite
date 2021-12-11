<template>
    <Container title="IMPORT PRODUCT" icon="mdi-file-import" :breadCrumbs="breadCrumbs">
        <Tab :navs="navs">
            <template v-slot:manually>
                <ImportManually basis="manual" />
            </template>
            <template v-slot:file>
                <ImportViaFile basis="manual" />
            </template>
        </Tab>
    </Container>
</template>

<script>
import ImportManually from "./ImportManually"
import ImportViaFile from "./ImportViaFile"
import Container from "./../../reusable/Container"
import Tab from "./../../reusable/Tab"

import { breadCrumbs } from "./assets/constant"
import { mapMutations } from "vuex"

export default {
    components: {
        ImportManually,
        ImportViaFile,
        Container,
        Tab
    },
    props: {
        cosmetics: {
            required: true,
            type: Array
        },
        statuses: {
            required: true,
            type: Array
        },
        product_types: {
            required: true,
            type: Array
        },
        stock_number: {
            required: true,
            type: String
        }
    },
    created() {
        this.breadCrumbs = breadCrumbs
    },
    data() {
        return {
            breadCrumbs: [],
            navs: [
                {
                    key: "manually",
                    text: "Import Manually"
                },
                {
                    key: "file",
                    text: "Import Via CSV/XLSX"
                }
            ]
        }
    },
    methods: {
        ...mapMutations("product_import", ["SET_REQUIRED_DETAILS"])
    },
    mounted() {
        const data = {
            cosmetics: this.cosmetics,
            statuses: this.statuses,
            stock_number: this.stock_number,
            product_types: this.product_types
        }
        this.SET_REQUIRED_DETAILS(data)
    }
}
</script>

<style></style>
