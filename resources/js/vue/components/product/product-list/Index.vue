<template>
    <Container title="Product List" icon="mdi-view-list" :breadCrumbs="breadCrumbs">
        <template v-slot:custom-nav>
            <Search />
        </template>
        <Table :headers="headers" :load="fetchData" :initLoad="willLoad">
            <template v-slot:[`item.actions`]="{ item }">
                <ViewDetails :details="item.details" />
                <Edit :product_type="product_type" :cosmetics="cosmetics_options" :statuses="statuses_options" :forEdit="item" />
                <Asker icon_header_color="red" :tooltip_show="false" icon_header="mdi-alert" icon :fab="false" title="Remove this Purchase Order?" color="grey darken-1" @proceed="removeProduct(item)">
                    <template v-slot:togglerIcon>
                        <v-icon>
                            mdi-trash-can
                        </v-icon>
                    </template>
                </Asker>
            </template>
            <template v-slot:[`item.product_type`]="{ item }">
                {{ item.product_type.product_name }}
            </template>
            <template v-slot:[`item.status`]="{ item }">
                {{ item.status.name }}
            </template>
            <template v-slot:[`item.cosmetic`]="{ item }">
                {{ item.cosmetic.name }}
            </template>
            <template v-slot:[`item.origin_price`]="{ item }">
                {{ item.cosmetic.origin_price | emptyFormatter }}
            </template>
            <template v-slot:[`item.selling_price`]="{ item }">
                {{ item.cosmetic.selling_price | emptyFormatter }}
            </template>
            <template v-slot:[`item.discount_percentage`]="{ item }">
                {{ item.cosmetic.discount_percentage | emptyFormatter }}
            </template>
        </Table>
    </Container>
</template>

<script>
import Edit from "./components/Edit"
import ViewDetails from "./components/View"
import Search from "./components/Search"

import { mapMutations, mapActions, mapGetters } from "vuex"

import Table from "./../../reusable/Table/complexHeaders"
import BreadCrumbs from "../../reusable/BreadCrumbs"
import Container from "../../reusable/Container"
import Asker from "./../../reusable/Asker"

import { breadCrumbs } from "./assets/constants"
import headers from "./assets/headers"

export default {
    props: {
        product_types: {
            required: true,
            type: Array
        },
        currency: {
            required: true,
            type: Object
        },
        statuses: {
            required: true,
            type: Array
        },
        cosmetics: {
            required: true,
            type: Array
        }
    },
    components: {
        Search,
        Table,
        BreadCrumbs,
        Container,
        Asker,
        Edit,
        ViewDetails
    },
    created() {
        this.breadCrumbs = breadCrumbs
        this.headers = headers
    },
    data() {
        return {
            breadCrumbs: [],
            headers: [],
            willLoad: false
        }
    },
    computed: {
        ...mapGetters("product_list", ["cosmetics_options", "product_type", "statuses_options"])
    },
    methods: {
        ...mapActions("product_list", ["fetchData", "remove"]),
        ...mapMutations("product_list", ["SET_REQUIRED_DATAS"]),
        removeProduct(item) {
            this.remove(item)
        }
    },
    mounted() {
        let requires = {
            product_types: this.product_types,
            currency: this.currency,
            cosmetics: this.cosmetics,
            statuses: this.statuses
        }
        this.SET_REQUIRED_DATAS(requires)
        this.willLoad = true
    }
}
</script>

<style></style>
