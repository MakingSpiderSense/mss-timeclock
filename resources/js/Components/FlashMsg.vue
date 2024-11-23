<!-- Template -->
<template>
    <div :class="`flash-msg bg-${msgColor}-100 border border-${msgColor}-400 text-${msgColor}-700 px-4 py-3 mb-6 rounded relative`"
        role="alert" @touchstart="startSwipe" @touchmove="moveSwipe" @touchend="endSwipe" :style="{ transform: `translateX(${swipeDistance}px)` }" >
        <!-- Preload tailwind classes -->
        <div style="display: none;" class="bg-teal-100 border-teal-400 text-teal-700 text-teal-500 bg-red-100 border-red-400 text-red-700 text-red-500 bg-yellow-100 border-yellow-400 text-yellow-700 text-yellow-500 bg-blue-100 border-blue-400 text-blue-700 text-blue-500"></div>
        <!-- Close Button -->
        <div class="fm-close" role="button" @click="closeFlashMsg">
            <svg :class="`fill-current h-6 w-6 text-${msgColor}-500`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </div>
        <!-- Message -->
        <div class="block sm:inline">
            <slot>Flash message not found.</slot>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'FlashMsg',
    props: {
        msgType: String,
    },
    data() {
        return {
            swipeStartX: 0, // To store the starting X coordinate of the swipe
            swipeDistance: 0, // To track swipe distance
        };
    },
    methods: {
        closeFlashMsg() {
            const flashMsg = document.querySelector('.flash-msg');
            flashMsg.style.display = 'none';
            this.swipeDistance = 0; // Reset swipe distance
        },
        startSwipe(event) {
            // Record the initial touch position
            this.swipeStartX = event.touches[0].clientX;
        },
        moveSwipe(event) {
            // Calculate swipe distance
            const currentX = event.touches[0].clientX;
            this.swipeDistance = currentX - this.swipeStartX;
        },
        endSwipe() {
            // Determine if swipe distance is sufficient for dismissal
            const dismissalThreshold = 100; // In pixels
            if (Math.abs(this.swipeDistance) > dismissalThreshold) {
                // Swipe the rest of the way off-screen (depending on direction of swipe)
                this.swipeDistance = this.swipeDistance > 0 ? window.innerWidth : -window.innerWidth;
                // Close the message after animation
                setTimeout(this.closeFlashMsg, 300);
            } else {
                // Reset swipe distance if not dismissed
                this.swipeDistance = 0;
            }
        },
    },
    computed: {
        msgColor() {
            return this.msgType === 'success' ? 'teal' : this.msgType === 'error' ? 'red' : this.msgType === 'warning' ? 'yellow' : 'blue';
        },
    },
};
</script>

<!-- Styling -->
<style lang="scss" scoped>
.flash-msg {
    transition: 0.3s;
    .fm-close {
        float: right;
        padding-left: 5px;
    }
}
</style>