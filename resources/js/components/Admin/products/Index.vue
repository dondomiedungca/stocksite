<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Manage Purchasing</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <h2>Navigations</h2>
        <br />
        <div class="row">
            <div v-for="(menu, i) in menus" v-bind:key="i" class="menu col-md-4" @click="go(menu.url)">
                <div class="card bg-light mb-3">
                    <div class="card-header">{{ menu.title }}</div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <i :class="menu.icon"></i>
                        </h5>
                        <p class="card-text">{{ menu.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menus: [
                {
                    url: "/admin/products/add-product-type",
                    icon: "fa fa-edit",
                    title: "Create New Product Type",
                    description: "Add new product types including the important columns that will hold the attributes/specifications of the product."
                },
                {
                    url: "/admin/products/product-list",
                    icon: "fa fa-list",
                    title: "Product List",
                    description: "Show the entire inventories, manage by the product types."
                },
                {
                    url: "/admin/products/product-import",
                    icon: "fa fa-dropbox",
                    title: "Import Product",
                    description: "Import items base on their product type, import the items via CSV, XLSX or manually."
                }
            ]
        }
    },
    methods: {
        go: function(url) {
            window.location.href = url
        }
    }
}
</script>

<style>
.menu {
    cursor: pointer;
}
</style>
