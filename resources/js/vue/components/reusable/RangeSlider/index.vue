<template>
    <div>
        <small><slot name="digitPrepend">$</slot> {{ val[0].toFixed(2) | numWithCommas }} - <slot name="digitAppend">$</slot> {{ val[1].toFixed(2) | numWithCommas }}</small>
        <v-range-slider v-bind="$attrs" v-model="val" step="10" max="100000"></v-range-slider>
    </div>
</template>

<script>
export default {
    inheritAttrs: true,
    name: "slider",
    props: {
        value: {
            required: true,
            type: Array
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
