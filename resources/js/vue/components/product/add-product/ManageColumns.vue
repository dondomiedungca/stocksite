<template>
    <div>
        <v-form ref="form" v-model="valid">
            <v-dialog v-model="dialog" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" dark v-bind="attrs" v-on="on">
                        <v-icon left>
                            mdi-pencil
                        </v-icon>
                        Create Column
                    </v-btn>
                </template>
                <v-card style="width: 100%;">
                    <v-card-title>New Column</v-card-title>
                    <v-container class="card-container">
                        <v-row>
                            <v-col lg="12" md="12" sm="12">
                                <v-text-field dense class="mt-3" :rules="columnNameRules" :hint="'Specification of your product type (ex: serial_number, model_number, warranty_days, etc.)'" :label="'Column Name'" outlined v-model="columnName" type="text"></v-text-field>
                            </v-col>
                            <v-col lg="12" md="12" sm="12">
                                <v-select :rules="columnNameRules" v-model="dataType" :items="data_types" label="Data Type" dense outlined></v-select>
                            </v-col>
                            <v-col lg="12" md="12" sm="12">
                                <v-radio-group row v-model="isRequired" mandatory>
                                    <v-radio label="Yes" :value="1"></v-radio>
                                    <v-radio label="No" :value="0"></v-radio>
                                </v-radio-group>
                            </v-col>
                            <v-col lg="12" md="12" sm="12" v-if="dataType != 'DATE'">
                                <v-radio-group row v-model="inputType" mandatory>
                                    <v-radio label="INPUT" :value="1"></v-radio>
                                    <v-radio label="SELECTION" :value="0"></v-radio>
                                </v-radio-group>
                                <v-text-field validate-on-blur @keyup.enter="addToSelections" @click:append="addToSelections" :append-icon="'mdi-plus-box-multiple'" v-if="inputType == 0" dense class="mt-3 mb-3" :rules="selectionRules" :hint="'Add a selection (ex: Selections for Mobile are \' Android, IOS \')'" :label="'Enter Selection'" outlined v-model="selection" type="text">
                                    <template v-if="selections.length" v-slot:append-outer>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn icon>
                                                    <v-icon v-on="on">
                                                        mdi-format-list-bulleted
                                                    </v-icon>
                                                </v-btn>
                                            </template>
                                            Show Selection
                                        </v-tooltip>
                                    </template>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="gray darken-1" text @click="dialog = false">
                            close
                        </v-btn>
                        <v-btn :disabled="!valid" color="green darken-1" text @click="importToColums">
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <snackbar-vue></snackbar-vue>
        </v-form>
        <v-row class="mt-3 mb-3">
            <v-col class="" lg="12" md="12" sm="12">
                <v-data-table :no-data-text="'No Columns Found'" :headers="headers" :items="getColumns" :items-per-page="5" class="elevation-1"></v-data-table>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"

export default {
    data() {
        return {
            dialog: false,
            headers: [
                {
                    text: "Column Name",
                    align: "start",
                    sortable: false,
                    value: "column_name",
                    class: "primary white--text"
                },
                { text: "Data Type", value: "data_type", align: "start", sortable: false, class: "primary white--text" },
                { text: "Is Required", value: "is_required", align: "start", sortable: false, class: "primary white--text" },
                { text: "Value Type", value: "value_type", align: "start", sortable: false, class: "primary white--text" },
                { text: "Selections", value: "selections", align: "start", sortable: false, class: "primary white--text" }
            ],
            items: this.getColumns,
            valid: false,
            columnNameRules: [value => !!value || "This is required", value => this.getColumnNames.indexOf(value) == -1 || "Column name is already exists"],
            selectionRules: [value => !!this.selections.length || "This is required, add a selection(s) and click the plus button or press enter"],
            columnName: "",
            dataType: "",
            isRequired: 1,
            inputType: 1,
            selection: "",
            selections: [],
            data_types: ["STRING", "INTEGER", "DATE"]
        }
    },
    computed: {
        ...mapGetters(["getColumns", "getColumnNames"])
    },
    methods: {
        ...mapMutations(["setStepper", "setSnackbar", "addToColumns"]),
        testProductName(val) {
            if (val) {
                this.setStepper({
                    canContinue: true
                })
            } else if (this.getColumns.length) {
                this.setStepper({
                    canContinue: true
                })
            } else {
                this.setStepper({
                    canContinue: false
                })
            }
        },
        addToSelections: function() {
            if (this.selection != "") {
                this.selections.push(this.selection)
                this.selection = ""
                this.setSnackbar({
                    isVisible: true,
                    color: "#404040",
                    text: "New selection added!"
                })
            }
        },
        importToColums: function() {
            // create column candidate
            let new_column = {
                column_name: this.columnName,
                data_type: this.dataType,
                is_required: this.isRequired,
                value_type: this.inputType,
                selections: this.selections
            }

            this.addToColumns(new_column)
            this.setSnackbar({
                isVisible: true,
                color: "#5cb85c",
                text: "New column was added!"
            })
            this.$refs.form.reset()
        }
    },
    watch: {
        valid: function(newVal, oldVal) {
            if (this.$store.state.columns.length) {
                this.testProductName(newVal)
            }
        },
        selections: function() {
            this.$refs.form.validate()
        }
    }
}
</script>

<style></style>
