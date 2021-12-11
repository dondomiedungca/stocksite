<template>
    <v-radio-group v-bind="$attrs" row v-model="val" mandatory>
        <template v-slot:label>
            <div>
                <strong>{{ label }}</strong>
            </div>
        </template>
        <v-radio v-for="(item, i) in items" :key="i" :label="item.text" :value="item.value"></v-radio>
    </v-radio-group>
</template>

<script>
export default {
    name: "custom-radio",
    inheritAttrs: true,
    props: {
        value: {
            type: [String, Number]
        },
        label: {
            required: true
        },
        items: {
            required: true,
            type: Array
        }
    },
    computed: {
        val: {
            set(v) {
                this.$emit("input", v)
            },
            get() {
                return this.value
            }
        }
    }
}
</script>

<style></style>
