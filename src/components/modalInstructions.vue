<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-1/2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg">Confirm action</div>
                        <span class="ml-auto rounded hover:bg-gray-300 p-1" @click="modal()">
                            <svg
                                class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                        </span>
                    </div>
                    <div class="py-2">{{ msg }}</div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: { status: Number },
    data() {
        return { msg: '' }
    },
    methods: {
        modal() {
            this.$emit('modal')
        },
    },
    created() {
        switch (this.status) {
            case 0:
                this.msg = 'instructions for status 0'
                break
            case 1:
                this.msg = 'instructions for status 1'
                break
            case 2:
                this.msg = 'instructions for status 2'
                break
            case 3:
                this.msg = 'instructions for status 3'
                break
        }
    },
}
</script>

<style></style>
