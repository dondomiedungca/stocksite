<template>
    <div class="app-bar-container overflow-hidden">
        <v-app-bar height="50">
            <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>mdi-heart</v-icon>
            </v-btn>

            <v-btn icon>
                <v-icon>mdi-magnify</v-icon>
            </v-btn>

            <v-menu left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="n in 5" :key="n" @click="() => {}">
                        <v-list-item-title>Option {{ n }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-navigation-drawer v-model="drawer" absolute temporary>
            <v-list nav dense>
                <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Home</v-list-item-title>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-account</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Account</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            drawer: false,
            group: null
        }
    }
}
</script>

<style></style>
