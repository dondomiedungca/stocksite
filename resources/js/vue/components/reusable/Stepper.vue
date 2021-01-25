<template>
    <v-stepper v-model="e1">
        <v-stepper-header>
            <template v-for="step in headers">
                <v-stepper-step :key="`${step.step_level}-step`" :complete="e1 > step.step_level" :step="step.step_level" editable> {{ step.step_name }} </v-stepper-step>

                <v-divider v-if="step.step_level !== headers.length" :key="step.step_level"></v-divider>
            </template>
        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content v-for="step in step_count" :key="`${step}-content`" :step="step">
                <slot :name="`step_content_${step}`"></slot>

                <v-btn small :disabled="!getStepper.canContinue" color="primary" @click="nextStep(step)">
                    Continue
                </v-btn>

                <v-btn small :disabled="step == 1" @click="nextStep(step - 1)">
                    Cancel
                </v-btn>
            </v-stepper-content>
        </v-stepper-items>
    </v-stepper>
</template>

<script>
import { mapGetters } from "vuex"

export default {
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
            e1: 1
        }
    },
    computed: {
        ...mapGetters(["getStepper"])
    },
    methods: {
        nextStep(n) {
            if (n === this.steps.length) {
                this.e1 = 1
            } else if (n < this.e1 && n != 0) {
                this.e1 = n
            } else {
                this.e1 = n + 1
            }
        }
    }
}
</script>

<style></style>
