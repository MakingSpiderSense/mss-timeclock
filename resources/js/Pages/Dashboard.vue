<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    manualTime: '',
    category: '',
    subcategory: '',
    notes: '',
});

const submit = () => {
    form.post(route('dashboard'), {
        onFinish: () => form.reset('manualTime', 'category', 'subcategory', 'notes'),
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :pageModal="pageModal">
        <section class="page-section">
            <!-- "Enter Manual Time" -->
            <div class="manual-entry mb-10 text-right">
                <div id="manual-time-display"></div>
                <a href="#" @click="modalEnterManualTime">Enter Manual Time</a>
            </div>
            <!-- Form -->
            <form @submit.prevent="submit" autocomplete="off">
                <!-- Hidden Field: Manual Time -->
                <TextInput id="manualTime" type="hidden" v-model="form.manualTime" />
                <!-- Form Input -->
                <div class="clockin-form-input">
                    <!-- Column 1 -->
                    <div>
                        <div class="mb-4">
                            <InputLabel for="category" value="Category" />
                            <div class="autocomplete">
                                <TextInput id="category" type="text" class="mt-1 block w-full" v-model="form.category" tabindex="1" required autofocus />
                            </div>
                            <InputError class="mt-2" :message="form.errors.category" />
                        </div>
                        <div class="mb-4">
                            <InputLabel for="subcategory" value="Subcategory" />
                            <div class="autocomplete">
                                <TextInput id="subcategory" type="text" class="mt-1 block w-full" v-model="form.subcategory" tabindex="3" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.subcategory" />
                        </div>
                    </div>
                    <!-- Column 2 -->
                    <div>
                        <div class="mb-4">
                            <div style="padding-top: 24px"></div>
                            <select class="border-gray-300 shadow-sm rounded-md" name="category_options" id="category_options" tabindex="2" @change="categoryOptionsChanged">
                                <option v-for="category in categories" v-bind:key="category">{{ category }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.category_options" />
                        </div>
                        <div class="mb-4">
                            <div style="padding-top: 24px"></div>
                            <select class="border-gray-300 shadow-sm rounded-md" name="subcategory_options" id="subcategory_options" tabindex="4" @change="subcategoryOptionsChanged">
                                <option v-for="subcategory in subcategories" v-bind:key="subcategory">{{ subcategory }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.subcategory_options" />
                        </div>
                    </div>
                    <!-- Column 3 -->
                    <div>
                        <div class="mb-4">
                            <InputLabel for="notes" value="Notes" />
                            <Textarea id="notes" class="mt-1 w-full" v-model="form.notes" style="height:125px;" tabindex="5" />
                            <InputError class="mt-2" :message="form.errors.notes" />
                        </div>
                    </div>
                </div>
                <!-- Submit "Clock In" -->
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Clock In
                    </PrimaryButton>
                </div>
            </form>

        </section>
    </AuthenticatedLayout>
</template>

<!-- Scripts -->
<script>
export default {
    data() {
        return {
            categories: ["Apple Inc", "Advance Auto Parts", "Aetna", "Aflac", "Allstate", "Allergan", "Amazon", "American Express", "American International Group", "American Tower", "Anthem", "Aon", "Aramark", "Arch Capital Group", "Arthur J. Gallagher", "Assurant", "AT&T", "Automatic Data Processing"],
            subcategories: ["Other", "Task A1", "Task A2", "Task B1", "Task B2"],
            pageModal: {
                title: 'ManualTimeSet',
                count: 0,
            },
        }
    },
    mounted() {
        this.autocomplete(document.getElementById("category"), this.categories);
        this.autocomplete(document.getElementById("subcategory"), this.subcategories);
    },
    methods: {
        // When "Enter Manual Time" is clicked, display the modal
        modalEnterManualTime() {
            document.querySelector('.modal').style.display = 'flex';
            document.querySelector('.modal-title').innerHTML = "Enter Manual Time";
            const modalFooter = document.querySelector('.modal-footer');
            modalFooter.innerHTML = `<button class="modal-continue">Save</button>`;
            // Update modal body (the count is a workaround to trigger the watcher even if the value is the same)
            this.pageModal = {
                title: 'ManualTimeSet',
                count: this.pageModal.count + 1,
            };
            // When "Save" is clicked, update the manual time
            modalFooter.addEventListener('click', saveTime);
            function saveTime() {
                const manualTime = document.getElementById("manual-time-input").value;
                document.getElementById("manualTime").value = manualTime;
                const manualTimeFormatted = new Date(`2021-01-01T${manualTime}:00`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
                const manialTimeDisplay = document.getElementById("manual-time-display");
                manialTimeDisplay.innerHTML = `${manualTimeFormatted}`;
                manialTimeDisplay.title = "The currently set manual time";
                // Close modal and remove event listener
                document.querySelector('.modal').style.display = 'none';
                modalFooter.removeEventListener('click', saveTime);
            }
        },
        // When #category_options is changed, update #category
        categoryOptionsChanged() {
            const category = document.getElementById("category_options").value;
            document.getElementById("category").value = category;
        },
        // When #subcategory_options is changed, update #subcategory
        subcategoryOptionsChanged() {
            const subcategory = document.getElementById("subcategory_options").value;
            document.getElementById("subcategory").value = subcategory;
        },
        // Autocomplete function
        autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function (e) {
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
    },
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
@import "../../sass/app.scss";
.clockin-form-input {
    display: flex;
    justify-content: space-between;
    gap: 25px;
    > div {
        width: 100%;
    }
}
select {
    display: block;
    width: 100%;
}
</style>