<template>
    <v-card elevation="2">
        <v-list-item :to="url">
            <v-list-item-icon>
                <v-icon v-text="icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-content>
                <v-list-item-title>
                    {{ title }}
                </v-list-item-title>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <small class="font-weight-light">
                    {{ description }}
                </small>
            </v-list-item-content>
        </v-list-item>
    </v-card>
</template>

<script>
export default {
    props: ["url", "title", "description", "icon"]
}
</script>

<style></style>
