<template>
    <v-main id="v-main">
        <BreadCrumbs :title="title" :icon="icon" :items="breadCrumbs">
            <template v-slot:custom-nav>
                <slot name="custom-nav"></slot>
            </template>
        </BreadCrumbs>
        <v-container fluid>
            <slot></slot>
        </v-container>
    </v-main>
</template>

<script>
import BreadCrumbs from "./../BreadCrumbs"

export default {
    props: {
        title: {
            required: true,
            type: String
        },
        icon: {
            required: true,
            type: String
        },
        breadCrumbs: {
            required: true,
            type: Array
        }
    },
    components: {
        BreadCrumbs
    }
}
</script>

<style></style>
