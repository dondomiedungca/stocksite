<template>
    <v-form ref="form" v-model="valid">
        <v-row>
            <v-col v-if="valid" lg="12" md="12" sm="12">
                <v-btn rounded tile color="primary">
                    <v-icon left>
                        mdi-check
                    </v-icon>
                    Add Column
                </v-btn>
            </v-col>
            <v-col lg="3" md="3" sm="6">
                <v-text-field dense class="mt-3 mb-3" :rules="columnNameRules" :hint="'Specification of your product type (ex: serial_number, model_number, warranty_days, etc.)'" :label="'Column Name'" outlined v-model="columnName" type="text"></v-text-field>
            </v-col>
            <v-col lg="3" md="3" sm="6">
                <v-select class="mt-3 mb-3" :rules="columnNameRules" v-model="dataType" :items="data_types" label="Data Type" dense outlined></v-select>
            </v-col>
            <v-col lg="2" md="2" sm="5">
                <v-radio-group row v-model="isRequired" mandatory>
                    <v-radio label="Yes" :value="1"></v-radio>
                    <v-radio label="No" :value="0"></v-radio>
                </v-radio-group>
            </v-col>
            <v-col v-if="dataType != 'DATE'" lg="4" md="4" sm="7">
                <v-radio-group row v-model="inputType" mandatory>
                    <v-radio label="INPUT" value="INPUT"></v-radio>
                    <v-radio label="SELECTION" value="SELECTION"></v-radio>
                </v-radio-group>
                <v-text-field validate-on-blur @click:append="addToSelections" :append-icon="'mdi-plus-box-multiple'" v-if="inputType == 'SELECTION'" dense class="mt-3 mb-3" :rules="selectionRules" :hint="'Add a selection (ex: Selections for Mobile are \' Android, IOS \')'" :label="'Enter Selection'" outlined v-model="selection" type="text">
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
        <snackbar-vue></snackbar-vue>
    </v-form>
</template>

<script>
import { mapGetters } from "vuex"
import { mapMutations } from "vuex"

export default {
    data() {
        return {
            valid: false,
            columnNameRules: [value => !!value || "This is required"],
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
    methods: {
        ...mapMutations(["setStepper", "setSnackbar"]),
        testProductName(val) {
            if (val) {
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
                    text: "New selection added!"
                })
            }
        }
    },
    watch: {
        valid: function(newVal, oldVal) {
            if (this.$store.state.columns.length) {
                this.testProductName(newVal)
            }
        }
    }
}
</script>

<style></style>
