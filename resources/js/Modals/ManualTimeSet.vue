<!-- Template -->
<template>
    <div>
        <div class="mb-4">Enter a manual time to use when clocking in/out. This will be override the actual time.</div>
        <!-- Manual Time Inputs -->
        <div class="manual-time-inputs">
            <!-- Date -->
            <div class="mti-date">
                <input id="manual-date-input" type="date" aria-label="Manual date" @input="updateTimeDifference">
            </div>
            <!-- Time -->
            <div class="mti-time">
                <input class="mb-4" id="manual-time-input" type="time" aria-label="Manual time" @input="updateTimeDifference">
                <div class="mb-4" id="time-difference"></div>
            </div>
        </div>
        <!-- Quick Add Buttons -->
        <div id="quick-add-buttons">
            <div class="item-group">
                <button type="button" class="btn btn-2" @click="addTime(-5)">-5 Minutes</button>
                <button type="button" class="btn btn-2" @click="addTime(5)">+5 Minutes</button>
            </div>
            <div class="item-group">
                <button type="button" class="btn btn-2" @click="addTime(-15)">-15 Minutes</button>
                <button type="button" class="btn btn-2" @click="addTime(15)">+15 Minutes</button>
            </div>
            <div class="item-group">
                <button type="button" class="btn btn-2" @click="addTime(-60)">-1 Hour</button>
                <button type="button" class="btn btn-2" @click="addTime(60)">+1 Hour</button>
            </div>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'ManualTimeSet',
    mounted() {
        // Initialize date input with today's date
        const dateInput = document.getElementById('manual-date-input');
        const now = new Date();
        const year = now.getFullYear();
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
    },
    methods: {
        /**
         * Add time to manual entry
         *
         * Adds or subtracts minutes from the currently entered manual date and time, automatically adjusting the date if the time crosses midnight.
         *
         * @param {number} timeToAdd The number of minutes to add (positive) or subtract (negative) from the current manual time.
         */
        addTime(timeToAdd) {
            const dateInput = document.getElementById('manual-date-input');
            const timeInput = document.getElementById('manual-time-input');
            // If there's no current value, start with current time
            if (!timeInput.value) {
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                timeInput.value = `${hours}:${minutes}`;
            }
            // Parse the current input value
            const [hours, minutes] = timeInput.value.split(':').map(Number);
            // Parse the date
            const [year, month, day] = dateInput.value.split('-').map(Number);
            // Create a date object with the current manual date and time
            const currentDateTime = new Date(year, month - 1, day, hours, minutes, 0);
            // Add the time difference
            currentDateTime.setMinutes(currentDateTime.getMinutes() + timeToAdd);
            // Extract the new date and time
            const newYear = currentDateTime.getFullYear();
            const newMonth = (currentDateTime.getMonth() + 1).toString().padStart(2, '0');
            const newDay = currentDateTime.getDate().toString().padStart(2, '0');
            const newHours = currentDateTime.getHours().toString().padStart(2, '0');
            const newMinutes = currentDateTime.getMinutes().toString().padStart(2, '0');
            // Update both date and time inputs
            dateInput.value = `${newYear}-${newMonth}-${newDay}`;
            timeInput.value = `${newHours}:${newMinutes}`;
            // Update the time difference display
            this.updateTimeDifference();
        },
        /**
         * Update time difference display
         *
         * Calculates the difference between the manually entered date and time and the current date and time, then displays the formatted difference with hours and minutes on the page.
         */
        updateTimeDifference() {
            const dateInput = document.getElementById('manual-date-input');
            const timeInput = document.getElementById('manual-time-input');
            const timeDifferenceElement = document.getElementById('time-difference');
            // Clear when no time is set
            if (!timeInput.value) {
                timeDifferenceElement.textContent = '';
                return;
            }
            // Get current date and time
            const now = new Date();
            const currentYear = now.getFullYear();
            const currentMonth = (now.getMonth() + 1).toString().padStart(2, '0');
            const currentDay = now.getDate().toString().padStart(2, '0');
            const currentHours = now.getHours();
            const currentMinutes = now.getMinutes();
            // Get manual date (default to today if not set)
            const manualDate = dateInput.value || `${currentYear}-${currentMonth}-${currentDay}`;
            // Get manual time
            const [manualHours, manualMinutes] = timeInput.value.split(':').map(Number);
            // Create date objects for comparison
            const currentDateTime = new Date(currentYear, now.getMonth(), now.getDate(), currentHours, currentMinutes, 0);
            const [manualYear, manualMonth, manualDay] = manualDate.split('-').map(Number);
            const manualDateTime = new Date(manualYear, manualMonth - 1, manualDay, manualHours, manualMinutes, 0);
            // Calculate difference in milliseconds and convert to minutes
            let diffMilliseconds = manualDateTime - currentDateTime;
            let diffMinutes = Math.floor(diffMilliseconds / (1000 * 60));
            // Format the output
            const sign = diffMinutes >= 0 ? '+ ' : '- ';
            diffMinutes = Math.abs(diffMinutes);
            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;
            let diffText = sign;
            if (hours > 0) {
                diffText += hours === 1 ? '1 Hr ' : hours + ' Hrs ';
            }
            if (minutes > 0 || hours === 0) {
                diffText += minutes + ' Min';
            }
            // Create HTML with text and icon
            timeDifferenceElement.innerHTML = `${diffText} <i class="fas fa-window-close"></i>`;
            // Add click event to the icon
            const closeIcon = timeDifferenceElement.querySelector('.fa-window-close');
            if (closeIcon) {
                closeIcon.addEventListener('click', () => {
                    timeInput.value = '';
                    // Reset date to today
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = (now.getMonth() + 1).toString().padStart(2, '0');
                    const day = now.getDate().toString().padStart(2, '0');
                    dateInput.value = `${year}-${month}-${day}`;
                    this.updateTimeDifference();
                });
            }
        }
    },
}
</script>

<!-- Styling (not scoped) -->
<style lang="scss">
    @import "../../sass/app.scss";
    .manual-time-inputs {
        display: flex;
        gap: 15px;
        @media (max-width: 550px) {
            flex-direction: column;
        }
    }
    #time-difference {
        display: inline-block;
        margin-left: 25px;
        i.fa-window-close {
            color: $color2;
            margin-left: 15px;
            cursor: pointer;
        }
    }
    #quick-add-buttons {
        .item-group {
            display: inline-block;
            width: 100%;
            display: flex;
            .btn-2 {
                font-size: 14px;
                margin-right: 10px;
                margin-bottom: 10px;
                flex-basis: 50%;
                :last-child {
                    margin-right: 0;
                }
            }
        }
    }
</style>