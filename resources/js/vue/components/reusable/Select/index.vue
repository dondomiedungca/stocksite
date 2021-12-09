<template>
    <v-select v-bind="$attrs" outlined v-model="val" dense></v-select>
</template>

<script>
export default {
    inheritAttrs: true,
    name: "custom-select",
    props: {
        value: {
            required: true,
            type: [Object, String, Number]
        }
    },
    computed: {
        val: {
            get() {
                return this.value
            },
            set(value) {
                this.$emit("input", value)
            }
        }
    }
}
</script>

<style></style>
