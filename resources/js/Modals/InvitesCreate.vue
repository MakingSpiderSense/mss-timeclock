<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    invite_email: '',
});

const submit = () => {
    form.post(route('organizations.invite'), {
        // Todo: Form does not reset after submit. Fix.
        onFinish: () => form.reset('invite_email'),
    });
};
</script>

<!-- Template -->
<template>
    <div>
        <div class="mb-4">Invite a user to [organization]. Please note that once invited, you cannot uninvite them.</div>
        <div>
            <!-- Form -->
            <form id="form_org_invite" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="invite_email" value="User Email" />
                <TextInput id="invite_email" type="text" class="mt-1 block w-full" v-model="form.invite_email" required />
                <InputError class="mt-2" :message="form.errors.invite_email" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'InvitesCreate',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>