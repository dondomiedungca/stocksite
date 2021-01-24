<template>
    <div>
        <v-main>
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container>
                <v-stepper v-model="e1">
                    <v-stepper-header>
                        <template v-for="step in steps">
                            <v-stepper-step :key="`${step.level}-step`" :complete="e1 > step.level" :step="step.level" editable> {{ step.step }} </v-stepper-step>

                            <v-divider v-if="step.level !== steps.length" :key="step.level"></v-divider>
                        </template>
                    </v-stepper-header>

                    <v-stepper-items>
                        <v-stepper-content v-for="step in steps" :key="`${step.level}-content`" :step="step.level">
                            <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

                            <v-btn small :disabled="step.level == steps.length" color="primary" @click="nextStep(step.level)">
                                Continue
                            </v-btn>

                            <v-btn small :disabled="step.level == 1" @click="nextStep(step.level - 1)">
                                Cancel
                            </v-btn>
                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>
            </v-container>
        </v-main>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            e1: 1,
            steps: [
                {
                    step: "Product Type",
                    level: 1
                },
                {
                    step: "Manage Column(s)",
                    level: 2
                },
                {
                    step: "Verify",
                    level: 3
                }
            ],
            items: [
                {
                    text: "Products",
                    disabled: false,
                    exact: true,
                    to: "/admin/products"
                },
                {
                    text: "Add Product Type",
                    disabled: true,
                    to: "add-product-type"
                }
            ]
        }
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
