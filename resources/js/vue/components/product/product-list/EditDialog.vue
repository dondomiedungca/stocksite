<template>
    <v-dialog v-model="isOpen" persistent max-width="350px">
        <v-card style="width: 100%;">
            <v-card-title></v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="gray darken-1" text @click="close">
                    close
                </v-btn>
                <v-btn color="green darken-1" text @click="updateItem">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["isEditOpen"],
    watch: {
        isEditOpen(data) {
            this.isOpen = data
        }
    },
    data() {
        return {
            isOpen: false
        }
    },
    methods: {
        updateItem: function() {},
        close: function() {
            ;(this.isOpen = !this.isOpen), this.$emit("close")
        }
    }
}
</script>

<style></style>
