<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    month: '',
    userAccounts: '',
});

const submit = () => {
    form.post(route(`super-admin.consolidate`));
};
</script>

<template>
    <Head title="SuperAdmin Dashboard" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <h2>SuperAdmin Dashboard</h2>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <!-- Name -->
                <div class="mb-5">
                    <InputLabel for="month" value="Month" />
                    <TextInput id="month" type="text" class="mt-1 block w-full" v-model="form.month" placeholder="YYYY-MM" required autofocus />
                    <InputError class="mt-2" :message="form.errors.month" />
                </div>
                <!-- User IDs -->
                <div class="mb-5">
                    <InputLabel for="userAccounts" value="User IDs (comma separated)" />
                    <TextInput id="userAccounts" type="text" class="mt-1 block w-full" v-model="form.userAccounts" placeholder="(e.g. 1, 2)" required />
                    <InputError class="mt-2" :message="form.errors.userAccounts" />
                </div>
                <!-- Submit Button -->
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Consolidate Logs
                    </PrimaryButton>
                </div>
            </form>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
</style>