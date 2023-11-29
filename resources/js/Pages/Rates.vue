<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Hourly Rates" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <!-- Global and Organization Rates -->
            <h2>Global and Organization Rates</h2>
            <div class="parent-rates mb-10">
                <table class="standard-table table-auto">
                    <tbody>
                        <!-- Global Rate -->
                        <tr>
                            <td><button @click="modalRatesEdit('global_rate', 0, 'Global', global_rate)">Edit</button></td>
                            <td>Global rate</td>
                            <td>${{ global_rate }}</td>
                        </tr>
                        <!-- Organization Rates -->
                        <!-- For each row in organization_rates, display the name and rate. But if organization.rate is null, output "Not Set" -->
                        <tr v-for="organization in organization_rates" :key="organization.id">
                            <td><button @click="modalRatesEdit('organization_rates', organization.id, organization.name, organization.rate)">Edit</button></td>
                            <td>{{ organization.name }}</td>
                            <td>{{ organization.rate ? `$${organization.rate}` : "Not Set" }}</td>
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
                        <!-- For each row in categories_with_rates, display the name and rate. But if category.rate is null, output "Not Set" -->
                        <tr v-for="category in categories_with_rates" :key="category.id">
                            <td><button @click="modalRatesEdit('categories_with_rates', category.id, category.name, category.rate)">Edit</button></td>
                            <td>{{ category.name }}</td>
                            <td>{{ category.rate ? `$${category.rate}` : "Not Set" }}</td>
                        </tr>
                        <!-- Display categories_without_rates -->
                        <tr v-for="category in categories_without_rates" :key="category.id">
                            <td><button @click="modalRatesEdit('categories_without_rates', category.id, category.name, 0)">Edit</button></td>
                            <td>{{ category.name }}</td>
                            <td>Not Set</td>
                        </tr>
                        <!-- If categories_with_rates and categories_without_rates both have no rows, display a message. -->
                        <tr v-if="categories_with_rates.length == 0 && categories_without_rates.length == 0">
                            <td colspan="3">No categories found.</td>
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
        categories_with_rates: Array,
        categories_without_rates: Array,
    },
    data() {
        return {
            pageModal: {
                title: 'RatesUpdate',
                count: 0,
            },
        }
    },
    methods: {
        modalRatesEdit(type, id, name, rate) {
            // Update modal body (the count is a workaround to trigger the watcher even if the value is the same)
            this.pageModal = {
                title: 'RatesUpdate',
                count: this.pageModal.count + 1,
            };
            document.querySelector('.modal').style.display = "flex";
            document.querySelector('.modal-title').innerHTML = "Update Rate";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button form="form_rate_update" class="btn modal-continue" type="submit">Save</button>`;
            modalFooter.querySelector('button').addEventListener('click', closeModal);
            setTimeout(() => {
                // Update intro text
                const rates_intro = document.querySelector('#rates_intro');
                const rate_name = document.querySelector('#rate_name');
                const rate_status = document.querySelector('#rate_status');
                rate_name.innerHTML = name;
                rate_status.innerHTML = rate ? `It is currently set to <strong>$${rate}</strong>. Leave blank to remove rate.` : "This rate is not yet set.";
                rates_intro.style.display = "block";
                // Update the hidden input values
                document.querySelector('input[name="type"]').value = type;
                document.querySelector('input[name="type"]').dispatchEvent(new Event('change'));
                document.querySelector('input[name="id"]').value = id;
                document.querySelector('input[name="id"]').dispatchEvent(new Event('change'));
                document.querySelector('input[name="name"]').value = name;
                document.querySelector('input[name="name"]').dispatchEvent(new Event('change'));
                document.querySelector('input[name="rate"]').value = rate;
                document.querySelector('input[name="rate"]').dispatchEvent(new Event('change'));
            }, 0);
            function closeModal() {
                // Close modal and remove event listener
                if (document.querySelector('#updated_rate').checkValidity()) {
                    document.querySelector('.modal').style.display = 'none';
                    rates_intro.style.display = "none";
                    modalFooter.removeEventListener('click', closeModal);
                }
            }
        },
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
.page-section {
    h2 {
        text-align: left;
    }
}
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
    button {
        color: $color1;
    }
}
</style>