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
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Global rate</td>
                            <td>$50.00</td>
                        </tr>
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Client With A Really Very Long Name</td>
                            <td>$23.50</td>
                        </tr>
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Making Spider Sense</td>
                            <td>Not Set</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Category Rates -->
            <h2>Category Rates</h2>
            <p class="mb-4"><em>Only the category that are part of the currently selected organization, <strong>Making Spider Sense</strong>, will show up here.</em></p>
            <div class="child-rates">
                <table class="standard-table table-auto">
                    <tbody>
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Administration</td>
                            <td>$50.00</td>
                        </tr>
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Emails</td>
                            <td>$23.50</td>
                        </tr>
                        <tr>
                            <td><a href="#">Edit</a></td>
                            <td>Education</td>
                            <td>Not Set</td>
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