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
            <h2>Welcome Ben</h2>
            <nav>
                <ul>
                    <li><a href="#">London</a></li>
                    <li><a href="#">Paris</a></li>
                    <li><a href="#">Tokyo</a></li>
                </ul>
            </nav>
        </header>
        <section>
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
    header {
        flex-basis: 300px;
        background-color: $white;
        min-height: 100vh;
    }
}
</style>