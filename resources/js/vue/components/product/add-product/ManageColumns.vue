<template>
    <div>
        <v-form ref="form" v-model="valid">
            <v-dialog v-model="dialog" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn small color="dark" v-bind="attrs" v-on="on">
                        <v-icon left>
                            mdi-pencil
                        </v-icon>
                        Create Column
                    </v-btn>
                </template>
                <v-card style="width: 100%;">
                    <v-card-title class="ml-2">CREATE NEW COLUMN</v-card-title>
                    <v-container class="card-container">
                        <v-row class="form-row">
                            <v-col cols="6">
                                <v-text-field dense :rules="columnNameRules" :hint="'Specification of your product type (ex: serial_number, model_number, warranty_days, etc.)'" :label="'Column Name'" outlined v-model="columnName" type="text"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select :rules="columnNameRules" v-model="dataType" :items="data_types" label="Data Type" dense outlined></v-select>
                            </v-col>
                            <v-col class="custom-no-padding-bt" cols="12">
                                <v-radio-group row v-model="isRequired" mandatory>
                                    <template v-slot:label>
                                        <div><strong>Required</strong></div>
                                    </template>
                                    <v-radio label="Yes" :value="1"></v-radio>
                                    <v-radio label="No" :value="0"></v-radio>
                                </v-radio-group>
                            </v-col>
                            <v-col class="custom-no-padding-bt" cols="12" v-if="dataType != 'DATE'">
                                <v-radio-group row v-model="inputType" mandatory>
                                    <template v-slot:label>
                                        <div><strong>Input Value Type</strong></div>
                                    </template>
                                    <v-radio label="INPUT" :value="1"></v-radio>
                                    <v-radio label="SELECTION" :value="0"></v-radio>
                                </v-radio-group>
                                <v-text-field validate-on-blur @keyup.enter="addToSelections" @click:append="addToSelections" :append-icon="'mdi-plus-box-multiple'" v-if="inputType == 0" dense class="mt-3 mb-3" :rules="selectionRules" :hint="'Add a selection (ex: Selections for Mobile are \' Android, IOS \')'" :label="'Enter Selection'" outlined v-model="selection" type="text"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn small color="dark" @click="dialog = false"> <v-icon>mdi-close</v-icon>Close </v-btn>
                        <v-btn small :disabled="!valid" color="dark" @click="importToColums"> <v-icon>mdi-check</v-icon>Save </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-form>
        <v-row class="mt-3 mb-3">
            <v-col class="" lg="12" md="12" sm="12">
                <Table :headers="headers" height="40vh">
                    <template v-slot:[`item.actions`]="{ item }">
                        <Asker icon_header="mdi-alert" :icon="true" :fab="false" icon_header_color="red" :tooltip_show="false" title="Remove this column from your product type?" @proceed="removeColumn(item)" color="secondary">
                            <template v-slot:togglerIcon>
                                <v-icon color="#404040">
                                    mdi-delete
                                </v-icon>
                            </template>
                        </Asker>
                    </template>
                    <template v-slot:[`item.is_required`]="{ item }">
                        <span>{{ item.is_required == 1 ? "REQUIRED" : "NOT REQUIRED" }}</span>
                    </template>
                    <template v-slot:[`item.value_type`]="{ item }">
                        <span>{{ item.value_type == 1 ? "INPUT" : "SELECTION" }}</span>
                    </template>
                </Table>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"
import { mapActions, mapState } from "vuex"

import Asker from "./../../reusable/Asker"
import Table from "./../../reusable/Table"

import headers from "./assets/headers"

export default {
    components: {
        Asker,
        Table
    },
    data() {
        return {
            loading: false,
            dialog: false,
            headers: [],

            valid: false,
            columnNameRules: [value => !!value || "This is required", value => this.getColumnNames.indexOf(value) == -1 || "Column name is already exists", value => /^[a-zA-Z][A-Za-z0-9_]*$/im.test(value) || "Only alphanumeric and underscore is allowed"],
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
        ...mapGetters("add_product", ["getColumns", "getColumnNames"]),
        ...mapState("add_product", ["columns"])
    },
    created() {
        this.headers = headers
    },
    methods: {
        ...mapMutations("add_product", ["ADD_TO_COLUMNS"]),
        ...mapMutations("snackBar", ["SET_SNACKBAR"]),
        ...mapMutations("stepper", ["SET_STEPPER"]),
        ...mapActions("add_product", ["updateColumns", "setContentTable"]),
        testProductName(val) {
            if (val) {
                this.SET_STEPPER({
                    canFinish: true
                })
            } else if (this.getColumns.length) {
                this.SET_STEPPER({
                    canFinish: true
                })
            } else {
                this.SET_STEPPER({
                    canFinish: false
                })
            }
        },
        addToSelections: function() {
            if (this.selection != "" && this.columnName != null && this.dataType != null) {
                this.selections.push(this.selection)
                this.selection = ""
                this.SET_SNACKBAR({
                    isVisible: true,
                    type: "info",
                    text: "New selection was added!"
                })
            }
        },
        removeColumn: async function(item) {
            this.loading = true
            var setting = this.updateColumns(item)
            await setting
            this.loading = false
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

            this.ADD_TO_COLUMNS(new_column)
            this.setContentTable()
            this.SET_SNACKBAR({
                isVisible: true,
                type: "success",
                text: "New column was added!"
            })
            this.columnName = ""
            this.dataType = ""
            this.selections = []
            this.$refs.form.reset()
        }
    },
    watch: {
        valid: function(newVal, oldVal) {
            if (this.columns.length) {
                this.testProductName(newVal)
            }
        },
        selections: function() {
            this.$refs.form.validate()
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 1%;
}
</style>
