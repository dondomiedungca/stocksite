<template>
    <div>
        <v-main>
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container>
                <stepper-vue :step_count="3" :headers="headers">
                    <template v-slot:step_content_1>
                        <h1>Hello 1</h1>
                    </template>
                    <template v-slot:step_content_2>
                        <h1>Hello 2</h1>
                    </template>
                    <template v-slot:step_content_3>
                        <h1>Hello 3</h1>
                    </template>
                </stepper-vue>
            </v-container>
        </v-main>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            headers: [
                {
                    step_name: "Product Type",
                    step_level: 1
                },
                {
                    step_name: "Manage Column(s)",
                    step_level: 2
                },
                {
                    step_name: "Verify",
                    step_level: 3
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
