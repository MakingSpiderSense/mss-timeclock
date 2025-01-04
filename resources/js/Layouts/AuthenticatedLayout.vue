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
        <!-- Mobile Icons -->
        <div class="mobile-icons">
            <!-- Hamburger Menu Button -->
            <div class="menu-icon">
                <button @click="toggleMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
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
                            <DropdownLink :href="route('settings')" as="button">
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
                            <i class="fas fa-stopwatch" style="margin-right: 14px;"></i> Clock In/Out
                        </NavLink>
                    </li>
                    <li>
                        <!-- Dropdown -->
                        <Dropdown align="left" width="48">
                            <template #trigger>
                                <button>
                                    <i class="fas fa-briefcase" style="margin-right: 12px;"></i> Manage
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
                        <!-- Dropdown -->
                        <Dropdown align="left" width="48">
                            <template #trigger>
                                <button>
                                    <i class="fas fa-folder-open" ></i>Reports
                                    <!-- Down chevron -->
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <!-- Dropdown Links -->
                            <template #content>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    Month Overview
                                </DropdownLink>
                                <DropdownLink :href="route('placeholder')" as="button">
                                    All Time Overview
                                </DropdownLink>
                                <DropdownLink :href="route('reports.time-log')" as="button">
                                    Time Log
                                </DropdownLink>
                                <DropdownLink v-if="$page.props.auth.user.id == 1 || $page.props.auth.user.id == 2" :href="route('reports.private')" as="button">
                                    MSS Private Report
                                </DropdownLink>
                            </template>
                        </Dropdown>
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
                    <div class="stats-today">
                        <h3>Today</h3>
                        <!-- Hours worked -->
                        <div><span id="hours_paid_today_combined_org" title="hours_paid_today_combined_org">0</span> Paid Work Hours</div>
                        <div><span id="hours_today_total_work_combined_org" title="hours_today_total_work_combined_org">0</span> Total Work Hours</div>
                        <div><span id="hours_today_combined_org" title="hours_today_combined_org">0</span> Overall Hours</div>
                        <div><span id="hours_today_current_org" title="hours_today_current_org">0</span> Total Hours</div>
                        <!-- Amount earned -->
                        <div class="money-positive mb-5">$<span id="amount_earned_today_current_org" title="amount_earned_today_current_org">0</span> Earned</div>
                        <div class="money-positive mb-5">$<span id="amount_earned_today_current_org_tax" title="amount_earned_today_current_org_tax">0</span> Earned</div>
                        <div class="money-positive mb-5">$<span id="amount_earned_today_combined_org" title="amount_earned_today_combined_org">0</span> Earned</div>
                        <div class="money-positive mb-5">$<span id="amount_earned_today_combined_org_tax" title="amount_earned_today_combined_org_tax">0</span> Earned</div>
                        <!-- Heart containers -->
                        <div id="goal-tracker" class="hearts hearts-two-by-three"></div>
                    </div>
                    <!-- This Month -->
                    <div class="stats-month">
                        <h3>This Month</h3>
                        <!-- Work hours -->
                        <div><span id="hours_month_paid_work_combined_org" title="hours_month_paid_work_combined_org">0</span> Paid Work Hours</div>
                        <div><span id="hours_month_total_work_combined_org" title="hours_month_total_work_combined_org">0</span> Total Work Hours</div>
                        <!-- Current org hours -->
                        <div><span id="hours_month_current_org" title="hours_month_current_org">0</span> Total Hours</div>
                        <!-- Hourly rate -->
                        <div>$<span id="rate_this_month_work_combined_org" title="rate_this_month_work_combined_org">0</span>/Hour</div>
                        <!-- Average weekly hours -->
                        <div><span id="hours_weekly_this_month_current_org" title="hours_weekly_this_month_current_org">0</span> Hours/Week</div>
                        <div><span id="hours_weekly_this_month_combined_org" title="hours_weekly_this_month_combined_org">0</span> Hours/Week</div>
                        <!-- Amount earned -->
                        <div class="money-positive">$<span id="amount_earned_month_current_org" title="amount_earned_month_current_org">0</span> Earned</div>
                        <div class="money-positive">$<span id="amount_earned_month_current_org_tax" title="amount_earned_month_current_org_tax">0</span> Earned</div>
                        <div class="money-positive">$<span id="amount_earned_month_combined_org" title="amount_earned_month_combined_org">0</span> Earned</div>
                        <div class="money-positive">$<span id="amount_earned_month_combined_org_tax" title="amount_earned_month_combined_org_tax">0</span> Earned</div>
                    </div>
                    <!-- Current Organization and Time -->
                    <div class="stats-time">
                        <!-- Organization Select -->
                        <select name="organization" @change="updateActiveOrganization()" :disabled="clockedInState" :title="organizationDropdownTitle">
                            <option v-for="organization in $page.props.auth.organizations" :key="organization.id" :value="organization.id">
                                {{ organization.name }}
                            </option>
                        </select>
                        <div class="st-state">
                            <!-- Clocked-in time -->
                            <div class="mb-5" v-if="clockedInTimeStat"><span id="clockedInTime">{{ clockedInTimeStat }}</span></div>
                            <!-- Current time on the clock -->
                            <div class="text-5xl"><span id="timeOnClock">00:00:00</span></div>
                        </div>
                    </div>
                    <!-- Stats view setting -->
                    <div class="stats-view-btns">
                        <a as="button" @click="toggleStats" class="btn-stats-toggle"><i class="fas fa-eye" style="margin-right: 5px;"></i> <span>Show Statistics</span></a>
                        <a as="button" @click="updateStatsView" class="btn-stats-view">Loading...</a>
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
import { useForm } from '@inertiajs/inertia-vue3';
const form = useForm();
export default {
    props: ['pageModal'],
    data() {
        return {
            currentModal: this.pageModal ? this.pageModal.title : "Default",
            clockedInState: usePage().props.value.auth.clocked_in,
            flashMsg: usePage().props.value.flash.message ? usePage().props.value.flash.message : ["", ""],
            stats: axios.get('/stats'),
            set_combined_org: usePage().props.value.auth.user.set_combined_org,
            goal_hours: usePage().props.value.auth.user.goal_hours,
            clockedInTime: usePage().props.value.auth.clocked_in_at ? new Date(usePage().props.value.auth.clocked_in_at) : null,
        }
    },
    mounted() {
        console.log("Mounted: " + new Date().toLocaleTimeString());
        this.stopUpdatingStats();
        this.setOptionSelected();
        // Update the time on the clock every second
        if (window.tc_second_counter_interval) {
            clearInterval(window.tc_second_counter_interval);
        }
        this.updateTimeOnClock();
        window.tc_second_counter_interval = setInterval(() => {
            this.updateTimeOnClock();
        }, 1000);
        // Update the stats every x seconds
        if (window.tc_stats_interval) {
            clearInterval(window.tc_stats_interval);
        }
        this.updateStats();
        window.tc_stats_interval = setInterval(() => {
            this.updateStats();
        }, 60000);
        // Update stats displayed based on the set_combined_org value
        this.updateStatsDisplay();
        // Reset the menu icon
        this.resetMenuIcon();
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
        clockedInState(newVal) {
            // Update the clocked in time stat
            this.updateClockedInTime();
        },
    },
    updated() {
        console.log("Updated: " + new Date().toLocaleTimeString());
        this.changeClockInOutState();
        this.flashMsg = usePage().props.value.flash.message ? usePage().props.value.flash.message : ["", ""];
        this.updateTimeOnClock();
    },
    computed: {
        // Set title attribute of organization dropdown
        organizationDropdownTitle() {
            return this.clockedInState ? "You cannot change organizations while clocked in" : "Change organization";
        },
        clockedInTimeStat() {
            // Convert the clockedInTime to the browser's local time and return in the format of "1:12 PM"
            if (this.clockedInTime) {
                const timezoneOffset = this.clockedInTime.getTimezoneOffset();
                const utcClockedInTime = new Date(this.clockedInTime.getTime() - timezoneOffset * 60 * 1000);
                return "Clocked in at " + utcClockedInTime.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
            } else {
                return "Clocked out";
            }
        },
    },
    methods: {
        // Toggle the menu
        toggleMenu() {
            const header = document.querySelector('header');
            if (header.style.left === '0px') {
                // Close menu
                header.style.left = '-100%';
                // Switch icons
                document.querySelector('.menu-icon button i').classList.remove('fa-times');
                document.querySelector('.menu-icon button i').classList.add('fa-bars');
            } else {
                // Open menu
                header.style.left = '0';
                // Switch icons
                document.querySelector('.menu-icon button i').classList.remove('fa-bars');
                document.querySelector('.menu-icon button i').classList.add('fa-times');
            }
        },
        // Reset menu icon
        resetMenuIcon() {
            const menuIcon = document.querySelector('.menu-icon button i');
            if (menuIcon.classList.contains('fa-times')) {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            }
        },
        // Toggle the stats view
        toggleStats() {
            const statsToday = document.querySelector('.stats-today');
            const statsMonth = document.querySelector('.stats-month');
            const statsViewBtn = document.querySelector('a.btn-stats-view');
            const btnStatsToggle = document.querySelector('.btn-stats-toggle span');
            const btnStatsIcon = document.querySelector('.btn-stats-toggle i');
            const timeOnClock = document.querySelector('#timeOnClock');
            if (btnStatsToggle.innerHTML === "Show Statistics") {
                statsToday.style.maxHeight = "500px";
                statsMonth.style.maxHeight = "500px";
                statsMonth.style.marginTop = "20px";
                statsViewBtn.style.display = "block";
                btnStatsToggle.innerHTML = "Hide Statistics";
                btnStatsIcon.classList.remove('fa-eye');
                btnStatsIcon.classList.add('fa-eye-slash');
            } else {
                statsToday.style.maxHeight = "0px";
                statsMonth.style.maxHeight = "0px";
                statsMonth.style.marginTop = "0px";
                statsViewBtn.style.display = "none";
                btnStatsToggle.innerHTML = "Show Statistics";
                btnStatsIcon.classList.remove('fa-eye-slash');
                btnStatsIcon.classList.add('fa-eye');
            }
        },
        // Update clockedInTime when clockedInState changes
        updateClockedInTime() {
            this.clockedInTime = usePage().props.value.auth.clocked_in_at ? new Date(usePage().props.value.auth.clocked_in_at) : null;
        },
        // If the user is clocked in, update the timeOnClock element to display the time they have been clocked in for. Then, update the time every second.
        updateTimeOnClock() {
            if (this.clockedInState) {
                // Return if this.clockedInTime is null
                if (!this.clockedInTime) return;
                const timezoneOffset = this.clockedInTime.getTimezoneOffset();
                const utcClockedInTime = new Date(this.clockedInTime.getTime() - timezoneOffset * 60 * 1000);
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
            } else {
                // If timeOnClock is set to 00:00:00, do not update it, otherwise, set it to 00:00:00
                if (document.querySelector('#timeOnClock').innerHTML != "00:00:00") {
                    document.querySelector('#timeOnClock').innerHTML = "00:00:00";
                }
            }
        },
        // Update stats section
        async updateStats() {
            try {
                // Set the stats
                const stats = await axios.get('/stats');
                this.stats = stats.data;
                document.querySelector('#hours_today_current_org').innerHTML = this.stats.hours_today_current_org
                    ? this.stats.hours_today_current_org : "0";
                document.querySelector('#hours_paid_today_combined_org').innerHTML = this.stats.hours_paid_today_combined_org
                    ? this.stats.hours_paid_today_combined_org : "0";
                document.querySelector('#hours_today_total_work_combined_org').innerHTML = this.stats.hours_today_total_work_combined_org
                    ? this.stats.hours_today_total_work_combined_org : "0";
                document.querySelector('#hours_today_combined_org').innerHTML = this.stats.hours_today_combined_org
                    ? this.stats.hours_today_combined_org : "0";
                document.querySelector('#amount_earned_today_current_org').innerHTML = this.stats.amount_earned_today_current_org
                    ? this.stats.amount_earned_today_current_org.toFixed(2) : "0";
                document.querySelector('#amount_earned_today_current_org_tax').innerHTML = this.stats.amount_earned_today_current_org_tax
                    ? this.stats.amount_earned_today_current_org_tax.toFixed(2) : "0";
                document.querySelector('#amount_earned_today_combined_org').innerHTML = this.stats.amount_earned_today_combined_org
                    ? this.stats.amount_earned_today_combined_org.toFixed(2) : "0";
                document.querySelector('#amount_earned_today_combined_org_tax').innerHTML = this.stats.amount_earned_today_combined_org_tax
                    ? this.stats.amount_earned_today_combined_org_tax.toFixed(2) : "0";
                document.querySelector('#hours_month_paid_work_combined_org').innerHTML = this.stats.hours_month_paid_work_combined_org
                    ? this.stats.hours_month_paid_work_combined_org.toFixed(2) : "0";
                document.querySelector('#hours_month_total_work_combined_org').innerHTML = this.stats.hours_month_total_work_combined_org
                    ? this.stats.hours_month_total_work_combined_org : "0";
                document.querySelector('#rate_this_month_work_combined_org').innerHTML = this.stats.rate_this_month_work_combined_org
                    ? this.stats.rate_this_month_work_combined_org.toFixed(2) : "0";
                document.querySelector('#hours_month_current_org').innerHTML = this.stats.hours_month_current_org
                    ? this.stats.hours_month_current_org : "0";
                document.querySelector('#hours_weekly_this_month_current_org').innerHTML = this.stats.hours_weekly_this_month_current_org
                    ? this.stats.hours_weekly_this_month_current_org : "0";
                document.querySelector('#hours_weekly_this_month_combined_org').innerHTML = this.stats.hours_weekly_this_month_combined_org
                    ? this.stats.hours_weekly_this_month_combined_org : "0";
                document.querySelector('#amount_earned_month_current_org').innerHTML = this.stats.amount_earned_month_current_org
                    ? this.stats.amount_earned_month_current_org.toFixed(2) : "0";
                document.querySelector('#amount_earned_month_current_org_tax').innerHTML = this.stats.amount_earned_month_current_org_tax
                    ? this.stats.amount_earned_month_current_org_tax.toFixed(2) : "0";
                document.querySelector('#amount_earned_month_combined_org').innerHTML = this.stats.amount_earned_month_combined_org
                    ? this.stats.amount_earned_month_combined_org.toFixed(2) : "0";
                document.querySelector('#amount_earned_month_combined_org_tax').innerHTML = this.stats.amount_earned_month_combined_org_tax
                    ? this.stats.amount_earned_month_combined_org_tax.toFixed(2) : "0";
                // Update the goal tracker
                this.updateGoalTracker();
            } catch (error) {
                console.error("Failed to update stats:", error);
            }
        },
        // Stop updating stats
        stopUpdatingStats() {
            clearInterval(this.intervalId);
        },
        // Update stats view setting
        updateStatsView(e) {
            e.preventDefault();
            // Update button label
            this.$inertia.post(route('settings.stats-view'), {}, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    // Get updated value of this.set_combined_org
                    this.set_combined_org = usePage().props.value.auth.user.set_combined_org;
                    setTimeout(() => {
                        this.updateStatsDisplay();
                    }, 0);
                }
            });
        },
        // Update stats displayed on the page and button label
        updateStatsDisplay() {
            const btnLabel = document.querySelector('.btn-stats-view');
            if (this.set_combined_org === "combined_org") {
                btnLabel.innerHTML = "Viewing combined org stats <span class='sm-right-arrowhead'>➤</span>";
                document.querySelector('#hours_paid_today_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_total_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_today_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_today_combined_org_tax').parentElement.style.display = "none";
                document.querySelector('#hours_month_paid_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_month_total_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#rate_this_month_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_month_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_weekly_this_month_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_weekly_this_month_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_month_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_month_combined_org_tax').parentElement.style.display = "none";
            } else if (this.set_combined_org === "combined_org_minus_tax") {
                btnLabel.innerHTML = "Viewing combined org stats (after taxes) <span class='sm-right-arrowhead'>➤</span>";
                document.querySelector('#hours_paid_today_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_total_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_today_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org_tax').parentElement.style.display = "block";
                document.querySelector('#hours_month_paid_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_month_total_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#rate_this_month_work_combined_org').parentElement.style.display = "block";
                document.querySelector('#hours_month_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_weekly_this_month_current_org').parentElement.style.display = "none";
                document.querySelector('#hours_weekly_this_month_combined_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_month_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org_tax').parentElement.style.display = "block";
            } else if (this.set_combined_org === "current_org") {
                btnLabel.innerHTML = "Viewing current org stats <span class='sm-right-arrowhead'>➤</span>";
                document.querySelector('#hours_paid_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_total_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_current_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_today_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org_tax').parentElement.style.display = "none";
                document.querySelector('#hours_month_paid_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_month_total_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#rate_this_month_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_month_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_weekly_this_month_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_weekly_this_month_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_current_org').parentElement.style.display = "block";
                document.querySelector('#amount_earned_month_current_org_tax').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org_tax').parentElement.style.display = "none";
            } else if (this.set_combined_org === "current_org_minus_tax") {
                btnLabel.innerHTML = "Viewing current org stats (after taxes) <span class='sm-right-arrowhead'>➤</span>";
                document.querySelector('#hours_paid_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_total_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_today_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_current_org_tax').parentElement.style.display = "block";
                document.querySelector('#amount_earned_today_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_today_combined_org_tax').parentElement.style.display = "none";
                document.querySelector('#hours_month_paid_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_month_total_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#rate_this_month_work_combined_org').parentElement.style.display = "none";
                document.querySelector('#hours_month_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_weekly_this_month_current_org').parentElement.style.display = "block";
                document.querySelector('#hours_weekly_this_month_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_current_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_current_org_tax').parentElement.style.display = "block";
                document.querySelector('#amount_earned_month_combined_org').parentElement.style.display = "none";
                document.querySelector('#amount_earned_month_combined_org_tax').parentElement.style.display = "none";
            } else {
                btnLabel.innerHTML = "Error: Invalid set_combined_org value";
            }
        },
        // Output goal points
        updateGoalTracker() {
            const goal_hours = this.goal_hours;
            // Total Work Hours
            const total_work_hours = this.stats.hours_today_total_work_combined_org;
            // Progress of goal hours
            const progress = (total_work_hours / goal_hours).toFixed(6);
            // Calculate numberOfHearts by multiplying goal_hours by progress, rounding down to the nearest half step.
            let numberOfHearts = Math.floor(goal_hours * progress * 2) / 2;
            numberOfHearts = numberOfHearts > goal_hours ? goal_hours : numberOfHearts;
            // Add empty heart icons to #goal-tracker for each hour in goal_hours. Fill in hearts based on progress, with full hearts for each whole hour and a half-full heart for any remaining half hour.
            const goalTracker = document.querySelector('#goal-tracker');
            // Remove all children of the goalTracker element
            while (goalTracker.firstChild) {
                goalTracker.removeChild(goalTracker.firstChild);
            }
            // Add the correct number of hearts
            for (let i = 0; i < goal_hours; i++) {
                const heart = document.createElement('div');
                heart.innerHTML = '<img src="/img/heart-icon-empty.png" alt="heart icon" style="width: 25px; margin-right: 5px;">';
                goalTracker.appendChild(heart);
            }
            // Fill in the hearts based on the progress
            for (let i = 0; i < numberOfHearts; i++) {
                const heart = goalTracker.children[i].firstChild;
                heart.src = "/img/heart-icon-full.png";
            }
            // If the numberOfHearts is a half, fill in the next heart with a half heart
            if (numberOfHearts % 1 != 0) {
                const heart = goalTracker.children[Math.floor(numberOfHearts)].firstChild;
                heart.src = "/img/heart-icon-half.png";
            }
            // Return the number of hearts
            return numberOfHearts;
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
    overflow-x: hidden; // Prevent horizontal scroll when swiping notifications
    // Header
    header {
        position: relative;
        flex-basis: 300px;
        background-color: $white;
        min-height: 100vh;
        padding: 25px;
        box-shadow: 1px 0px 28px 1px rgba(0, 0, 0, .1);
        transition: .4s ease-in-out;
        @media (max-width: 1000px) {
            position: fixed;
            left: -100%;
            width: 80vw;
            max-width: 300px;
            z-index: 1;
        }
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
                        color: $black;
                    }
                }
                i {
                    margin-right: 10px;
                    font-size: 16px;
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
    // Mobile Icons
    .mobile-icons {
        position: fixed;
        z-index: 10;
        width: 100%;
        padding: 10px;
        pointer-events: none;
        .menu-icon {
            display: flex;
            justify-content: flex-end;
            button {
                background-color: white;
                border-radius: 100%;
                aspect-ratio: 1;
                width: 100%;
                max-width: 42px;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
                pointer-events: initial;
            }
            @media (min-width: 1000px) {
                display: none;
            }
        }
    }
    // Main content
    .main-content {
        display: flex;
        flex-direction: column;
        flex-basis: 100%;
        padding: 30px;
        @media (max-width: 500px) {
            padding: 20px;
        }
        .page-container {
            container-type: inline-size;
            container-name: page-container;
            margin: 0 auto;
            width: 100%;
            max-width: 1200px;
        }
    }
    // Current Stats
    .current-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(1, 1fr);
        .stats-today {
            overflow: hidden;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }
        .stats-month {
            overflow: hidden;
            grid-column: 2 / 3;
            grid-row: 1 / 2;
        }
        .stats-time {
            text-align: right;
            grid-column: 3 / 5;
            grid-row: 1 / 2;
            .st-state > div:first-child {
                padding-left: 12px;
                color: gray;
            }
            select {
                margin-bottom: 0.3rem;
                max-width: 300px;
            }
        }
        .stats-view-btns {
            grid-column: 1 / 5;
            grid-row: 2 / 3;
            a {
                display: inline-block;
                cursor: pointer;
                margin-bottom: 5px;
                &:last-child {
                    margin-bottom: 0;
                }
            }
            @container page-container (min-width: 530px) {
                a.btn-stats-toggle {
                    display: none;
                }
                &:hover {
                    color: $black;
                }
            }
        }
        // Hearts
        .hearts {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .money-positive {
            color: green;
        }
    }
    @container page-container (max-width: 1000px) {
        .current-stats {
            justify-content: space-between;
            grid-template-columns: repeat(3, 1fr);
            .stats-time {
                grid-column: 3 / 4;
            }
        }
    }
    @container page-container (max-width: 700px) {
        .current-stats {
            grid-template-columns: 1fr 1fr; // 2 even columns
            grid-template-rows: auto auto auto; // 3 rows that are auto height
            .stats-time {
                grid-column: 1 / 3;
                grid-row: 1 / 2;
                margin-bottom: 20px;
                text-align: left;
            }
            .stats-today {
                grid-column: 1 / 2;
                grid-row: 2 / 3;
            }
            .stats-month {
                grid-column: 2 / 3;
                grid-row: 2 / 3;
            }
            .stats-view-btns {
                grid-column: 1 / 3;
                grid-row: 3 / 4;
            }
        }
    }
    @container page-container (max-width: 530px) {
        .current-stats {
            grid-template-columns: auto; // 2 even columns
            grid-template-rows: auto auto auto auto;
            justify-content: center;
            .stats-time {
                grid-column: 1 / 2;
                grid-row: 1 / 2;
                margin-bottom: 0px;
                text-align: left;
            }
            .stats-month {
                grid-column: 1 / 2;
                grid-row: 2 / 3;
                max-height: 0;
                transition: .4s;
                margin-bottom: 20px;
            }
            .stats-today {
                grid-column: 1 / 2;
                grid-row: 3 / 4;
                max-height: 0;
                transition: .4s;
            }
            .stats-view-btns {
                grid-column: 1 / 2;
                grid-row: 4 / 5;
            }
            .stats-view-btns a.btn-stats-view {
                display: none;
            }
        }
    }
}
</style>