<template>
    <v-text-field v-on="$listeners" v-bind="$attrs" dense placeholder=" " outlined v-model="val" type="text"></v-text-field>
</template>

<script>
export default {
    name: "custom-textfield",
    inheritAttrs: true,
    props: {
        value: {
            type: [String, Number]
        }
    },
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
