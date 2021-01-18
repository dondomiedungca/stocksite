<template>
    <div v-if="Object.keys(details).length">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-md">
                <thead>
                    <tr>
                        <th align="center" colspan="2">Full Details (ASC)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(key, i) in keys" v-bind:key="i">
                        <td>
                            {{ key }}
                        </td>
                        <td>
                            {{ full_details[key] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import _ from "lodash"

export default {
    props: ["details"],
    data() {
        return {
            full_details: {},
            keys: []
        }
    },
    watch: {
        details: function(newVal, oldVal) {
            this.full_details = newVal
            this.sorting()
        }
    },
    methods: {
        sorting: function() {
            this.keys = Object.keys(this.full_details).sort()
        }
    }
}
</script>

<style></style>
