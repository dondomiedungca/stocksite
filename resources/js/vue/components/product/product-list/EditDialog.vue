<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(forEdit).length" style="width: 100%;">
            <v-card-title>Edit {{ forEdit.stock_number }}</v-card-title>
            <div>
                <v-row class="form-row">
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="status_selections" v-model="forEdit.inventory_status_id" label="Inventory Status" dense></v-select>
                    </v-col>
                    <v-col lg="4" md="4" sm="6" xs="12">
                        <v-select outlined :items="cosmetic_selections" v-model="forEdit.inventory_cosmetic_id" label="Item Cosmetic" dense></v-select>
                    </v-col>
                </v-row>
            </div>
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
    props: ["isEditOpen", "forEdit", "statuses", "cosmetics"],
    watch: {
        isEditOpen(data) {
            this.isOpen = data
        },
        statuses(data) {
            this.status_selections = data.map(status => {
                return {
                    text: status.name,
                    value: status.id
                }
            })
        },
        cosmetics(data) {
            this.cosmetic_selections = data.map(cosmetic => {
                return {
                    text: cosmetic.name,
                    value: cosmetic.id
                }
            })
        }
    },
    data() {
        return {
            isOpen: false,
            status_selections: [],
            cosmetic_selections: []
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

<style>
.form-row {
    margin: 7px 1%;
}
</style>
