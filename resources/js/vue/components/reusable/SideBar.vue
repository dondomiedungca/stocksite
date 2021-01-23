<template>
    <v-card class="sidebar-container" height="100vh" width="250">
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
                            <v-icon color="primary">{{ item.icon }}</v-icon>
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
                { title: "Dashboard", icon: "mdi-chart-bar", url: "/admin/dashboard" },
                { title: "Purchase Order", icon: "mdi-receipt", url: "/admin/purchasing" },
                { title: "Sales Transaction", icon: "mdi-cash-multiple", url: "/admin/sales" },
                { title: "Products", icon: "mdi-warehouse", url: "/admin/products" },
                { title: "Customers", icon: "mdi-account-group-outline", url: "/admin/customers" },
                { title: "Reports", icon: "mdi-file-chart", url: "/admin/reports" },
                { title: "Settings", icon: "mdi-cogs", url: "/admin/settings" }
            ]
        }
    }
}
</script>

<style></style>
