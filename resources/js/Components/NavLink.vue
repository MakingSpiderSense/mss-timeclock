<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'active-nav'
    : ''
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
a:link, a:visited, a:hover, a:active {
    color: $black;
}
.active-nav {
    // after, include a center dot with the color red
    &:after {
        content: "•";
        color: $color1;
        font-size: 20px;
        margin-left: 10px;
    }
}
</style>