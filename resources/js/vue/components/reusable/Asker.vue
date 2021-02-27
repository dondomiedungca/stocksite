<template>
    <v-menu v-model="menu" nudge-bottom="10" nudge-right="50" :close-on-content-click="false" transition="slide-x-transition" bottom right>
        <template v-slot:activator="{ on: menu, attrs: attr1 }">
            <v-tooltip bottom>
                <template v-slot:activator="{ on: tooltip, attr2 }">
                    <v-btn :class="classes" :color="color" :small="small" :icon="icon" :fab="fab" v-bind="{ ...attr1, attr2 }" v-on="{ ...menu, ...tooltip }">
                        <slot name="togglerIcon"></slot>
                    </v-btn>
                </template>
                <span>{{ tooltip_message }}</span>
            </v-tooltip>
        </template>

        <v-card>
            <v-card-title
                ><v-icon :color="icon_header_color" large left> {{ icon_header }} </v-icon>
                <h6>{{ title }}</h6></v-card-title
            >
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" @click="menu = false" small text>
                    No
                </v-btn>
                <v-btn color="secondary" @click="proceed" small text>
                    Yes
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>
</template>

<script>
export default {
    props: {
        fab: {
            required: false,
            default: "fab"
        },
        small: {
            default: true,
            required: false
        },
        color: {
            required: false,
            default: "primary"
        },
        classes: {
            required: false,
            default: ""
        },
        icon_header: {
            required: false,
            default: "mdi-information"
        },
        icon_header_color: {
            required: false,
            default: "primary"
        },
        title: {
            required: true,
            default: null
        },
        tooltip_message: {
            required: true,
            default: null
        },
        icon: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            menu: false
        }
    },
    methods: {
        proceed() {
            this.$emit("proceed")
        }
    }
}
</script>

<style></style>
