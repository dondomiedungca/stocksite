<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <template v-slot:activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" v-on="on">
                <v-icon>
                    mdi-eye
                </v-icon>
            </v-btn>
        </template>
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
                                <td>{{ details[key] }}</td>
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
    props: ["details"],
    data() {
        return {
            keys: [],
            isOpen: false
        }
    },
    methods: {
        sorting: function() {
            this.keys = Object.keys(this.details).sort()
        },
        close: function() {
            this.isOpen = !this.isOpen
            this.$emit("close")
        }
    },
    created() {
        this.sorting()
    }
}
</script>

<style>
.form-row {
    margin: 7px 2%;
}
</style>
