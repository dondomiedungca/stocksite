<template>
    <div class="app-bar-container overflow-hidden">
        <v-app-bar flat fixed height="50">
            <v-app-bar-nav-icon class="appbar-toggle" @click="drawer = true"></v-app-bar-nav-icon>
            <v-app-bar-nav-icon id="appbar-toggle-default" class="appbar-toggle-default" @click="toggleDefault"></v-app-bar-nav-icon>

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-badge left color="primary" content="6">
                    <v-icon>mdi-bell</v-icon>
                </v-badge>
            </v-btn>

            <v-menu left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-account-details</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="(navigation, i) in navs" :key="i" :to="navigation.url">
                        <v-list-item-icon>
                            <v-icon size="20">{{ navigation.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>{{ navigation.title }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item class="tile" style="cursor: pointer;">
                        <v-list-item-icon>
                            <v-icon size="20">mdi-logout</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-navigation-drawer v-model="drawer" fixed temporary>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">
                        Company Name
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>
            <v-list nav dense>
                <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
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
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
export default {
    watch: {
        $route(to, from) {
            this.checkIfToggled()
        }
    },
    data: () => {
        return {
            drawer: false,
            group: null,
            navs: [
                {
                    title: "Account Details",
                    icon: "mdi-cogs",
                    url: "/admin/account",
                    isLogout: false
                }
            ],
            items: [
                { title: "Dashboard", icon: "mdi-chart-bar", url: "/admin/dashboard" },
                { title: "Purchase Order", icon: "mdi-receipt", url: "/admin/purchasing" },
                { title: "Sales Transaction", icon: "mdi-cash-multiple", url: "/admin/sales" },
                { title: "Products", icon: "mdi-warehouse", url: "/admin/products" },
                { title: "Suppliers & Receivers", icon: "mdi-account-group", url: "/admin/supplier-and-receiver-list" },
                { title: "Settings", icon: "mdi-cogs", url: "/admin/settings" }
            ]
        }
    },
    methods: {
        toggleDefault() {
            var element = document.getElementById("sidebar") // this id is from Sidebar.vue to toggle close the sidebar
            var toggler = document.getElementById("appbar-toggle-default")
            var v_main = document.getElementById("v-main")
            element.classList.toggle("sidebar-close-default")
            toggler.classList.toggle("toggler-sidebar-close-default")
            v_main.classList.toggle("v_main-sidebar-close-default")
        },
        checkIfToggled() {
            var toggler = document.getElementById("appbar-toggle-default")
            if (toggler.classList.contains("toggler-sidebar-close-default")) {
                var v_main = document.getElementById("v-main")
                v_main.classList.toggle("v_main-sidebar-close-default")
            }
        }
    }
}
</script>

<style></style>
