<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    category_name: '',
});

const submit = () => {
    form.post(route('categories.store'), {
        // Todo: Form does not reset after submit. Fix.
        onFinish: () => form.reset('category_name'),
    });
};
</script>

<!-- Template -->
<template>
    <div>
        <div class="mb-4">Add a new category below. Examples of common categories would be the name of a <strong>client</strong> or <strong>project</strong>. Please note that at this time, categories can only be edited or deleted directly from the database.</div>
        <div>
            <!-- Form -->
            <form id="form_category_create" @submit.prevent="submit" autocomplete="off">
                <InputLabel for="category_name" value="Category Name" />
                <TextInput id="category_name" type="text" class="mt-1 block w-full" v-model="form.category_name" required />
                <InputError class="mt-2" :message="form.errors.category_name" />
            </form>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'CategoryCreate',
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>