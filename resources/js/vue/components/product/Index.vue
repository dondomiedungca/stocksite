<template>
    <v-main class="main-container">
        <breadcrumbs-vue :items="items"></breadcrumbs-vue>
    </v-main>
</template>

<script>
export default {
    data() {
        return {
            items: [
                {
                    text: "Dashboard",
                    disabled: false,
                    href: "breadcrumbs_dashboard"
                },
                {
                    text: "Link 1",
                    disabled: false,
                    href: "breadcrumbs_link_1"
                },
                {
                    text: "Link 2",
                    disabled: true,
                    href: "breadcrumbs_link_2"
                }
            ]
        }
    }
}
</script>

<style></style>
