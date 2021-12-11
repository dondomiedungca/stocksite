<template>
    <v-row>
        <v-col lg="12">
            <v-tabs color="#000" slider-size="2" align-with-title fixed-tabs slider-color="blue accent-3">
                <v-tab v-for="(tab, i) in navs" :key="i" :href="`#${tab.key}`">
                    {{ tab.text }}
                </v-tab>
                <v-tab-item v-for="(tab, i) in navs" :key="i" :id="tab.key">
                    <v-card color="basil" flat>
                        <slot :name="tab.key"></slot>
                    </v-card>
                </v-tab-item>
            </v-tabs>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: {
        navs: {
            required: true,
            type: Array
        }
    }
}
</script>

<style></style>
