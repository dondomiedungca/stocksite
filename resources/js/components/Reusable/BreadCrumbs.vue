<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li v-for="(url, i) in urls" :class="{ active: url.active }" v-bind:key="i" class="breadcrumb-item">
                <a v-if="!url.active" :href="url.url">{{ url.name }}</a>
                <span v-else>{{ url.name }}</span>
            </li>
        </ol>
    </nav>
</template>

<script>
export default {
    props: ["urls"]
}
</script>

<style></style>
