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
            <table id="table-sortable">
                <tr>
                    <th @click="sortTable(0, 'text')">Organization</th>
                    <th @click="sortTable(1, 'text')">Category</th>
                    <th @click="sortTable(2, 'text')">Subcategory</th>
                    <th></th>
                </tr>
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
            </table>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
import { usePage } from '@inertiajs/inertia-vue3';
import { sortTable } from '../../Utilities/helpers';

export default {
    data() {
        return {
            hiddenCategories: usePage().props.value.hiddenCategories,
        };
    },
    mounted() {
        sortTable(2, 'text');
        sortTable(1, 'text');
        sortTable(0, 'text');
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
    th {
        cursor: pointer;
    }
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