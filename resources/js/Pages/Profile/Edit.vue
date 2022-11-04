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
    image: null,
});

const submit = () => {
    form.post(route(`profile.update`));
};
</script>

<template>
    <Head title="Edit Profile" />

    <AuthenticatedLayout>
        <section class="page-section">
            <!-- Return Link -->
            <div class="flex items-center justify-end mt-4">
                <Link :href="route('profile')">
                    Return to Profile
                </Link>
            </div>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <!-- Name -->
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus required autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <!-- Email Address -->
                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="text" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <!-- Nickname -->
                <div class="mt-4">
                    <InputLabel for="nickname" value="Nickname" />
                    <TextInput id="nickname" type="text" class="mt-1 block w-full" v-model="form.nickname" autocomplete="nickname" />
                    <InputError class="mt-2" :message="form.errors.nickname" />
                </div>
                <!-- Username -->
                <div class="mt-4">
                    <InputLabel for="username" value="Username" />
                    <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>
                <!-- Profile Image -->
                <div class="mt-4">
                    <InputLabel for="image" value="Profile Image" />
                    <TextInput id="image" type="file" class="mt-1 block" accept=".jpeg,.png,.jpg,.gif" @input="form.image = $event.target.files[0]" />
                    <InputError class="mt-2" :message="form.errors.image" />
                </div>
                <!-- Submit Button -->
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
#image {
    border-radius: 0;
    box-shadow: none;
    cursor: pointer;
}
input[type=file],
input[type=file]::-webkit-file-upload-button {
    cursor: pointer; 
}
</style>