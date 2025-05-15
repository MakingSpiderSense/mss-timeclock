<!-- Template -->
<template>
    <div>
        <div class="mb-4">Enter a manual time to use when clocking in/out. This will be override the actual time.</div>
        <input class="mb-4" id="manual-time-input" type="time">
        <div class="mb-4" id="time-difference"></div>
        <!-- Quick Add Buttons -->
        <div>
            <div class="item-group">
                <button type="button" class="btn btn-2" @click="addTime(-15)">-15 Min</button>
                <button type="button" class="btn btn-2" @click="addTime(15)">+15 Min</button>
            </div>
            <div class="item-group">
                <button type="button" class="btn btn-2" @click="addTime(-60)">-1 Hr</button>
                <button type="button" class="btn btn-2" @click="addTime(60)">+1 Hr</button>
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
            const now = new Date();
            now.setMinutes(now.getMinutes() + timeToAdd);
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
        }
    }
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
    #time-difference {
        display: inline-block;
        margin-left: 25px;
    }
    .item-group {
        display: inline-block;
    }
    .btn-2 {
        margin-right: 10px;
        margin-bottom: 10px;
        :last-child {
            margin-right: 0;
        }
    }
</style>