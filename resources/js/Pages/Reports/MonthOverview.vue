<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>

    <Head title="Month Overview" />

    <AuthenticatedLayout>
        <section class="page-section">
            <h2 style="margin-bottom: 25px;">Month Overview</h2>

            <!-- Tabs -->
            <div class="table-tabs">
                <button :class="['tab-button', { active: activeTab === 'subcategory' }]" @click="activeTab = 'subcategory'">By Subcategory</button>
                <button :class="['tab-button', { active: activeTab === 'category' }]" @click="activeTab = 'category'">By Category</button>
            </div>

            <!-- Subcategory Tab -->
            <div v-if="activeTab === 'subcategory'" class="tab-content">
                <p class="tab-description">View your time logged this month organized by <strong>subcategory</strong>.
                </p>
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Total Minutes</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in subcategoryData" :key="item.subcategory_id">
                            <td>{{ item.organization_name }}</td>
                            <td>{{ item.category_name }}</td>
                            <td>{{ item.subcategory_name }}</td>
                            <td>{{ item.total_minutes }}</td>
                            <td>{{ item.total_hours }}</td>
                        </tr>
                        <tr v-if="subcategoryData.length === 0">
                            <td colspan="5" style="text-align: center;">No data for this month.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Category Tab -->
            <div v-if="activeTab === 'category'" class="tab-content">
                <p class="tab-description">View your time logged this month organized by <strong>category</strong>.</p>
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Category</th>
                            <th>Total Minutes</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in categoryData" :key="item.category_id">
                            <td>{{ item.organization_name }}</td>
                            <td>{{ item.category_name }}</td>
                            <td>{{ item.total_minutes }}</td>
                            <td>{{ item.total_hours }}</td>
                        </tr>
                        <tr v-if="categoryData.length === 0">
                            <td colspan="4" style="text-align: center;">No data for this month.</td>
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
        subcategoryData: Array,
        categoryData: Array,
    },
    data() {
        return {
            activeTab: 'subcategory',
        };
    },
};
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
</style>
