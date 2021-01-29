<template>
    <v-snackbar v-model="getSnackbar.isVisible" bottom :timeout="getSnackbar.timeout">
        {{ getSnackbar.text }}

        <template v-slot:action="{ attrs }">
            <v-btn color="blue" text v-bind="attrs" @click="close">
                Close
            </v-btn>
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
        "$store.state.snackbar.isVisible": function(newVal) {
            if (!newVal) {
                this.setSnackbar({
                    text: "",
                    isVisible: false
                })
            }
        }
    },
    computed: {
        ...mapGetters(["getSnackbar"])
    },
    methods: {
        ...mapMutations(["setSnackbar"]),
        close: function() {
            this.setSnackbar({
                text: "",
                isVisible: false
            })
        }
    }
}
</script>

<style></style>
