<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <section class="page-section">
            This is a test
        </section>
    </AuthenticatedLayout>
</template>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
</style>