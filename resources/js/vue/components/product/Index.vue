<template>
    <Container title="Inventory Management" icon="mdi-view-list" :breadCrumbs="breadCrumbs">
        <ModulesIterator :modules="modules" />
    </Container>
</template>

<script>
import { modules, breadCrumbs } from "./assets/constant"
import ModulesIterator from "./../reusable/ModulesIterator"
import Container from "./../reusable/Container"

export default {
    components: {
        ModulesIterator,
        Container
    },
    data() {
        return {
            breadCrumbs: [],
            modules: []
        }
    },
    created() {
        this.breadCrumbs = breadCrumbs
        this.modules = modules
    }
}
</script>

<style></style>
