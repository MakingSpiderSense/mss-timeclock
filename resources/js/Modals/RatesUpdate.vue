<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    invite_email: '',
});

const submit = () => {
    form.post(route('rate.update'), {
        // Todo: Form does not reset after submit. Fix.
        onFinish: () => form.reset('updated_rate'),
    });
};
</script>

<!-- Template -->
<template>
    <div>
        <div class="mb-4">Update <strong>SomeName</strong> rate. Status.</div>
        <div>
            <!-- Form -->
            <form id="form_rate_update" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="updated_rate" value="New Rate" />
                <TextInput id="updated_rate" type="number" class="mt-1 block w-full" v-model="form.updated_rate" required />
                <InputError class="mt-2" :message="form.errors.updated_rate" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'RatesUpdate',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>