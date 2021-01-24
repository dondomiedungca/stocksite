<template>
    <v-main>
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
                    text: "Product",
                    disabled: true
                }
            ],
            menus: [
                {
                    url: "/admin/products/add-product-type",
                    icon: "mdi-pencil",
                    title: "Create New Product Type",
                    description: "Add new product types including the important columns that will hold the attributes/specifications of the product."
                },
                {
                    url: "/admin/products/product-list",
                    icon: "mdi-format-list-text",
                    title: "Product List",
                    description: "Show the entire inventories, managed by the product types."
                },
                {
                    url: "/admin/products/product-import",
                    icon: "mdi-application-import",
                    title: "Import Product",
                    description: "Import item(s) base on their product type, import the items via CSV, XLSX or manually."
                }
            ]
        }
    }
}
</script>

<style></style>
