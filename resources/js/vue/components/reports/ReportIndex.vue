<template>
    <v-main id="v-main">
        <breadcrumbs-vue :items="items"></breadcrumbs-vue>
        <v-container>
            <v-row>
                <v-col v-for="(menu, i) in menus" :key="i" lg="4" md="6" sm="12" xs="12">
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
                    text: "Reports",
                    disabled: true
                }
            ],
            menus: [
                {
                    url: "/admin/reports/queue-management",
                    icon: "mdi-tray-full",
                    title: "Queue Monitoring",
                    description: "Monitoring system queue including File Uploading, Email Users, and etc. Show what happens on your system background."
                }
            ]
        }
    }
}
</script>

<style></style>
