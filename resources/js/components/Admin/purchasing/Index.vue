<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Manage Purchasing</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button type="button" @click="showSupplier" class="btn btn-sm btn-outline-secondary">
                        Add Supplier
                    </button>
                    <modal ref="addsupplier" :keyId="'addSupplier'" :title="'Add Supplier'" :show_submit="true" :icon="'fa fa-check'" :submit_name="'Add Supplier'" @met="saveSupplier">
                        <add-supplier ref="addsupplierpage"></add-supplier>
                    </modal>
                </div>
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
                    url: "/admin/purchasing/purchasing-order",
                    icon: "fa fa-edit",
                    title: "Create Purchase Order",
                    description: "Create new purchase order that can receive items from your suppliers or even without a supplier."
                },
                {
                    url: "/admin/purchasing/purchasing-list",
                    icon: "fa fa-list",
                    title: "Purchasing List",
                    description: "Manage the list of purchasing orders, can modified their statuses (Delivered, Completion, Payments)."
                }
            ]
        }
    },
    methods: {
        go: function(url) {
            window.location.href = url
        },
        showSupplier: function() {
            this.$refs.addsupplier.trigger()
        },
        saveSupplier: function(data) {
            this.$refs.addsupplierpage.createSupplier()
        }
    }
}
</script>

<style>
.menu {
    cursor: pointer;
}
</style>
