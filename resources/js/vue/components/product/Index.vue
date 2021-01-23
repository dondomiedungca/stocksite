<template>
    <v-main class="main-container">
        <breadcrumbs-vue :items="items"></breadcrumbs-vue>
        <v-container>
            <v-row>
                <v-col lg="12"> </v-col>
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
                    text: "Product",
                    disabled: true
                }
            ]
        }
    }
}
</script>

<style></style>
