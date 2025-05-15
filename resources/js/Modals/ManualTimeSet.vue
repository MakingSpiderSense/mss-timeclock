<!-- Template -->
<template>
    <div>
        <div class="mb-4">Enter a manual time to use when clocking in/out. This will be override the actual time.</div>
        <input class="mb-4" id="manual-time-input" type="time" @input="updateTimeDifference">
        <div class="mb-4" id="time-difference"></div>
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
    methods: {
        addTime(timeToAdd) {
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
            // Convert to total minutes, add the new time, then convert back
            let totalMinutes = hours * 60 + minutes + timeToAdd;
            // Handle negative times by wrapping around 24 hours
            while (totalMinutes < 0) {
                totalMinutes += 24 * 60;
            }
            // Handle times over 24 hours by wrapping
            totalMinutes = totalMinutes % (24 * 60);
            // Calculate new hours and minutes
            const newHours = Math.floor(totalMinutes / 60).toString().padStart(2, '0');
            const newMinutes = (totalMinutes % 60).toString().padStart(2, '0');
            // Update the input value
            timeInput.value = `${newHours}:${newMinutes}`;
            // Update the time difference display
            this.updateTimeDifference();
        },
        updateTimeDifference() {
            const timeInput = document.getElementById('manual-time-input');
            const timeDifferenceElement = document.getElementById('time-difference');
            // Clear when no time is set
            if (!timeInput.value) {
                timeDifferenceElement.textContent = '';
                return;
            }
            // Get current time
            const now = new Date();
            const currentHours = now.getHours();
            const currentMinutes = now.getMinutes();
            // Get manual time
            const [manualHours, manualMinutes] = timeInput.value.split(':').map(Number);
            // Calculate difference in minutes
            const currentTotalMinutes = currentHours * 60 + currentMinutes;
            const manualTotalMinutes = manualHours * 60 + manualMinutes;
            let diffMinutes = manualTotalMinutes - currentTotalMinutes;
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