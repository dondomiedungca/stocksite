<template>
    <v-breadcrumbs :items="items">
        <template v-slot:divider>
            <v-icon>mdi-chevron-right</v-icon>
        </template>
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item :href="item.to" :disabled="item.disabled">
                <v-icon :color="!item.disabled ? 'primary' : 'secondary'" style="margin-right: 10px; margin-top: -3px;">{{ item.icon }}</v-icon
                >{{ item.text }}
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
