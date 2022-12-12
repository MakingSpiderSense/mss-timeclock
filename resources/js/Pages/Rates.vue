<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Hourly Rates" />

    <AuthenticatedLayout>
        <section class="page-section">
            <!-- Global and Organization Rates -->
            <h2>Global and Organization Rates</h2>
            <div class="parent-rates mb-10">
                <table class="standard-table table-auto">
                    <tbody>
                        <!-- Global Rate -->
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Global rate</td>
                            <td>${{ global_rate }}</td>
                        </tr>
                        <!-- Organization Rates -->
                        <!-- For each row in organization_rates, display the name and rate. But if organization.rate is null, output "Not Set" -->
                        <tr v-for="organization in organization_rates" :key="organization.id">
                            <td><a href="#">Edit</a></td>
                            <td>{{ organization.name }}</td>
                            <td>{{ organization.rate ? organization.rate : "Not Set" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Category Rates -->
            <h2>Category Rates</h2>
            <p class="mb-4"><em>Only the categories that are part of the currently selected organization, <strong>{{ $page.props.auth.active_org_name }}</strong>, will show up here.</em></p>
            <div class="child-rates">
                <table class="standard-table table-auto">
                    <tbody>
                        <!-- For each row in category_with_rates, display the name and rate. But if category.rate is null, output "Not Set" -->
                        <tr v-for="category in category_with_rates" :key="category.id">
                            <td><a href="#">Edit</a></td>
                            <td>{{ category.name }}</td>
                            <td>{{ category.rate ? category.rate : "Not Set" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
export default {
    props: {
        global_rate: String,
        organization_rates: Array,
        category_with_rates: Array,
    },
    methods: {
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
table.standard-table {
    border: none;
    width: 100%;
    max-width: 600px;
    tr {
        td {
            padding: 5px;
            border: none;
            vertical-align: initial;
            // Edit Buttons
            &:first-child {
                text-align: left;
            }
            // Title
            &:nth-child(2) {
                text-align: left;
            }
            // Rate
            &:nth-child(3) {
                text-align: right;
            }
        }
    }
}
</style>