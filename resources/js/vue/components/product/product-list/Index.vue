<template>
    <Container title="Product List" icon="mdi-view-list" :breadCrumbs="breadCrumbs">
        <!-- <v-row>
            <v-col lg="3">
                <v-select outlined :items="product_type_selections" v-model="selected_product_type_id" label="Inventory Type" dense></v-select>
            </v-col>
            <v-col lg="3">
                <v-btn small class="mt-1" color="dark" @click="isSearchOpen = true"> <v-icon>mdi-feature-search-outline</v-icon> Advance Search </v-btn>
                <search-dialog @search="search" :currency="currency" :inventory_status_selections="inventory_status_selections" :inventory_cosmetic_selections="inventory_cosmetic_selections" :product_type="product_type" :isOpen="isSearchOpen" @close="isSearchOpen = false"></search-dialog>
            </v-col>
        </v-row> -->
        <Table :headers="headers" :load="fetchData" :initLoad="willLoad">
            <template v-slot:[`item.actions`]="{ item }">
                <Edit :product_type="product_type" :cosmetics="cosmetics_options" :statuses="statuses_options" :forEdit="item" />
                <Asker icon_header_color="red" :tooltip_show="false" icon_header="mdi-alert" :icon="true" tooltip_message="Remove" :fab="false" title="Remove this Purchase Order?" color="grey darken-1" @proceed="removePurchasing(item.id)">
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
        </Table>
        <!-- <edit-dialog :product_type="product_type" :cosmetics="cosmetics" :statuses="statuses" :forEdit="forEdit" @close="isEditOpen = !isEditOpen" :isEditOpen="isEditOpen"></edit-dialog>
        <view-specs :details="details" @close="isViewOpen = !isViewOpen" :isViewOpen="isViewOpen"></view-specs>
        <v-dialog v-model="isShowDescription" width="500">
            <v-card>
                <v-card-title class="headline grey lighten-2"> {{ item_all_description.item_name }} - {{ item_all_description.item_column_name }} </v-card-title>
                <v-card-text class="mt-3">
                    {{ item_all_description.description }}
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="isShowDescription = false">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog> -->
    </Container>
</template>

<script>
import EditDialog from "./EditDialog"
import Edit from "./components/Edit"

import SearchDialog from "./SearchDialog"
import ViewSpecs from "./ViewSpecs"
import { mapMutations, mapActions, mapGetters, mapState } from "vuex"

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
        EditDialog,
        ViewSpecs,
        SearchDialog,
        Table,
        BreadCrumbs,
        Container,
        Asker,
        Edit
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
        ...mapActions("product_list", ["fetchData"]),
        ...mapMutations("product_list", ["SET_REQUIRED_DATAS"])
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
