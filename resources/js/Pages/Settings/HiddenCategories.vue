<script setup>
import { Head, usePage } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { initSortableTables } from '@/Utilities/table-sorting';
</script>

<template>
    <Head title="Hidden Categories - Settings" />

    <AuthenticatedLayout>
        <section class="page-section">
            <h2 style="margin-bottom: 25px;">Hidden Categories</h2>
            <table class="clean-table sortable-table">
                <thead>
                    <tr>
                        <th class="sortable-string">Organization</th>
                        <th class="sortable-string">Category</th>
                        <th class="sortable-string">Subcategory</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="hiddenCategory in hiddenCategories" :key="hiddenCategory.id">
                        <td>{{ hiddenCategory.organization.name }}</td>
                        <td>{{ hiddenCategory.category.name }}</td>
                        <td>
                            <span v-if="hiddenCategory.subcategory === null">
                                * All subcategories hidden
                            </span>
                            <span v-else>
                                {{ hiddenCategory.subcategory.name }}
                            </span>
                        </td>
                        <!-- Unhide button -->
                        <td>
                            <form :action="route('settings.hidden-categories.remove', { id: hiddenCategory.id })" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <button type="submit" class="btn btn-unhide">Unhide</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
import { usePage } from '@inertiajs/inertia-vue3';
import { initSortableTables } from '@/Utilities/helpers';

export default {
    data() {
        return {
            hiddenCategories: usePage().props.value.hiddenCategories,
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        };
    },
    mounted() {
        initSortableTables('sortable-table');
    },
};
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
.btn-unhide {
    padding: 0;
    color: $color1;
    &:hover {
        color: $black;
    }
}
</style>