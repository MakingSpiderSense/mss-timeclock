<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Hidden Categories - Settings" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <h2>Hidden Categories</h2>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque, blanditiis atque distinctio repellat aspernatur nam cum. Officiis quia dolorum corrupti rerum quis, sed blanditiis, labore, deleniti at totam consequuntur eius ipsa tempora ut quae voluptatum soluta? Excepturi officiis quibusdam corporis, magni laudantium minima doloribus sed ex quis. Veniam, sapiente aspernatur.
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
</style>