<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/inertia-vue3'

const form = useForm({
    name: usePage().props.value.auth.user.name,
    email: usePage().props.value.auth.user.email,
    nickname: usePage().props.value.auth.user.nickname,
    username: usePage().props.value.auth.user.username,
});

const submit = () => {
    form.post(route(`profile.update`));
};
</script>

<template>
    <Head title="Edit Profile" />

    <AuthenticatedLayout>
        <section class="page-section">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="text" class="mt-1 block w-full" v-model="form.email" autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="nickname" value="Nickname" />
                    <TextInput id="nickname" type="text" class="mt-1 block w-full" v-model="form.nickname" autocomplete="nickname" />
                    <InputError class="mt-2" :message="form.errors.nickname" />
                </div>

                <div class="mt-4">
                    <InputLabel for="username" value="Username" />
                    <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update
                    </PrimaryButton>
                </div>
            </form>
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