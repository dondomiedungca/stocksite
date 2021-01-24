<template>
    <v-breadcrumbs :items="items">
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item :to="item.to" :exact="item.exact" :disabled="item.disabled">
                {{ item.text.toUpperCase() }}
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>
</template>

<script>
export default {
    props: ["items"]
}
</script>

<style></style>
