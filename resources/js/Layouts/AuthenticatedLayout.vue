<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import FlashMsg from '@/Components/FlashMsg.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
</script>

<!-- Template -->
<template>
    <div class="main-layout">
        <!-- Header/Sidebar -->
        <header>
            <!-- Profile -->
            <div class="ml-profile">
                <!-- Image -->
                <div v-if="$page.props.auth.user.avatar_url" class="mlp-image" 
                    :style="`background-image: url('/storage/uploads/profile/${$page.props.auth.user.avatar_url}')`">
                </div>
                <div v-else class="mlp-image" style="background-image: url('/img/avatar-placeholder.png')"></div>
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
                            <DropdownLink :href="route('profile')" as="button">
                                View Profile
                            </DropdownLink>
                            <DropdownLink :href="route('placeholder')" as="button">
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
                                <DropdownLink preserve-state as="button" @click="modalOrgCreate">
                                    Create an organization
                                </DropdownLink>
                                <DropdownLink preserve-state as="button" @click="modalInvitesCreate">
                                    Invite to organization
                                </DropdownLink>
                                <DropdownLink preserve-state as="button" @click="modalInvitesManage">
                                    Manage Invites
                                </DropdownLink>
                                <DropdownLink :href="route('rates')" as="button">
                                    Edit hourly rates
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
            <!-- Header - Bottom -->
            <div class="header-bottom">
                <!-- Logo -->
                <div class="ml-logo">
                    <Link :href="route('dashboard')">
                        <div class="logo-text">
                            <!-- Image -->
                            <div class="lt-img">
                                <ApplicationLogo />
                            </div>
                            <!-- Text -->
                            <div class="lt-text">
                                <h1>{{ $page.props.app.name }}</h1>
                            </div>
                        </div>
                    </Link>
                </div>
                <!-- Version -->
                <div class="mss-version text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ $page.props.laravelVersion }} (PHP v{{ $page.props.phpVersion }})
                </div>
            </div>
        </header>
        <!-- Main Content -->
        <section class="main-content">
            <!-- Content -->
            <div class="page-container">
                <!-- Flash Message -->
                <div v-if="$page.props.flash.message" class="max-w-7xl mx-auto">
                    <FlashMsg :msgType="$page.props.flash.message[0]">{{ flashMsg[1] }}</FlashMsg>
                </div>
                <!-- Current Stats: Displays on all pages -->
                <section class="current-stats page-section">
                    <!-- Today -->
                    <div>
                        <h3>Today</h3>
                        <!-- Hours worked -->
                        <div><span id="todayHours">7.4</span> Hours</div>
                        <!-- Amount earned -->
                        <div class="money-positive mb-5">$<span id="todayEarned">146.85</span> Earned</div>
                        <!-- Heart containers -->
                        <div class="hearts hearts-two-by-three">
                            <div><img src="/img/heart-icon-full.png" alt="heart icon"></div>
                            <div><img src="/img/heart-icon-full.png" alt="heart icon"></div>
                            <div><img src="/img/heart-icon-full.png" alt="heart icon"></div>
                            <div><img src="/img/heart-icon-full.png" alt="heart icon"></div>
                            <div><img src="/img/heart-icon-half.png" alt="heart icon"></div>
                            <div><img src="/img/heart-icon-empty.png" alt="heart icon"></div>
                        </div>
                    </div>
                    <!-- This Month -->
                    <div>
                        <h3>This Month</h3>
                        <!-- Paid hours -->
                        <div><span id="monthPaidHours">27.3</span> Paid Hours ($<span id="monthHourlyRatePaid">61</span>/Hour)</div>
                        <!-- Unpaid hours -->
                        <div><span id="monthUnpaidHours">43.2</span> Unpaid Hours</div>
                        <!-- Hourly rate -->
                        <div>$<span id="monthHourlyRate">43</span>/Hour</div>
                        <!-- Average weekly hours -->
                        <div><span id="monthWeeklyHours">0</span> Hours/Week</div>
                        <!-- Amount earned -->
                        <div class="money-positive">$<span id="monthAmountEarned">0</span> Earned</div>
                    </div>
                    <!-- Current Organization and Time -->
                    <div class="text-right">
                        <!-- Organization Select -->
                        <select class="mb-4" name="organization" @change="updateActiveOrganization()" :disabled="clockedInState" :title="organizationDropdownTitle">
                            <option v-for="organization in $page.props.auth.organizations" :key="organization.id" :value="organization.id">
                                {{ organization.name }}
                            </option>
                        </select>
                        <div class="text-right">
                            <!-- Clocked-in time -->
                            <div class="mb-5">Clocked in at <span id="clockedInTime">1:12pm</span></div>
                            <!-- Current time on the clock -->
                            <div class="text-5xl"><span id="timeOnClock">00:00:00</span></div>
                        </div>
                    </div>
                </section>
                <slot />
            </div>
        </section>
        <!-- Modal -->
        <Modal :currentModal="currentModal"></Modal>
    </div>
