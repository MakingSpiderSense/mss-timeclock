<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Hidden Categories - Settings" />

    <AuthenticatedLayout>
        <section class="page-section">
            <h2 style="margin-bottom: 25px;">Hidden Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Category</th>
                        <th>Subcategory</th>
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
                        <td><Link class="btn">Unhide</Link></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    data() {
        return {
            hiddenCategories: usePage().props.value.hiddenCategories,
        };
    },
};
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
// Table
// Eventually move this to a global style sheet if it's used in multiple places
table, td, th {
    border: 1px solid #ddd;
    text-align: center;
}
table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 50px;
    th, td {
        padding: 8px 15px;
    }
    .notes {
        text-align: left;
    }
    .price {
        text-align: right;
    }
}
@media (max-width: 1000px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        th, td {
            img {
                max-width: 50px;
            }
        }
    }
}
</style>