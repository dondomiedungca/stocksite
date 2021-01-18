<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Manage Purchasing</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <bread-crumbs :urls="urls"></bread-crumbs>
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
            urls: [
                {
                    name: "Reports",
                    active: true
                }
            ],
            menus: [
                {
                    url: "/admin/reports/queue-management",
                    icon: "lni lni-ticket-alt",
                    title: "Queue Lists",
                    description: "Monitoring system queue including File Uploading, Email Users, and etc. Show what happens on your system background."
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
