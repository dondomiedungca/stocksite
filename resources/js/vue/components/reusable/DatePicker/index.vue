<template>
    <v-dialog ref="dialog" v-model="modal" persistent width="290px">
        <template v-slot:activator="{ on }">
            <v-text-field outlined dense v-bind="$attrs" v-model="displayReadable" readonly v-on="on"></v-text-field>
        </template>
        <v-date-picker :range="range" v-model="val" scrollable @input="handleDatepickerClose">
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modal = false">
                Cancel
            </v-btn>
            <v-btn text color="primary" @click="$refs.dialog.save(val)">
                OK
            </v-btn>
        </v-date-picker>
    </v-dialog>
</template>

<script>
import moment from "moment"

export default {
    inheritAttrs: true,
    name: "custom-datepicker",
    data: () => ({
        modal: false
    }),
    props: {
        value: {
            required: true,
            type: [Array, String, Date]
        },
        displayFormat: {
            required: false,
            type: String,
            default: "MMMM DD, YYYY"
        },
        range: {
            required: false,
            default: false
        }
    },
    computed: {
        val: {
            get() {
                return this.value
            },
            set(v) {
                let res = v
                if (this.range) {
                    let checkInvalid = res.indexOf("") != -1
                    if (checkInvalid) {
                        res = res.filter(v1 => v1 != "")
                    } else {
                        res = res.sort((a, b) => new Date(a) - new Date(b))
                    }
                }
                this.$emit("input", res)
            }
        },
        displayReadable: {
            get() {
                if (this.value) {
                    if (typeof this.value === "object") {
                        let range = this.value.map(value => this.formatReadable(value))

                        return range.join(" - ")
                    }
                    return this.formatReadable(this.value)
                }

                return ""
            },
            set(val) {
                if (this.range) {
                    this.$emit("input", val || [])
                } else {
                    this.$emit("input", val || "")
                }
            }
        }
    },
    methods: {
        formatReadable(date) {
            let initial = new Date(date)
            return moment(initial).format(this.displayFormat)
        },
        handleDatepickerClose() {
            if (this.range) {
                this.$nextTick(() => {
                    const len = this.value.length
                    if (len > 1) {
                        this.modal = false
                    }
                })
            } else {
                this.modal = false
            }
        }
    }
}
</script>

<style></style>
