<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    updated_rate: '',
    type: '',
    id: '',
    name: '',
    rate: '',
});
// Note: I had to add set the form to the window object so that I could access it from the RatesUpdate component. Not sure if there is a better way to do this.
window.rate_form = form;

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
                <!-- Hidden inputs for type, id, name, and rate -->
                <input type="hidden" name="type" value="" />
                <input type="hidden" name="id" value="" />
                <input type="hidden" name="name" value="" />
                <input type="hidden" name="rate" value="" />
                <!-- Visible -->
                <InputLabel for="updated_rate" value="New Rate" />
                <TextInput id="updated_rate" type="number" class="mt-1 block w-full" step=".01" min="0" v-model="form.updated_rate" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'RatesUpdate',
    mounted() {
        // When this modal is first loaded, add an event listener to all four hidden inputs watching for changes to the value. When a change is detected, update the corresponding form input (e.g. this.form.type = type.value).
        const type = document.querySelector('input[name="type"]');
        const id = document.querySelector('input[name="id"]');
        const name = document.querySelector('input[name="name"]');
        const rate = document.querySelector('input[name="rate"]');
        type.addEventListener('change', () => {
            window.rate_form.type = type.value;
        });
        id.addEventListener('change', () => {
            window.rate_form.id = id.value;
        });
        name.addEventListener('change', () => {
            window.rate_form.name = name.value;
        });
        rate.addEventListener('change', () => {
            window.rate_form.rate = rate.value;
        });
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>