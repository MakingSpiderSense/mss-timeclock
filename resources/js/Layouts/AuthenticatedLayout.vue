<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import FlashMsg from '@/Components/FlashMsg.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
</script>

<!-- Template -->
<template>
    <div class="main-layout">
        <!-- Header/Sidebar -->
        <header>
            <!-- Logo -->
            <div class="ml-logo">
                <Link :href="route('dashboard')">
                    <ApplicationLogo />
                </Link>
            </div>
            <!-- Profile -->
            <div class="ml-profile">
                <!-- Image -->
                <div class="mlp-image">
                    <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" alt="Profile Image">
                </div>
                <!-- Dropdown -->
                <div class="mlp-dropdown">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    {{ $page.props.auth.user.name }}
                                    <!-- Down chevron -->
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <!-- Dropdown Links -->
                        <template #content>
                            <DropdownLink href="#" as="button">
                                View Profile
                            </DropdownLink>
                            <DropdownLink href="#" as="button">
                                Settings
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
            <!-- Main Navigation -->
            <nav>
                <ul>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Clock In/Out
                        </NavLink>
                    </li>
                    <li>
                        <!-- Dropdown -->
                        <Dropdown align="left" width="48">
                            <template #trigger>
                                <button>
                                    Manage 
                                    <!-- Down chevron -->
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <!-- Dropdown Links -->
                            <template #content>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    Create an organization
                                </DropdownLink>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    Invite to organization
                                </DropdownLink>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    Create a client
                                </DropdownLink>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    Edit client rates
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </li>
                    <li>
                        <NavLink :href="route('placeholder')" :active="route().current('placeholder')">
                            Reports
                        </NavLink>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- Main Content -->
        <section>
            <!-- Flash Message -->
            <div v-if="$page.props.flash.message" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FlashMsg :msgType="$page.props.flash.message[0]">{{ $page.props.flash.message[1] }}</FlashMsg>
            </div>
            <!-- Content -->
            <slot />
        </section>
    </div>
</template>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
.main-layout {
    display: flex;
    background-color: $light-gray;
    // Header
    header {
        flex-basis: 300px;
        background-color: $white;
        min-height: 100vh;
        padding: 25px;
        box-shadow: 1px 0px 28px 1px rgba(0, 0, 0, .1);
        // Logo
        .ml-logo {
            width: 100%;
            max-width: 75px;
            margin: 0 auto;
            margin-bottom: 75px;
        }
        // Profile
        .ml-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 100px;
            // Image
            .mlp-image {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                overflow: hidden;
                img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            }
        }
        // Main Navigation
        nav {
            ul li {
                > a, button {
                    display: flex;
                    align-items: center;
                    padding: 7px 10px;
                    width: 100%;
                    &:hover {
                        background-color: $light-gray;
                    }
                }
            }
            a, button {
                font-size: 20px;
            }
            .dropdown-items {
                a, button {
                    font-size: 0.875rem;
                }
            }
        }
    }
}
</style>