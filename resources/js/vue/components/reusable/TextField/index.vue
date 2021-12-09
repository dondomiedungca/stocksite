<template>
    <v-text-field v-bind="$attrs" dense placeholder=" " outlined v-model="val" type="text"></v-text-field>
</template>

<script>
export default {
    name: "custom-textfield",
    inheritAttrs: true,
    props: {
        value: {
            required: true,
            type: String
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
        }
    }
}
</script>

<style></style>
