<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    client_name: '',
});

const submit = () => {
    form.post(route('clients.store'), {
        // Todo: Form does not reset after submit. Fix.
        onFinish: () => form.reset('client_name'),
    });
};
</script>

<!-- Template -->
<template>
    <div>
        <div class="mb-4">Add a new client below. Please note that at this time, clients can only be edited or deleted directly from the database.</div>
        <div>
            <!-- Form -->
            <form id="form_client_create" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="client_name" value="Client Name" />
                <TextInput id="client_name" type="text" class="mt-1 block w-full" v-model="form.client_name" required />
                <InputError class="mt-2" :message="form.errors.client_name" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'ClientCreate',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>