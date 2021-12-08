<template>
    <div>
        <v-container class="header-container" fluid>
            <div class="nav-title font-weight-thin">
                <v-icon class="nav-icon mr-5">{{ icon }}</v-icon
                >{{ title }}
            </div>
        </v-container>
        <v-breadcrumbs :items="items">
            <template v-slot:divider>
                <v-icon>mdi-chevron-right</v-icon>
            </template>
            <template v-slot:item="{ item }">
                <v-breadcrumbs-item class="br-item" :href="item.to" :disabled="item.disabled">
                    <v-icon class="br-icon">{{ item.icon }}</v-icon
                    >{{ item.text }}
                </v-breadcrumbs-item>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    props: ["items", "title", "icon"]
}
</script>

<style>
.br-item {
    margin-left: -4px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.br-icon {
    margin-right: 5px;
}

.nav-title {
    margin-left: -5px;
}
</style>
