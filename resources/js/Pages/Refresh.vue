<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="page-section">
            Loading...
        </div>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
export default {
    methods: {
    },
    mounted() {
        // After one second, redirect to the dashboard
        setTimeout(() => {
            this.$inertia.visit('/dashboard');
        }, 1000);
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
</style>