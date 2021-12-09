<template>
    <v-row>
        <v-col v-for="(m, i) in modules" :key="i" lg="3" md="4" sm="12" xs="12">
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-card v-bind="attrs" v-on="on" elevation="2">
                        <v-list-item :href="m.url">
                            <v-list-item-icon>
                                <v-icon color="#000" v-text="m.icon"></v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ m.title }}
                                </v-list-item-title>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>
                                <small class="font-weight-light">
                                    {{ m.description }}
                                </small>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card>
                </template>
                <span>{{ m.title }}</span>
            </v-tooltip>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: {
        modules: {
            required: true,
            type: Array
        }
    }
}
</script>

<style></style>
