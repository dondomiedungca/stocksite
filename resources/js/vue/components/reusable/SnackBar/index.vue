<template>
    <v-snackbar transition="fab-transition" color="dark" vertical v-model="getSnackbar.isVisible" bottom :timeout="getSnackbar.timeout" multi-line>
        <v-layout align-center pr-4>
            <v-icon v-if="getSnackbar.type == 'success'" class="pr-3" large color="success">mdi-check</v-icon>
            <v-icon v-if="getSnackbar.type == 'warning'" class="pr-3" large color="orange">mdi-alert-circle-outline</v-icon>
            <v-icon v-if="getSnackbar.type == 'info'" class="pr-3" large color="primary">mdi-information</v-icon>
            <v-icon v-if="getSnackbar.type == 'error'" class="pr-3" large color="red">mdi-alert</v-icon>
            <v-layout column>
                <div>
                    <strong v-if="getSnackbar.type == 'success'">Well Done!</strong>
                    <strong v-else-if="getSnackbar.type == 'warning'">Warning Alert</strong>
                    <strong v-else-if="getSnackbar.type == 'info'">information Alert</strong>
                    <strong v-else>Something Went Wrong</strong>
                </div>
                <div v-html="getSnackbar.text"></div>
            </v-layout>
        </v-layout>

        <template v-slot:action="{ attrs }">
            <v-btn small color="white" text v-bind="attrs" @click="close"> <v-icon>mdi-close</v-icon>Close </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"

export default {
    data() {
        return {
            snackbar: false
        }
    },
    watch: {
        getVisibility(newVal) {
            if (!newVal) {
                this.SET_SNACKBAR({
                    text: "",
                    isVisible: false
                })
            }
        }
    },
    computed: {
        ...mapGetters("snackBar", ["getSnackbar", "getVisibility"])
    },
    methods: {
        ...mapMutations("snackBar", ["SET_SNACKBAR"]),
        close: function() {
            this.SET_SNACKBAR({
                text: "",
                isVisible: false,
                color: "#5cb85c"
            })
        }
    }
}
</script>

<style></style>
