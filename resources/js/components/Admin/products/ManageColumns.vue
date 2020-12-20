<template>
    <div>
        <div class="form-group" v-show="!pass">
            <small><b>NOTE*</b> These are the column that already exist on the server, meaning you should not use this column name(s)</small>
            <br />
            <small>price</small><br />
            <small>cost</small><br />
            <small>quantity</small><br />
            <small>discount</small><br />
            <br />
            <label for="exampleInputEmail1">Column Name</label>
            <input type="text" :class="{ 'is-invalid': $v.column_name.$error }" name v-model="$v.column_name.$model" @change="column_name = $event.target.value" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" />
            <div class="invalid-feedback" v-if="$v.column_name.$error">This field is required and only alpha is allowed</div>
        </div>
        <div class="form-group" v-show="!pass">
            <button class="btn btn-primary btn-sm" @click="makeColumn"><i class="fa fa-edit"></i> Make Column Type</button>
        </div>
        <div class="form-group" v-show="pass">
            <label for="exampleInputEmail1">Choose Data Type</label>
            <select :class="{ 'is-invalid': $v.dt.$error }" name v-model="$v.dt.$model" @change="dt = $event.target.value" class="form-control form-control-sm">
                <option v-for="(data_type, i) in data_types" v-bind:key="i">{{ data_type }}</option>
            </select>
            <div class="invalid-feedback" v-if="$v.dt.$error">This field is required and only alpha is allowed</div>
        </div>
        <div class="form-group" v-show="pass">
            <label for="">Is Manual Fillable</label>
            <br />
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions1" id="inlineRadio1" v-model="manual" value="YES" />
                <label class="form-check-label" for="inlineRadio1">YES</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="inlineRadio2" v-model="manual" value="NO" />
                <label class="form-check-label" for="inlineRadio2">NO</label>
            </div>
        </div>
        <div class="form-group" v-show="pass">
            <label for="">Is Required</label>
            <br />
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions3" id="inlineRadio3" v-model="required" value="YES" />
                <label class="form-check-label" for="inlineRadio3">YES</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions4" id="inlineRadio4" v-model="required" value="NO" />
                <label class="form-check-label" for="inlineRadio4">NO</label>
            </div>
        </div>
        <div class="form-group" v-show="pass && dt != 'DATE'">
            <label for="">Input Type</label>
            <br />
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions5" id="inlineRadio5" v-model="input_type" value="SELECTION" />
                <label class="form-check-label" for="inlineRadio5">SELECTION</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions6" id="inlineRadio6" v-model="input_type" value="INPUT" />
                <label class="form-check-label" for="inlineRadio6">INPUT</label>
            </div>
        </div>
        <div class="form-group" v-show="pass && input_type == 'SELECTION'">
            <label for="">Column Selections</label>
            <input type="text" class="form-control" id="exampleInputEmail1" v-model="selection_value" @keyup.enter="enter()" aria-describedby="emailHelp" />
            <small>Press enter key to add this selection value</small>
            <br />
            <br />
            <label for="">Selections for this column</label>
            <ul>
                <li v-for="(value_selection, i) in selection" v-bind:key="i">{{ value_selection }}</li>
            </ul>
        </div>
        <div class="form-group" v-show="pass">
            <button class="btn btn-primary btn-sm" @click="addColumn"><i class="fa fa-plus"></i> Add Column</button>
        </div>
    </div>
</template>

<script>
import { required, alpha } from "vuelidate/lib/validators"

const isColumn = value => /^[a-zA-Z0-9_]*$/im.test(value)

export default {
    data() {
        return {
            column_name: "",
            columns: [],
            data_types: ["STRING", "INTEGER", "DATE"],
            dt: "",
            pass: false,
            manual: "YES",
            required: "YES",
            input_type: "INPUT",
            selection: [],
            selection_value: ""
        }
    },
    validations: {
        column_name: {
            required,
            columnValid: isColumn
        },
        dt: {
            required
        }
    },
    methods: {
        makeColumn: function() {
            this.$v.column_name.$touch()
            if (!this.$v.column_name.$invalid) {
                this.pass = true
            }
        },
        addColumn: function() {
            this.$v.dt.$touch()
            if (!this.$v.dt.$invalid) {
                this.pass = false
                this.$emit("addColumn", [this.column_name, this.dt, this.manual, this.required, this.input_type, this.selection])
                swal.fire("Column added", "Column is ready and inserted", "success")
                this.$v.column_name.$reset()
                this.$v.dt.$reset()
            }
        },
        enter: function() {
            this.selection.push(this.selection_value)
        },
        reset: function() {
            this.column_name = ""
            this.$v.column_name.$reset()
            this.$v.dt.$reset()
            this.pass = false
            this.selection = []
            this.selection_value = ""
        }
    }
}
</script>

<style></style>
