<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(details).length" style="width: 100%;">
            <v-card-title>View Specification</v-card-title>
            <div class="form-row">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">
                                    Full Specification
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(key, i) in keys" v-bind:key="i">
                                <td>{{ key }}</td>
                                <td>{{ full_details[key] }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="dark" small @click="close"> <v-icon>mdi-close</v-icon>close </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["details", "isViewOpen"],
    data() {
        return {
            full_details: {},
            keys: [],
            isOpen: false
        }
    },
    watch: {
        details: function(newVal, oldVal) {
            this.full_details = newVal
            this.sorting()
        },
        isViewOpen(data) {
            this.isOpen = data
        }
    },
    methods: {
        sorting: function() {
            this.keys = Object.keys(this.full_details).sort()
        },
        close: function() {
            this.isOpen = !this.isOpen
            this.$emit("close")
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 2%;
}
</style>
