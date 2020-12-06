<template>
    <div v-if="Object.keys(data).length">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Column Name</th>
                    <th>Is Manually</th>
                    <th>Is Required</th>
                    <th>Input Type</th>
                    <th>Selections</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(column, i) in data.product_attributes" v-bind:key="i">
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="column.product_column_name" />
                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions1${i}${data.product_name}`" :id="`inlineRadio1${i}${data.product_name}`" v-model="column.product_column_manual_fillable" value="1" />
                            <label class="form-check-label" :for="`inlineRadio1${i}${data.product_name}`">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions2${i}${data.product_name}`" :id="`inlineRadio2${i}${data.product_name}`" v-model="column.product_column_manual_fillable" value="0" />
                            <label class="form-check-label" :for="`inlineRadio2${i}${data.product_name}`">NO</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions3${i}${data.product_name}`" :id="`inlineRadio3${i}${data.product_name}`" v-model="column.product_column_is_required" value="1" />
                            <label class="form-check-label" :for="`inlineRadio3${i}${data.product_name}`">YES</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions4${i}${data.product_name}`" :id="`inlineRadio4${i}${data.product_name}`" v-model="column.product_column_is_required" value="0" />
                            <label class="form-check-label" :for="`inlineRadio4${i}${data.product_name}`">NO</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions5${i}${data.product_name}`" :id="`inlineRadio5${i}${data.product_name}`" v-model="column.product_column_input_type" value="INPUT" />
                            <label class="form-check-label" :for="`inlineRadio5${i}${data.product_name}`">INPUT</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" :name="`inlineRadioOptions6${i}${data.product_name}`" :id="`inlineRadio6${i}${data.product_name}`" v-model="column.product_column_input_type" value="SELECTION" />
                            <label class="form-check-label" :for="`inlineRadio6${i}${data.product_name}`">SELECTION</label>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" :id="`exampleInputEmail1${data.product_name}`" v-model="column.newSelection" @keyup.enter="enter(i)" aria-describedby="emailHelp" />
                        <small>Press enter key to add this selection value</small>
                        <br />
                        <br />
                        <label for="">Selections for this column</label>
                        <ul>
                            <li v-for="(value_selection, ind) in column.column_selections" v-bind:key="ind">{{ value_selection.selection_name }}</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["data"],
    methods: {
        enter: function(i) {
            this.data.product_attributes[i].column_selections.push({
                newSelection: this.data.product_attributes[i].newSelection
            })
        }
    }
}
</script>

<style></style>
