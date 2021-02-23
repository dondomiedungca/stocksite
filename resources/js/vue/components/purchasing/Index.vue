<template>
    <v-main id="v-main">
        <breadcrumbs-vue :items="items"></breadcrumbs-vue>
        <v-container fluid>
            <v-row>
                <v-col v-for="(menu, i) in menus" :key="i" lg="3" md="4" sm="12" xs="12">
                    <menucard-vue :url="menu.url" :icon="menu.icon" :title="menu.title" :description="menu.description"></menucard-vue>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>

<script>
export default {
    data() {
        return {
            items: [
                {
                    text: "Purchasing",
                    disabled: true,
                    icon: "mdi-receipt"
                }
            ],
            menus: [
                {
                    url: "/admin/purchasing/purchasing-order",
                    icon: "mdi-pencil",
                    title: "Create New Purchasing",
                    description: "Create new purchase order that can receive items from your suppliers."
                },
                {
                    url: "/admin/purchasing/purchasing-list",
                    icon: "mdi-format-list-text",
                    title: "Purchasing List",
                    description: "Manage the list of purchasing orders, can modified their statuses (Delivered, Completion, Payments)."
                }
            ]
        }
    }
}
</script>

<style></style>
