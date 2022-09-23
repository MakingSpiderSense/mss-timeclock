<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Logo -->
            <div class="flex justify-center pt-8 sm:pt-0">
                <div class="logo-text">
                    <!-- Image -->
                    <div class="lt-img">
                        <ApplicationLogo />
                    </div>
                    <!-- Text -->
                    <div class="lt-text">
                        <h1>{{ $page.props.app.name }}</h1>
                        <div class="text-sm text-gray-500 sm:text-right sm:ml-0">
                            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<!-- Styling -->
<style lang="scss" scoped>
    @import "../../sass/app.scss";
    .logo-text {
        display: flex;
        align-items: center;
        padding: 0 20px;
        text-align: center;
        .lt-img {
            width: 100%;
            max-width: 100px;
        }
        .lt-text {
            h1 {
                color: $black;
                font-size: 2.5rem;
                font-weight: 700;
                margin: 0;
                margin-left: 1rem;
            }
        }
    }
    @media (max-width: 500px) {
        .logo-text {
            flex-direction: column;
            .lt-img {
                max-width: 60px;
                margin-bottom: 5px;
            }
            .lt-text {
                h1 {
                    font-size: 1.5rem;
                    margin-left: 0;
                }
            }
        }
    }
</style>