<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    orgCreate: '',
});

const submit = () => {
    form.post(route('placeholder'), {
        onFinish: () => form.reset('orgCreate'),
    });
};
</script>

<!-- Template -->
<template>
    <div id="modalbody-org-create">
        <div class="mb-4">Add a new organization below. Please note that at this time, organizations can only be edited or deleted directly from the database.</div>
        <div>
            <InputLabel for="orgCreate" value="Organization Name" />
            <TextInput id="org_create" type="text" class="mt-1 block w-full" v-model="form.orgCreate" required />
            <InputError class="mt-2" :message="form.errors.orgCreate" />
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