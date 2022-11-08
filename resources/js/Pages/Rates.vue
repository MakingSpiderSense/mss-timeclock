<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Hourly Rates" />

    <AuthenticatedLayout>
        <section class="page-section">
            This is the hourly rates page.
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
export default {
    methods: {
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
</style>