<!-- Template -->
<template>
    <div>
        <div class="mb-4">Enter a manual time to use when clocking in/out. This will be override the actual time.</div>
        <input id="manual-time-input" type="time">
        <!-- Quick Add Buttons -->
        <div class="mt-4">
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