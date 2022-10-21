<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    org_name: '',
});

const submit = () => {
    form.post(route('organizations.store'), {
        // Todo: Form does not reset after submit. Fix.
        onFinish: () => form.reset('org_name'),
    });
};
</script>

<!-- Template -->
<template>
    <div>
        <div class="mb-4">Add a new organization below. Please note that at this time, organizations can only be edited or deleted directly from the database.</div>
        <div>
            <!-- Form -->
            <form id="form_org_create" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="org_name" value="Organization Name" />
                <TextInput id="org_name" type="text" class="mt-1 block w-full" v-model="form.org_name" required />
                <InputError class="mt-2" :message="form.errors.org_name" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'OrgCreate',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>