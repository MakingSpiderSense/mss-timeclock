<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// const form = useForm({
//     month: '',
//     userAccounts: '',
// });

// const submit = () => {
//     form.post(route(`super-admin.consolidate`));
// };
</script>

<template>
    <Head title="Time Log" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <h2 style="margin-bottom: 25px;">Time Log</h2>
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Organization</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Total Time</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="log in logs" :key="log.id">
                        <td :title="`Id: ${log.id}, Org: ${log.org_id}, Category: ${log.category_id}, Subcategory: ${log.subcategory_id}`">{{ formatDate(log.clock_in) }}</td>
                        <td>{{ log.organization_name }}</td>
                        <td>{{ log.category_name }}</td>
                        <td>{{ log.subcategory_name }}</td>
                        <td>{{ formatTime(log.clock_in) }}</td>
                        <td>{{ formatTime(log.clock_out) }}</td>
                        <td>{{ formatMinutes(log.minutes) }}</td>
                        <td :title="log.notes">{{ formatNotes(log.notes) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
    export default {
        props: ['logs'],
        methods: {
            // Format date to mm/dd/yyyy
            formatDate(dateString) {
                const date = new Date(dateString);
                return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
            },
            // Format time to 12 hour format with am/pm
            formatTime(dateString) {
                if (dateString) {
                    const date = new Date(dateString);
                    const hours = date.getHours();
                    const minutes = date.getMinutes().toString().padStart(2, '0');
                    const amOrPm = hours < 12 ? 'am' : 'pm';
                    const twelveHourFormat = hours % 12 || 12;
                    return `${twelveHourFormat}:${minutes}${amOrPm}`;
                }
                return '';
            },
            // Format minutes to hours and minutes
            formatMinutes(minutes) {
                if (minutes) {
                    const hours = Math.floor(minutes / 60);
                    const remainingMinutes = minutes % 60;
                    return `${hours}h ${remainingMinutes}m`;
                } else if (minutes === 0) {
                    return '0h 0m';
                } else {
                    return 'N/A';
                }
            },
            // Format notes to only show the first 10 characters and then a "..." after
            formatNotes(notes) {
                if (notes) {
                    return notes.length > 10 ? `${notes.substring(0, 10)}...` : notes;
                }
                return '';
            },
        },
    }
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
</style>