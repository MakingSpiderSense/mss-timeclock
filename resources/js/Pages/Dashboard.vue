<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <!-- Top Row -->
            <div class="top-row mb-10 text-right">
                <!-- Clocked in state -->
                <div v-if="clockedInState" class="current-category">
                    Clocked into <em>{{ clockedInCategory }}</em> → <em>{{ clockedInSubcategory }}</em>
                </div>
                <!-- "Enter Manual Time" -->
                <div class="manual-entry">
                    <div id="manual-time-display"></div>
                    <a href="#" @click="modalEnterManualTime"><i class="fas fa-clock"></i> Enter Manual Time</a>
                    <InputError class="mt-2" :message="form.errors.manualTime" />
                </div>
            </div>
            <!-- Form -->
            <form @submit.prevent="submit" autocomplete="off">
                <!-- Hidden Field: Manual Time -->
                <TextInput id="manualTime" type="hidden" v-model="form.manualTime" />
                <!-- Form Input -->
                <div class="clockin-form-input">
                    <!-- Column 1 -->
                    <div>
                        <!-- Category Text Input -->
                        <div>
                            <InputLabel for="category" value="Category" />
                            <div class="autocomplete">
                                <TextInput id="category" type="text" class="mt-1 block w-full" v-model="form.category" @input="updateSubcategoryOptions" :disabled="clockedInState" tabindex="1" required autofocus />
                            </div>
                            <InputError class="mt-2" :message="form.errors.category" />
                        </div>
                        <!-- Category Dropdown Select -->
                        <div class="mb-4">
                            <div style="padding-top: 24px"></div>
                            <select class="border-gray-300 shadow-sm rounded-md" name="category_options" id="category_options" tabindex="2" @change="categoryOptionsChanged" :disabled="clockedInState">
                                <option disabled selected value>-- Select --</option>
                                <option v-for="category in filteredCategoriesArray" v-bind:key="category">{{ category }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.category_options" />
                        </div>
                        <!-- "Hide Category" button -->
                        <div v-if="isCategoryFound">
                            <a href="#" @click="hideCategory('category')">Hide Category</a>
                        </div>
                    </div>
                    <!-- Column 2 -->
                    <div>
                        <!-- Subcategory / Recent Mode -->
                        <div>
                            <!-- Subcategory Text Input -->
                            <InputLabel :for="showRecentOptions ? 'recent_subcategory' : 'subcategory'" :value="showRecentOptions ? 'Recent' : 'Subcategory'" />
                            <div class="autocomplete">
                                <TextInput id="subcategory" type="text" class="mt-1 block w-full" v-model="form.subcategory" tabindex="3" :disabled="clockedInState || showRecentOptions" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.subcategory" />
                            <!-- Recent Subcategory Dropdown Select -->
                            <div v-if="showRecentOptions" class="mb-4">
                                <div style="padding-top: 24px"></div>
                                <select class="border-gray-300 shadow-sm rounded-md w-full" id="recent_subcategory" @change="handleRecentSelection">
                                    <option selected value>-- Select --</option>
                                    <option v-for="recent in recentSubcategories" :key="recent.id">
                                        {{ recent.category.organization.name }} → {{ recent.category.name }} → {{ recent.name }}
                                    </option>
                                </select>
                            </div>
                            <!-- Normal Subcategory Dropdown Select -->
                            <div v-else class="mb-4">
                                <div style="padding-top: 24px"></div>
                                <select class="border-gray-300 shadow-sm rounded-md" name="subcategory_options" id="subcategory_options" tabindex="4" @change="subcategoryOptionsChanged" :disabled="clockedInState">
                                    <option disabled selected value>-- Select --</option>
                                    <option v-for="subcategory in filteredSubcategoriesArray" v-bind:key="subcategory">{{ subcategory }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.subcategory_options" />
                            </div>
                            <!-- "Hide Subcategory" button -->
                            <div v-if="isSubcategoryFound">
                                <a href="#" @click="hideCategory('subcategory')">Hide Subcategory</a>
                            </div>
                        </div>
                    </div>
                    <!-- Column 3 -->
                    <div>
                        <!-- Notes -->
                        <div class="mb-4">
                            <InputLabel for="notes" value="Notes" />
                            <Textarea id="notes" class="mt-1 w-full" v-model="form.notes" style="height:109px;" tabindex="5" />
                            <InputError class="mt-2" :message="form.errors.notes" />
                        </div>
                    </div>
                </div>
                <!-- Submit "Clock In/Out" -->
                <div class="cfi-submit flex items-center justify-end mt-4">
                    <a v-if="clockedInState" as="button" @click="cancelClockIn" class="cancel clockin-btn ml-4">
                        Cancel
                    </a>
                    <PrimaryButton title="Shortcut: Alt + C" class="clockin-btn ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="confirmCreation($event)">
                        {{ clockInOutButton }}
                    </PrimaryButton>
                </div>
            </form>

        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { usePage } from '@inertiajs/inertia-vue3';

const form = useForm({
    manualTime: '',
    org_id: '',
    category: '',
    subcategory: '',
    notes: '',
});
const submit = () => {
    // If the category is contains to "aycove", then alert "test"
    if (form.category.toLowerCase().includes('aycove')) {
        alert("Don't forget to set yourself as active/inactive in Slack!");
    }
    // Submit the form
    form.post(route('clock-in-out'), {
        onFinish: () => form.reset('manualTime', 'org_id', 'category', 'subcategory', 'notes'),
    });
};

export default {
    props: {
        categoriesObj: Object,
        filteredCategoriesObj: Object,
        recentSubcategories: Array,
    },
    data() {
        return {
            categoriesArray: [],
            subcategoriesArray: [],
            filteredCategoriesArray: [],
            filteredSubcategoriesArray: [],
            pageModal: {
                title: 'ManualTimeSet',
                count: 0,
            },
            clockedInState: usePage().props.value.auth.clocked_in,
            clockedInCategory: usePage().props.value.auth.temp_log.row ? usePage().props.value.auth.temp_log.row.subcategory.category.name : '',
            clockedInSubcategory: usePage().props.value.auth.temp_log.row ? usePage().props.value.auth.temp_log.row.subcategory.name : '',
            isCategoryFound: false,
            isSubcategoryFound: false,
        }
    },
    mounted() {
        window.addEventListener('keydown', this.handleKeydown);
        this.updateCategoryOptions();
        this.getNotes();
    },
    beforeUnmount() {
        window.removeEventListener('keydown', this.handleKeydown);
    },
    updated() {
        this.updateCategoryOptions();
        this.changeClockInOutState();
        this.clearManualTime();
        this.clockedInCategory = usePage().props.value.auth.temp_log.row ? usePage().props.value.auth.temp_log.row.subcategory.category.name : '';
        this.clockedInSubcategory = usePage().props.value.auth.temp_log.row ? usePage().props.value.auth.temp_log.row.subcategory.name : '';
    },
    computed: {
        clockInOutButton() {
            return this.clockedInState ? 'Clock Out' : 'Clock In';
        },
        // Check if we should show the recent options
        showRecentOptions() {
            return !this.clockedInState && form.category.trim() === '';
        },
    },
    methods: {
        // Perform any actions when user clocks in or out
        changeClockInOutState() {
            this.clockedInState = usePage().props.value.auth.clocked_in;
        },
        // Confirm creation of new category or subcategory
        confirmCreation(event) {
            // If already clocked in, submit and return
            if (this.clockedInState) {
                submit();
                return;
            }
            // Update the form's org_id to the current org_id
            form.org_id = this.org_id;
            // Otherwise, check if category and subcategory exist
            let categoryExists = false;
            let subcategoryExists = false;
            const newCategory = form.category;
            const newSubCategory = form.subcategory ? form.subcategory : "Other";
            // Exit if no category is entered
            if (newCategory == "") {
                console.warn("Please enter a category.");
                return;
            }
            // Check if form.category is in this.categoriesArray, if so, set categoryExists to true
            if (this.categoriesArray.includes(newCategory)) {
                categoryExists = true;
            }
            // Check if form.subcategory is in this.subcategoriesArray, if so, set subcategoryExists to true
            if (this.subcategoriesArray.includes(newSubCategory)) {
                subcategoryExists = true;
            } else {
                // If subcategory does not exist, but is "Other", then set subcategoryExists to true
                if (newSubCategory === "Other") {
                    subcategoryExists = true;
                }
            }
            // Compose confirm messages based on categoryExists and subcategoryExists
            let confirmMessage = "";
            if (categoryExists && subcategoryExists) {
                // Submit the form
                submit();
                return;
            } else if (categoryExists && !subcategoryExists) {
                confirmMessage = `Are you sure you want to create the new subcategory, "${newSubCategory}"?`;
            } else if (!categoryExists && subcategoryExists) {
                confirmMessage = `Are you sure you want to create the new category, "${newCategory}"?`;
            } else {
                confirmMessage = `Are you sure you want to create the new category, "${newCategory}", and the new subcategory, "${newSubCategory}"?`;
            }
            if (!confirm(confirmMessage)) {
                console.log("Cancel");
                // Prevent the form from submitting
                event.preventDefault();
            } else {
                console.log("Continue");
                // Submit the form
                submit();
            }
        },
        // Cancel clock in
        cancelClockIn(e) {
            e.preventDefault();
            form.post(route('cancel-clock-in'));
        },
        // Clear the manual time field
        clearManualTime() {
            form.manualTime = '';
            document.getElementById("manual-time-display").innerHTML = '';
        },
        // Pull in the notes from the clock in record
        getNotes() {
            const notesDB = usePage().props.value.auth.temp_log.row ? usePage().props.value.auth.temp_log.row.notes : "";
            const notesInput = document.getElementById("notes");
            notesInput.value = notesDB;
        },
        // When "Enter Manual Time" is clicked, display the modal
        modalEnterManualTime() {
            document.querySelector('.modal').style.display = 'flex';
            document.querySelector('.modal-title').innerHTML = "Enter Manual Time";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button class="btn modal-continue">Save</button>`;
            // Update modal body (the count is a workaround to trigger the watcher even if the value is the same)
            this.pageModal = {
                title: 'ManualTimeSet',
                count: this.pageModal.count + 1,
            };
            // When "Save" is clicked, update the manual time
            modalFooter.querySelector('button').addEventListener('click', saveTime);
            function saveTime() {
                const manualTime = document.getElementById("manual-time-input").value;
                const manualTimeFormatted = new Date(`2021-01-01T${manualTime}:00`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
                // Get the current date and time and format it (e.g. "YYYY-MM-DD HH:MM:SS")
                const now = new Date();
                const nowFormatted = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
                // Get the user's timezone
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                // Update the date/time to keep the date, but change the time to the manual time
                const nowFormattedSplit = nowFormatted.split(" ");
                const nowFormattedDate = nowFormattedSplit[0];
                const manualTimeSplit = manualTime.split(":");
                const manualTimeHours = manualTimeSplit[0];
                const manualTimeMinutes = manualTimeSplit[1];
                const manualTimeSeconds = "00";
                const nowFormattedNew = `${nowFormattedDate} ${manualTimeHours}:${manualTimeMinutes}:${manualTimeSeconds}`;
                // Display the manual time back to the user
                const manualTimeDisplay = document.getElementById("manual-time-display");
                manualTimeDisplay.innerHTML = `${nowFormattedNew + " " + timezone} (${manualTimeFormatted})`;
                manualTimeDisplay.title = "The currently set manual time";
                // Convert the date to UTC
                let dateTimeUTC = (new Date(`${nowFormattedNew}`).toUTCString());
                // Remove " GMT" from the end of dateTimeUTC
                dateTimeUTC = dateTimeUTC.slice(0, -4);
                // Format dateTimeUTC to "YYYY-MM-DD HH:MM:SS"
                const dateTimeUTCFormatted = new Date(dateTimeUTC).getFullYear() + "-" + twoDigits((new Date(dateTimeUTC).getMonth() + 1)) + "-" + twoDigits(new Date(dateTimeUTC).getDate()) + " " + twoDigits(new Date(dateTimeUTC).getHours()) + ":" + twoDigits(new Date(dateTimeUTC).getMinutes()) + ":00";
                // Set the form.manualTime to the new UTC time
                form.manualTime = dateTimeUTCFormatted;
                // Close modal and remove event listener
                document.querySelector('.modal').style.display = 'none';
                modalFooter.removeEventListener('click', saveTime);
            }
            function twoDigits(n) {
                return ("0" + n).slice(-2);
            }
        },
        // Hide Category or Subcategory
        hideCategory(type) {
            const category = form.category;
            const subcategory = form.subcategory;
            const categoryTitle = type === "category" ? category : subcategory;
            let confirmMessage = `Are you sure you want to hide the ${type}, "${categoryTitle}"?`;
            if (confirm(confirmMessage)) {
                console.log(`Hide ${type}: ${categoryTitle}`);
                form.post(route('hide-category', type));
            }
        },
        // Runs when the category or subcategory inputs are updated
        inputsUpdated() {
            this.isCategoryFound = this.categoriesArray.includes(form.category);
            this.isSubcategoryFound = this.subcategoriesArray.includes(form.subcategory);
        },
        // Update category select list
        // Note: This function is run when the page loads or when the organization is changed
        updateCategoryOptions() {
            // If triggered by the modal, return
            if (document.querySelector('.modal').style.display === 'flex') {
                return;
            }
            // Clear the category and subcategory inputs
            this.clearCategoryInput();
            this.clearSubcategoryInput();
            // Create categoriesFullArray from categories object with the name of each category
            this.categoriesFullArray = [];
            this.categoriesObj.forEach((category, index) => {
                // If first iteration, set the org_id
                if (index === 0) {
                    this.org_id = category.org_id;
                }
                // Create subcategories array from each category
                let subcategories = [];
                console.log(category); // Leave this here for debugging
                category.subcategories.forEach(subcategory => {
                    subcategories.push(subcategory.name);
                });
                this.categoriesFullArray.push([category.name, subcategories]);
            });
            this.categoriesArray = this.categoriesFullArray.map(value => value[0]);
            // Create filteredCategoriesFullArray from filtered categories object with the name of each category
            this.filteredCategoriesFullArray = [];
            Object.values(this.filteredCategoriesObj).forEach(category => {
                // Create subcategories array from each category
                let subcategories = [];
                Object.values(category.subcategories).forEach(subcategory => {
                    subcategories.push(subcategory.name);
                });
                this.filteredCategoriesFullArray.push([category.name, subcategories]);
            });
            this.filteredCategoriesArray = this.filteredCategoriesFullArray.map(value => value[0]);
            // Update the autocomplete lists
            this.autocomplete(document.getElementById("category"), this.categoriesArray);
            this.autocomplete(document.getElementById("subcategory"), this.subcategoriesArray);
        },
        // When category is changed, update the subcategory select list
        updateSubcategoryOptions() {
            // Clear the subcategory input
            this.clearSubcategoryInput();
            const category = document.getElementById("category").value;
            this.isCategoryFound = this.categoriesArray.includes(category);
            if (this.categoriesArray.includes(category)) {
                // If so, filter the categoriesFullArray array to get the subcategories array of the selected category
                this.subcategoriesArray = this.categoriesFullArray.filter(value => value[0] === category)[0][1];
                // Also, filter the filteredCategoriesFullArray array to get the subcategories array of the selected category
                if (this.filteredCategoriesFullArray.some(value => value[0] === category)) {
                    // If the selected category is not hidden, update the filteredSubcategoriesArray array
                    this.filteredSubcategoriesArray = this.filteredCategoriesFullArray.filter(value => value[0] === category)[0][1];
                } else {
                    this.filteredSubcategoriesArray = [];
                }
            } else {
                this.subcategoriesArray = [];
            }
            this.autocomplete(document.getElementById("subcategory"), this.subcategoriesArray);
        },
        // When #category_options is changed, update form.category and subcategory options
        categoryOptionsChanged() {
            const categoryInput = document.getElementById("category");
            const categoryOptions = document.getElementById("category_options");
            this.isCategoryFound = this.categoriesArray.includes(categoryInput);
            if (categoryOptions.value) {
                form.category = categoryOptions.value;
                categoryInput.value = categoryOptions.value;
                this.updateSubcategoryOptions();
            }
            // Add scrolling for mobile devices
            const clockinForm = document.querySelector('.clockin-form-input');
            if (clockinForm.offsetWidth <= 550) {
                const clockInButton = document.querySelector('.clockin-btn');
                clockInButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },
        // When #subcategory_options is changed, update form.subcategory
        subcategoryOptionsChanged() {
            const subcategoryOptions = document.getElementById("subcategory_options");
            this.isSubcategoryFound = this.subcategoriesArray.includes(subcategoryOptions.value);
            if (subcategoryOptions.value) {
                form.subcategory = subcategoryOptions.value;
            }
            // Add scrolling for mobile devices
            const clockinForm = document.querySelector('.clockin-form-input');
            if (clockinForm.offsetWidth <= 550) {
                const clockInButton = document.querySelector('.clockin-btn');
                clockInButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },
        // Clear the category input
        clearCategoryInput() {
            document.getElementById("category").value = "";
            document.getElementById("category_options").value = "";
            form.category = "";
            this.updateSubcategoryOptions();
        },
        // Clear the subcategory input
        clearSubcategoryInput() {
            setTimeout(() => {
                document.getElementById("subcategory").value = "";
                const subcategoryOptions = document.getElementById("subcategory_options");
                if (subcategoryOptions) {
                    subcategoryOptions.value = "";
                }
                form.subcategory = "";
            }, 0); // This is to ensure it tries to clear after switching from the recent subcategory dropdown to the normal subcategory dropdown
        },
        async handleRecentSelection(event) {
            const categoryInput = document.getElementById("category");
            const selectedIndex = event.target.selectedIndex;
            const selectedOption = this.recentSubcategories[selectedIndex - 1]; // Skip the placeholder
            if (!selectedOption) return;
            const currentOrgId = this.$page.props.auth.user.active_org_id;
            const selectedOrgId = selectedOption.category.organization.id;
            // Same org? Just update form
            if (selectedOrgId === currentOrgId) {
                form.category = selectedOption.category.name;
                categoryInput.value = selectedOption.category.name;
                this.updateSubcategoryOptions();
                setTimeout(() => { form.subcategory = selectedOption.name; }, 0); // Wait for the subcategory options to update
            } else {
                // Switch org, then autofill after navigation
                this.$inertia.post(`/organization/active/${selectedOrgId}`, {}, {
                    onSuccess: () => {
                        document.querySelector('select[name="organization"]').value = selectedOrgId;
                        form.category = selectedOption.category.name;
                        categoryInput.value = selectedOption.category.name;
                        this.updateSubcategoryOptions();
                        setTimeout(() => { form.subcategory = selectedOption.name; }, 0); // Wait for the subcategory options to update
                    },
                });
            }
        },
        // Autocomplete function
        autocomplete(inp, arr) {
            // Cache the this keyword so it can be used inside the event listener
            let _this = this;
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function (e) {
                _this.inputsUpdated();
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false; }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function (e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            if (inp.id == "category") {
                                form.category = inp.value;
                                _this.updateSubcategoryOptions();
                            } else if (inp.id == "subcategory") {
                                form.subcategory = inp.value;
                            }
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function (e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });
            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }
            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }
            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
        },
        // Keyboard shortcuts
        handleKeydown(event) {
            // Alt + C: Clock in/out
            if (event.altKey && event.code === 'KeyC') {
                // Submit after confirming creation
                this.confirmCreation(event);
            }
        },
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
.current-category {
    margin-bottom: 10px;
}
.manual-entry {
    i {
        margin-right: 5px;
    }
}
.clockin-form-input {
    display: flex;
    justify-content: space-between;
    gap: 25px;
    container: inline-size;
    > div {
        width: 100%;
    }
    @container (max-width: 550px) {
        flex-direction: column;
    }
}
.clockin-btn {
    padding: 20px 30px;
    font-size: 20px;
}
.clockin-btn.cancel:hover {
    color: $black;
    cursor: pointer;
}
select {
    display: block;
    width: 100%;
}
.cfi-submit {
    container: inline-size;
    @container (max-width: 415px) {
        flex-direction: column-reverse;
        a, button {
            margin-left: 0;
        }
    }
}
</style>