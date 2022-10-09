<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Edit Profile" />

    <AuthenticatedLayout>
        <section class="page-section">
            Testing Edit Profile
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
@import "../../../sass/app.scss";
</style>