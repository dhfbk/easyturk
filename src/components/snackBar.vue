<template>
    <div class="fixed topHalf">
        <div
            class="flex items-center bg-orange-400 border-l-4 border-orange-700 py-2 px-3 shadow-md mb-2"
            v-if="isWarning.test(msg)"
        >
            <div class="text-orange-500 rounded-full bg-white mr-3">
                <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill="evenodd"
                        d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                    />
                </svg>
            </div>
            <div class="text-white max-w-xs">{{ msg }}</div>
        </div>

        <div
            class="flex items-center bg-red-500 border-l-4 border-red-700 py-2 px-3 shadow-md mb-2"
            v-else-if="isError.test(msg)"
        >
            <div class="text-red-500 rounded-full bg-white mr-3">
                <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill="evenodd"
                        d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                    />
                </svg>
            </div>
            <div class="text-white max-w-xs">{{ msg }}</div>
        </div>
        <div
            class="flex items-center bg-blue-400 border-l-4 border-blue-700 py-2 px-3 shadow-md mb-2"
            v-else-if="isActive.test(msg)"
        >
            <div class="text-blue-500 rounded-full bg-white mr-3">
                <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill="evenodd"
                        d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                    />
                </svg>
            </div>
            <div class="text-white max-w-xs">{{ msg }}</div>
        </div>
        <div class="flex items-center bg-green-500 border-l-4 border-green-700 py-2 px-3 shadow-md mb-2" v-else>
            <div class="text-green-500 rounded-full bg-white mr-3">
                <svg
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill="evenodd"
                        d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"
                    />
                </svg>
            </div>
            <div class="text-white max-w-xs">{{ msg }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'snackBar',
    props: {
        msg: String,
        active: Boolean,
    },
    data() {
        return {
            isError: /\bError\b/,
            isWarning: /\bWarning\b/,
            isActive: /\bAll HITs\b/,
        }
    },
}
</script>

<style scoped>
.topHalf {
    top: 90%;
    left: 50%;
    -webkit-transform: translateY(50%) !important;
    transform: translateY(50%) !important;
    -webkit-transform: translateX(-50%) !important;
    transform: translateX(-50%) !important;
    z-index: 999;
}
</style>
