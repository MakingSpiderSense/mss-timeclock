<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="View Profile" />

    <AuthenticatedLayout>
        <section class="page-section">
            <!-- Flex container  -->
            <div class="profile-container">
                <!-- Avatar -->
                <div class="avatar">
                    <img v-if="$page.props.auth.user.avatar_url" :src="`/storage/uploads/profile/${$page.props.auth.user.avatar_url}`" alt="avatar" />
                    <img v-else src="/img/avatar-placeholder.png" alt="default avatar" />
                </div>
                <!-- Other Profile Info -->
                <div class="other-info">
                    <div>
                        <h2 title="Full name">{{ $page.props.auth.user.name }} <span v-if="$page.props.auth.user.nickname" title="Nickname">({{ $page.props.auth.user.nickname }})</span></h2>
                        <p>{{ $page.props.auth.user.email }}</p>
                        <p class="mb-3" title="Username">{{ $page.props.auth.user.username }}</p>
                    </div>
                    <div>
                        <p><strong>Created at:</strong> {{ $page.props.auth.user.created_at }}</p>
                        <Link class="edit-link" :href="route('profile.edit')">Edit Profile</Link>
                        <Link class="change-settings" :href="route('settings')">Change Settings</Link>
                        <Link class="settings-icon" :href="route('settings')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336c44.2 0 80-35.8 80-80s-35.8-80-80-80s-80 35.8-80 80s35.8 80 80 80z"/></svg>
                        </Link>
                    </div>
                </div>
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
@import "../../../sass/app.scss";
.page-section {
    h2 {
        text-align: left;
    }
}
.profile-container {
    position: relative;
    display: flex;
    gap: 2rem;
    .avatar {
        width: 200px;
        height: 200px;
        overflow: hidden;
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    .other-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        h2 {
            font-size: 2rem;
            margin-bottom: 0;
        }
        p {
            margin-bottom: 0;
        }
    }
    .settings-icon {
        display: none;
    }
    @container page-section (min-width: 700px) {
        .edit-link {
            position: absolute;
            bottom: 0;
            right: 0;
        }
        .change-settings {
            display: none;
        }
        .settings-icon {
            display: block;
            position: absolute;
            width: 35px;
            height: 35px;
            top: 0;
            right: 0;
            svg {
                fill: #8b8b8b;
                transition: .3s ease-in-out;
            }
            &:hover {
                svg {
                    fill: #000;
                    rotate: 90deg;
                }
            }
        }
    }
}
</style>