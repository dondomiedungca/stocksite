<template>
    <v-file-input v-on="$listeners" v-bind="$attrs" dense counter show-size="" outlined v-model="val"></v-file-input>
</template>

<script>
export default {
    name: "custom-inputfile",
    inheritAttrs: true,
    props: ["value"],
    computed: {
        val: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit("input", val)
            }
        },
        inputListeners: function() {
            var vm = this
            // `Object.assign` merges objects together to form a new object
            return Object.assign(
                {},
                // We add all the listeners from the parent
                this.$listeners,
                // Then we can add custom listeners or override the
                // behavior of some listeners.
                {
                    // This ensures that the component works with v-model
                    input: function(event) {
                        vm.$emit("input", event.target.value)
                    }
                }
            )
        }
    }
}
</script>

<style></style>