</template>


<!-- Scripts -->
<script>
import { usePage } from '@inertiajs/inertia-vue3';
export default {
    props: ['pageModal'],
    data() {
        return {
            currentModal: this.pageModal ? this.pageModal.title : "Default",
            clockedInState: usePage().props.value.auth.clocked_in,
            flashMsg: usePage().props.value.flash.message ? usePage().props.value.flash.message : ["", ""],
            // stats: usePage().props.value.auth.stats,
            stats: axios.get('/stats'),
        }
    },
    mounted() {
        console.log('mounted');
        this.stopUpdatingStats();
        this.setOptionSelected();
        this.updateTimeOnClock();
        if (window.tc_stats_interval) {
            clearInterval(window.tc_stats_interval);
        }
        this.updateStats();
        window.tc_stats_interval = setInterval(() => {
            this.updateStats();
        }, 5000);
    },
    watch: {
        // Watch for changes to the pageModal prop
        pageModal(newVal) {
            // Set currentModal to the new value
            this.currentModal = newVal.title;
        },
        flashMsg(newVal) {
            // Display the flash message
            if (document.querySelector('.flash-msg')) {
                document.querySelector('.flash-msg').style.display = 'block';
            }
        },
    },
    updated() {
        this.changeClockInOutState();
        this.flashMsg = usePage().props.value.flash.message ? usePage().props.value.flash.message : ["", ""];
        this.updateTimeOnClock();
    },
    computed: {
        // Set title attribute of organization dropdown
        organizationDropdownTitle() {
            return this.clockedInState ? "You cannot change organizations while clocked in" : "Change organization";
        }
    },
    methods: {
        // If the user is clocked in, update the timeOnClock element to display the time they have been clocked in for. Then, update the time every second.
        updateTimeOnClock() {
            // console.log(this.clockedInState);
            if (this.clockedInState) {
                // Get the time the user clocked in at
                const clockedInTime = usePage().props.value.auth.clocked_in_at ? new Date(usePage().props.value.auth.clocked_in_at) : null;
                // Return if clockedInTime is null
                if (!clockedInTime) return;
                const timezoneOffset = clockedInTime.getTimezoneOffset();
                const utcClockedInTime = new Date(clockedInTime.getTime() - timezoneOffset * 60 * 1000);
                // Get the current time
                const currentTime = new Date();
                // Calculate the time difference between the two
                const timeDifference = currentTime - utcClockedInTime;
                // Convert the time difference to hours, minutes, and seconds
                const hours = Math.floor(timeDifference / 1000 / 60 / 60);
                const minutes = Math.floor(timeDifference / 1000 / 60) - (hours * 60);
                const seconds = Math.floor(timeDifference / 1000) - (hours * 60 * 60) - (minutes * 60);
                // Display the time on the clock
                document.querySelector('#timeOnClock').innerHTML = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                // Update the time on the clock every second
                setTimeout(this.updateTimeOnClock, 1000);
            } else {
                // If timeOnClock is set to 00:00:00, do not update it, otherwise, set it to 00:00:00
                if (document.querySelector('#timeOnClock').innerHTML != "00:00:00") {
                    document.querySelector('#timeOnClock').innerHTML = "00:00:00";
                }
            }
        },
        // Update stats section
        async updateStats() {
            // Set the stats
            console.log('updated stats');
            const stats = await axios.get('/stats');
            this.stats = stats.data;
            document.querySelector('#monthWeeklyHours').innerHTML = this.stats.hours_weekly_this_month_combined_org ? this.stats.hours_weekly_this_month_combined_org : "0";
            document.querySelector('#monthAmountEarned').innerHTML = this.stats.amount_earned_month_combined_org ? this.stats.amount_earned_month_combined_org : "0";
        },
        // Stop updating stats
        stopUpdatingStats() {
            clearInterval(this.intervalId);
        },
        // Perform any actions when user clocks in or out
        changeClockInOutState() {
            this.clockedInState = usePage().props.value.auth.clocked_in;
        },
        // Swap modal body to OrgCreate
        modalOrgCreate() {
            this.currentModal = "OrgCreate";
            document.querySelector('.modal').style.display = "flex";
            document.querySelector('.modal-title').innerHTML = "Create Organization";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button form="form_org_create" class="btn modal-continue" type="submit">Save</button>`;
            modalFooter.querySelector('button').addEventListener('click', saveOrg);
            function saveOrg() {
                // Close modal and remove event listener
                if (document.querySelector('#org_name').checkValidity()) {
                    document.querySelector('.modal').style.display = 'none';
                    modalFooter.removeEventListener('click', saveOrg);
                }
            }
        },
        // Swap modal body to InvitesManage
        modalInvitesManage() {
            this.currentModal = "InvitesManage";
            document.querySelector('.modal').style.display = "flex";
            document.querySelector('.modal-title').innerHTML = "Manage Invites";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button class="btn modal-close">Close</button>`;
            modalFooter.querySelector('button').addEventListener('click', closeModal);
            function closeModal() {
                // Close modal and remove event listener
                document.querySelector('.modal').style.display = 'none';
                modalFooter.removeEventListener('click', closeModal);
            }
        },
        // Swap modal body to InviteCreate
        modalInvitesCreate() {
            this.currentModal = "InvitesCreate";
            document.querySelector('.modal').style.display = "flex";
            document.querySelector('.modal-title').innerHTML = "Invite to Organization";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button form="form_org_invite" class="btn modal-continue" type="submit">Save</button>`;
            modalFooter.querySelector('button').addEventListener('click', saveInvite);
            function saveInvite() {
                // Close modal and remove event listener
                if (document.querySelector('#invite_email').checkValidity()) {
                    document.querySelector('.modal').style.display = 'none';
                    modalFooter.removeEventListener('click', saveInvite);
                }
            }
        },
        // Update active organization by posting to /organization/active/{id}
        updateActiveOrganization() {
            const flashMsg = document.querySelector('.flash-msg');
            const organizationId = document.querySelector('select[name="organization"]').value;
            // Restore flash message
            if (flashMsg) { flashMsg.style.display = 'block'; }
            // Make change to active organization in database
            this.$inertia.post(`/organization/active/${organizationId}`);
        },
        // Set the option to 'selected' if the organization is the active organization
        setOptionSelected() {
            const activeOrgId = this.$page.props.auth.user.active_org_id;
            const options = document.querySelectorAll('select[name="organization"] option');
            options.forEach(option => {
                if (option.value == activeOrgId) {
                    option.setAttribute('selected', 'selected');
                } else {
                    option.removeAttribute('selected');
                }
            });
        },
    },
}
</script>


<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
.main-layout {
    display: flex;
    background-color: $light-gray;
    // Header
    header {
        position: relative;
        flex-basis: 300px;
        background-color: $white;
        min-height: 100vh;
        padding: 25px;
        box-shadow: 1px 0px 28px 1px rgba(0, 0, 0, .1);
        // Profile
        .ml-profile {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            margin-bottom: 50px;
            // Image
            .mlp-image {
                min-width: 70px;
                min-height: 70px;
                border-radius: 50%;
                background-size: cover;
                background-position: center;
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
        // Header - Bottom
        .header-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 25px;
            // Logo
            .ml-logo {
                width: 100%;
                margin: 0 auto;
                margin-bottom: 15px;
                .logo-text {
                    .lt-img {
                        max-width: 60px;
                    }
                    .lt-text {
                        h1 {
                            text-align: left;
                            font-size: 1.2rem;
                        }
                    }
                }
            }
            // Version
            .mss-version {
                text-align: center;
            }
        }
    }
    // Main content
    .main-content {
        display: flex;
        flex-basis: 100%;
        padding: 30px;
        .page-container {
            margin: 0 auto;
            width: 100%;
            max-width: 1200px;
        }
    }
    // Current Stats
    .current-stats {
        display: flex;
        justify-content: space-between;
        // Hearts
        .hearts {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            img {
                width: 25px;
                margin-right: 5px;
            }
        }
        .money-positive {
            color: green;
        }
    }
}
</style>