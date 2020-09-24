<template>
    <div class="rounded shadow-md my-2 mx-2 p-2 flex items-center flex-wrap bg-white relative">
        <div class="w-full md:w-3/5 flex contenutoPrj text-center items-center flex-wrap">
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Project Id:</p>
                <router-link
                    :to="{
                        name: 'projectView',
                        params: { projectId: projectData.id },
                    }"
                    class="focus:outline-none"
                >
                    <p>{{ projectData.id }}</p>
                </router-link>
            </div>
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Title:</p>
                <p class>{{ projectData.title }}</p>
            </div>
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Date:</p>
                <p class>{{ projectData.created_at }}</p>
            </div>
        </div>
        <span class="flex-grow flex justify-end w-2/5">
            <button
                class="tooltip relative transition duration-150 ease-in-out bg-primary hover:bg-primaryDark text-white py-2 px-4 rounded m-1 focus:outline-none hidden sm:inline-block"
            >
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path fill="white" d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z" />
                </svg>
                <span
                    class="tooltip-text bg-white border rounded border-gray-100 text-gray-700 -mt-16 -ml-2"
                >Publish</span>
            </button>

            <button
                @click="upload('std')"
                class="tooltip relative bg-gray-300 hover:bg-gray-400 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden xl:flex flex-row"
            >
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path
                        fill="rgba(26, 32, 44, 1)"
                        d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"
                    />
                </svg>
                <span class="ml-1">Standard</span>
                <span
                    class="tooltip-text bg-white border rounded border-gray-100 text-gray-700 -mt-16"
                >Upload standard csv</span>
            </button>
            <button
                @click="upload('gld')"
                class="tooltip relative bg-gray-300 hover:bg-gray-400 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden xl:flex flex-row"
            >
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path
                        fill="rgba(26, 32, 44, 1)"
                        d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"
                    />
                </svg>
                <span class="ml-1">Gold</span>
                <span
                    class="tooltip-text bg-white border rounded border-gray-100 text-gray-700 -mt-16"
                >Upload gold csv</span>
            </button>
            <span v-click-outside="hide" class="flex align-center">
                <button
                    class="py-2 px-2 m-1 focus:outline-none hover:bg-gray-300 bg-white rounded"
                    @click="dropdown = !dropdown"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        height="24"
                        viewBox="0 0 24 24"
                        width="24"
                    >
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"
                        />
                    </svg>
                </button>
                <transition name="slide-toggle">
                    <div
                        v-show="dropdown"
                        class="absolute right-0 w-40 sm:w-56 bg-white rounded-md shadow-xl z-20 mr-4 mt-12 sm:mr-20"
                    >
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md sm:hidden"
                        >Publish batch</a>
                        <a
                            @click="upload('std')"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md xl:hidden"
                        >Upload data</a>
                        <a
                            @click="upload('gld')"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-b-md xl:hidden"
                        >Upload Gold</a>
                        <router-link
                            :to="{
                                name: 'edit',
                                params: { projectId: projectData.id },
                            }"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md"
                        >Edit</router-link>
                        <a
                            @click="deleteItem()"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-b-md"
                        >Delete</a>
                    </div>
                </transition>
            </span>
            <button
                class="bg-white hover:bg-gray-300 py-2 px-2 m-1 rounded focus:outline-none"
                @click="isOpen = !isOpen"
            >
                <svg
                    class="transition duration-300 ease-in-out"
                    :class="{ 'transform  rotate-180': isOpen }"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="black"
                    width="24px"
                    height="24px"
                >
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
                </svg>
            </button>
            <span class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1" v-if="isTherePending">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"
                ></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
            </span>
        </span>
        <transition name="slide-toggle">
            <div v-if="isOpen" class="content w-full flex flex-wrap justify-center relative">
                <vue-ellipse-progress
                    :progress="(40 * 100) / 200"
                    :legend-value="40"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ 200 }}</span>
                    <p slot="legend-caption">Pending Review</p>
                </vue-ellipse-progress>
                <vue-ellipse-progress
                    :progress="(60 * 100) / 200"
                    :legend-value="60"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ 200 }}</span>
                    <p slot="legend-caption">In Progress</p>
                </vue-ellipse-progress>
                <vue-ellipse-progress
                    :progress="(100 * 100) / 200"
                    :legend-value="100"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ 200 }}</span>
                    <p slot="legend-caption">Completed</p>
                </vue-ellipse-progress>
                <button
                    class="absolute bg-white hover:bg-gray-300 py-2 px-2 m-1 rounded focus:outline-none bottom-0 right-0"
                    @click="refresh()"
                >
                    <svg
                        style="width:24px;height:24px"
                        viewBox="0 0 24 24"
                        class="transition ease-in-out"
                        :class="refreshIcon ? 'animate-spin ' : 'animate-none'"
                    >
                        <path
                            fill="currentColor"
                            d="M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z"
                        />
                    </svg>
                </button>
            </div>
        </transition>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
    props: {
        projectData: Object,
    },
    directives: {
        ClickOutside,
    },
    data() {
        return {
            isOpen: false,
            pieSize: 150,
            myWidth: 0,
            dropdown: false,
            refreshIcon: false,
            isTherePending: false,
        }
    },
    created() {
        this.displayWindowSize()
        window.onresize = this.displayWindowSize
        //for (let i = 0; i < this.projectData.length; i++) {}
        //controllare con un loop se ci sono HIT che attendono review
        //se ci sono isTherePending = true
        this.isTherePending = true
        console.log(this.projectData)
    },
    methods: {
        setPieSize() {
            if (this.myWidth < 640) {
                this.pieSize = 120
            } else {
                this.pieSize = 150
            }
        },
        displayWindowSize() {
            this.myWidth = window.innerWidth
            this.setPieSize()
        },
        hide() {
            this.dropdown = false
        },
        deleteItem() {
            if (this.dropdown) {
                this.dropdown = !this.dropdown
            }
            this.$emit('deleteThis', this.projectData.id)
        },
        upload(type) {
            if (this.dropdown) {
                this.dropdown = !this.dropdown
            }
            this.$emit('upload', [type, this.projectData.id])
        },
        refresh() {
            console.log('refreshing...')
            this.refreshIcon = true
            setTimeout(() => {
                this.refreshIcon = false
            }, 5000)
            //chiamata api per aggiornare i dati della HIT
        },
    },
}
</script>

<style scoped>
.contenutoPrj {
    flex-direction: column;
}
@media screen and (min-width: 500px) {
    .contenutoPrj {
        flex-direction: row;
    }
}
.widthElem {
    width: 33.33%;
    width: calc(100% / 3);
}
@media screen and (max-width: 500px) {
    .widthElem {
        width: auto;
    }
}
.tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 2px 6px;
    position: absolute;
    z-index: 100;
    left: 0;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
}
</style>
