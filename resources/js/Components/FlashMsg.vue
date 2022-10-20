<!-- Template -->
<template>
    <div :class="`flash-msg bg-${msgColor}-100 border border-${msgColor}-400 text-${msgColor}-700 px-4 py-3 mb-6 rounded relative`" role="alert">
        <!-- Preload tailwind classes -->
        <div style="display: none;" class="bg-teal-100 border-teal-400 text-teal-700 text-teal-500 bg-red-100 border-red-400 text-red-700 text-red-500 bg-yellow-100 border-yellow-400 text-yellow-700 text-yellow-500 bg-blue-100 border-blue-400 text-blue-700 text-blue-500"></div>
        <!-- Message -->
        <div class="block sm:inline">
            <slot>Flash message not found.</slot>
        </div>
        <!-- Close Button -->
        <div class="absolute top-0 bottom-0 right-0 px-4 py-3" role="button" @click="closeFlashMsg">
            <svg :class="`fill-current h-6 w-6 text-${msgColor}-500`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
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
    methods: {
        closeFlashMsg() {
            const flashMsg = document.querySelector('.flash-msg');
            flashMsg.style.display = 'none';
        }
    },
    computed: {
        msgColor() {
            return this.msgType === 'success' ? 'teal' : this.msgType === 'error' ? 'red' : this.msgType === 'warning' ? 'yellow' : 'blue';
        }
    }
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
</style>