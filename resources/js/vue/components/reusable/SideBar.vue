<template>
    <v-card class="sidebar-container" style="position: fixed; left: 0; top: 0;" height="100vh" width="250">
        <v-navigation-drawer absolute permanent left>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">
                        Pro Prints Enterprise
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense nav>
                <v-list-item v-for="item in items" :key="item.title" :to="item.url">
                    <template>
                        <v-list-item-icon>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </template>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
    </v-card>
</template>

<script>
export default {
    data: () => {
        return {
            items: [
                { title: "Dashboard", icon: "mdi-view-dashboard", url: "/admin/dashboard" },
                { title: "Purchase Order", icon: "mdi-image", url: "/admin/purchasing" },
                { title: "Sales Transaction", icon: "mdi-help-box", url: "/admin/sales" },
                { title: "Products", icon: "mdi-help-box", url: "/admin/products" },
                { title: "Customers", icon: "mdi-help-box", url: "/admin/customers" },
                { title: "Reports", icon: "mdi-help-box", url: "/admin/reports" },
                { title: "Settings", icon: "mdi-help-box", url: "/admin/settings" }
            ]
        }
    }
}
</script>

<style></style>
