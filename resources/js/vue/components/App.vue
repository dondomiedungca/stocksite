<template>
    <v-app>
        <AskBar />
        <Sidebar height="100" />
        <slot></slot>
        <SnackBar />
    </v-app>
</template>

<script>
import SnackBar from "./reusable/SnackBar"
import Sidebar from "./reusable/Sidebar"
import AskBar from "./reusable/AppBar"

export default {
    components: {
        SnackBar,
        Sidebar,
        AskBar
    }
}
</script>

<style></style>
