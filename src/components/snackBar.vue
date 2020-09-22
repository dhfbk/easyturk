<template>
    <div class="absolute right-0 top-1 m-5 z-30">
        <div
            class="flex items-center bg-green-500 border-l-4 border-green-700 py-2 px-3 shadow-md mb-2"
            v-if="type == 'success'"
        >
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
            <div class="text-white max-w-xs">{{msg}}</div>
        </div>

        <!--
            //INFO
        <div
            class="flex items-center bg-blue-400 border-l-4 border-blue-700 py-2 px-3 shadow-md mb-2"
        >
            <div class="text-blue-500 rounded-full bg-white mr-3">
                <svg
                    width="1.8em"
                    height="1.8em"
                    viewBox="0 0 16 16"
                    class="bi bi-info"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"
                    />
                    <circle cx="8" cy="4.5" r="1" />
                </svg>
            </div>
            <div class="text-white max-w-xs">ini pesan ketika ada informasi</div>
        </div>

        //WARNING
        <div
            class="flex items-center bg-orange-400 border-l-4 border-orange-700 py-2 px-3 shadow-md mb-2"
        >
            <div class="text-orange-500 rounded-full bg-white mr-3">
                <svg
                    width="1.8em"
                    height="1.8em"
                    viewBox="0 0 16 16"
                    class="bi bi-exclamation"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"
                    />
                </svg>
            </div>
            <div class="text-white max-w-xs">ini pesan ketika ada warning</div>
        </div>
        -->

        <div
            class="flex items-center bg-red-500 border-l-4 border-red-700 py-2 px-3 shadow-md mb-2"
            v-else
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
            <div class="text-white max-w-xs">{{msg}}</div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'snackBar',
    props: {
        type: String,
        msg: String
    },
}
</script>

<style>
</style>