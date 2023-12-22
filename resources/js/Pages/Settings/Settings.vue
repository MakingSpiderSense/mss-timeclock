<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="User Settings" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <h2>User Settings</h2>
            <!-- Hidden Categories -->
            <article class="setting">
                <!-- Switch -->
                <div class="setting-toggle">
                    <label class="toggle-switch" style="display: none;">
                        <input type="checkbox" name="msssettings_dashclasses" value="true" checked="">
                        <span class="toggle-slider toggle-round"></span>
                    </label>
                </div>
                <!-- Info -->
                <div class="setting-info">
                    <div class="settings-btn-layout">
                        <div>
                            <div class="setting-name">Hidden Categories</div>
                            <div class="setting-description">Choose to hide specific categories or subcategories from the dropdown options when clocking in. This is useful if you created an old category that you no longer use.</div>
                        </div>
                        <div>
                            <button class="btn btn-1">Manage</button>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../../sass/app.scss";
.setting {
    display: flex;
    padding: 16px 15px;
    padding-right: 70px;
    border-bottom: 1px solid #eee;
    &:last-child {
        border-bottom: none;
    }
    .setting-toggle {
        flex-basis: 50px;
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 17px;
            input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            input:checked + .toggle-slider {
                background-color: #1abd0b;
            }
            input:checked + .toggle-slider:before {
                transform: translateX(13px);
            }
            .toggle-slider {
                cursor: pointer;
                position: absolute;
                top: 0; bottom: 0; left: 0; right: 0;
                background-color: #ccc;
                transition: 0.2s;
                transform: translateY(3px);
                &:before {
                    position: absolute;
                    content: "";
                    height: 13px;
                    width: 13px;
                    left: 2px;
                    bottom: 2px;
                    background-color: white;
                    -webkit-transition: 0.2s;
                    transition: 0.2s;
                }
            }
            .toggle-slider.toggle-round {
                border-radius: 17px;
                &:before {
                    border-radius: 50%;
                }
            }
        }
    }
    .setting-info {
        flex-basis: 100%;
        .setting-name {
            font-weight: bold;
        }
        .setting-description {
            margin-top: 10px;
            font-style: italic;
            color: #6f6f6f;
        }
    }
}
.settings-btn-layout {
    display: flex;
    justify-content: space-between;
    align-items: center;
    button {
        margin-left: 35px;
    }
}
</style>