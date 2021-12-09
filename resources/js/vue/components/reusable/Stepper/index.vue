<template>
    <v-stepper v-model="e1">
        <v-stepper-header>
            <template v-for="step in headers">
                <v-stepper-step :key="`${step.step_level}-step`" :complete="e1 > step.step_level" :step="step.step_level"> {{ step.step_name }} </v-stepper-step>

                <v-divider v-if="step.step_level !== headers.length" :key="step.step_level"></v-divider>
            </template>
        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content v-for="step in step_count" :key="`${step}-content`" :step="step">
                <slot :name="`step_content_${step}`"></slot>

                <v-btn v-if="!isFinal" small :disabled="!getStepper.canContinue" color="dark" @click="nextStep(step)"> <v-icon>mdi-login-variant</v-icon>Continue </v-btn>

                <slot v-else name="finish_button"> </slot>

                <v-btn small :disabled="step == 1" @click="nextStep(step - 1)"> <v-icon>mdi-cancel</v-icon> Cancel </v-btn>
                <br />
                <br />
                <br />
            </v-stepper-content>
        </v-stepper-items>
    </v-stepper>
</template>

<script>
import { mapGetters, mapMutations } from "vuex"

export default {
    watch: {
        e1: function(newVal, oldVal) {
            if (newVal == this.step_count) {
                this.isFinal = true
            } else {
                this.isFinal = false
            }
        }
    },
    props: {
        step_count: {
            required: true,
            type: Number
        },
        headers: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            e1: 1,
            isFinal: false
        }
    },
    computed: {
        ...mapGetters("stepper", ["getStepper"])
    },
    methods: {
        ...mapMutations("stepper", ["SET_STEPPER"]),
        nextStep(n) {
            if (n === this.step_count) {
                this.e1 = 1
            } else if (n < this.e1 && n != 0) {
                this.e1 = n
                this.SET_STEPPER({
                    canContinue: true,
                    canFinish: false
                })
            } else {
                this.e1 = n + 1
                this.SET_STEPPER({
                    canContinue: false,
                    canFinish: false
                })
            }
        }
    }
}
</script>

<style></style>
