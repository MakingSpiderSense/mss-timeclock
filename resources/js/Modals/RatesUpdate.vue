<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    updated_rate: '',
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
        <!-- Intro Text - This mostly will be dynamic. -->
        <div id="rates_intro" class="mb-4" style="display: none;">Update <strong><span id="rate_name">your</span></strong> rate. <span id="rate_status"></span></div>
        <div>
            <!-- Form -->
            <form id="form_rate_update" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="updated_rate" value="New Rate" />
                <TextInput id="updated_rate" type="number" class="mt-1 block w-full" step=".01" min="0" v-model="form.updated_rate" required />
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