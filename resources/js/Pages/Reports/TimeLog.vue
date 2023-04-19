<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// const form = useForm({
//     month: '',
//     userAccounts: '',
// });

// const submit = () => {
//     form.post(route(`super-admin.consolidate`));
// };
</script>

<template>
    <Head title="Time Log" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <h2>Time Log</h2>
            History of time logs go here
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