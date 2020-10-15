<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
            @click="modal"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg text-primary">Help</div>
                        <span class="ripple ml-auto rounded hover:bg-gray-300 p-1" @click="modal()">
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
                    <span v-if="msg1 != ''">
                        <div>{{ msg1 }}</div></span
                    >
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: { status: Number },
    data() {
        return { msg: '', msg1: '' }
    },
    methods: {
        modal() {
            this.$emit('modal')
        },
    },
    created() {
        switch (this.status) {
            case 0:
                this.msg =
                    "Now that you have succesfully set up the project, you have to upload the CSV containing the data you want your Workers to annotate. If you need to, you can upload a golden set, which allows you to test your Workers' competence. Check out the CSV status card for all the actions and options available."
                this.msg1 = 'After you have uploaded your data, you are ready to create the HITs.'
                break
            case 1:
                this.msg =
                    'Now that you have successfully created the HITs, you can set the layout of your HITs by giving a value to fill the parameters of your project. You can additionally manipulate the answers as you like and handle Workers who make a mistake annotating the golden set.'
                break
            case 2:
                this.msg =
                    'Now that you have set the layout of your HITs, you are ready to lauch your project and let the Workers annotate your data. You can either launch all HITs at once, or choose to launch a portion of them at a time.'
                break
            case 3:
                this.msg =
                    'Now that the HITs have been launched, you can either launch more if you have some left, or wait for the results.'
                break
        }
    },
}
</script>

<style></style>
