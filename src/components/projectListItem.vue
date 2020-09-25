<template>
    <div class="rounded shadow-md my-2 mx-2 p-2 flex items-center flex-wrap bg-white relative">
        <div class="w-1/2 md:w-2/3 flex contenutoPrj text-center items-center flex-wrap">
            <div
                class="flex flex-col w-1/2 md:w-1/4 justify-center text-center flex-wrap my-1 sm:my-0"
            >
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
            <div
                class="flex flex-col w-1/2 md:w-1/4 justify-center text-center flex-wrap my-1 sm:my-0"
            >
                <p class="block md:hidden text-md font-bold tracking-tight">Title:</p>
                <p class>{{ projectData.title }}</p>
            </div>
            <div
                class="hidden lg:flex flex-col w-1/4 justify-center text-center flex-wrap my-1 sm:my-0"
            >
                <p class="block md:hidden text-md font-bold tracking-tight">Created:</p>
                <p class>{{ date }}</p>
            </div>
            <div
                class="hidden xl:flex flex-col w-1/4 justify-center text-center flex-wrap my-1 sm:my-0"
            >
                <p class="block md:hidden text-md font-bold tracking-tight">Last edited:</p>
                <p class>{{ lastEdited }}</p>
            </div>
        </div>
        <span class="flex-grow flex justify-end w-1/2 md:w-1/4">
            <span class="tooltip relative">
                <button
                    v-if="projectData.status == 0"
                    :disabled="baseCsvStatus == 0"
                    :class="{ 'cursor-not-allowed': baseCsvStatus == 0 }"
                    class="ripple hover:bg-blue-600 bg-primary text-white py-2 px-4 rounded m-1 focus:outline-none hidden xs:flex"
                >
                    <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                        <path
                            d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M10,2H14A2,2 0 0,1 16,4V6H20A2,2 0 0,1 22,8V13.53C20.94,12.58 19.54,12 18,12A6,6 0 0,0 12,18C12,19.09 12.29,20.12 12.8,21H4C2.89,21 2,20.1 2,19V8C2,6.89 2.89,6 4,6H8V4C8,2.89 8.89,2 10,2M14,6V4H10V6H14Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                >Create HIts</span>
            </span>
            <span class="tooltip relative">
                <button
                    @click="upload('std')"
                    class="tooltip ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden sm:flex flex-row"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="M20,6A2,2 0 0,1 22,8V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4H10L12,6H20M10.75,13H14V17H16V13H19.25L15,8.75"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                >Upload standard csv</span>
            </span>
            <span class="tooltip relative">
                <button
                    @click="upload('gld')"
                    class="ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden sm:flex flex-row"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="M1 22L2.5 17H9.5L11 22H1M13 22L14.5 17H21.5L23 22H13M6 15L7.5 10H14.5L16 15H6M23 6.05L19.14 7.14L18.05 11L16.96 7.14L13.1 6.05L16.96 4.96L18.05 1.1L19.14 4.96L23 6.05Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                >Upload gold csv</span>
            </span>
            <span class="tooltip relative">
                <button
                    @click="
                        $router.push({
                            name: 'edit',
                            params: {
                                projectId: projectData.id,
                            },
                        })
                    "
                    class="ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden md:flex flex-row"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                >Edit</span>
            </span>
            <span class="tooltip relative">
                <button
                    @click="deleteItem()"
                    class="ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden md:flex flex-row"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                >Delete</span>
            </span>
            <span v-click-outside="hide" class="block md:hidden flex align-center">
                <button
                    class="ripple-light py-2 px-2 m-1 focus:outline-none bg-white rounded"
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
                            @click="upload('std')"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md xs:hidden"
                        >Create HITs</a>
                        <a
                            @click="upload('std')"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md sm:hidden"
                        >Upload data</a>
                        <a
                            @click="upload('gld')"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white sm:hidden"
                        >Upload Gold</a>
                        <router-link
                            :to="{
                                name: 'edit',
                                params: { projectId: projectData.id },
                            }"
                            class="block md:hidden px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white sm:rounded-t-md"
                        >Edit</router-link>
                        <a
                            @click="deleteItem()"
                            class="block md:hidden px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-b-md"
                        >Delete</a>
                    </div>
                </transition>
            </span>
            <button
                class="bg-white ripple-light py-2 px-2 m-1 rounded focus:outline-none"
                @click="isOpen = !isOpen"
                v-if="projectData.status == 3"
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
            <span class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"
                ></span>
                <span
                    class="relative inline-flex justify-center items-center rounded-full h-3 w-3 bg-blue-500 text-xs text-white"
                >
                    <!-- {{ status }} -->
                </span>
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
                    class="absolute ripple-light bg-white hover:bg-gray-300 py-2 px-2 m-1 rounded focus:outline-none bottom-0 right-0"
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
            date: '',
            lastEdited: '',
            baseCsvStatus: 0,
            goldCsvStatus: 0,
        }
    },
    created() {
        this.displayWindowSize()
        window.onresize = this.displayWindowSize
        //for (let i = 0; i < this.projectData.length; i++) {}
        //controllare con un loop se ci sono HIT che attendono review
        //se ci sono isTherePending = true
        // this.isTherePending = true
        this.date = new Date(this.projectData.created_at).toLocaleDateString()
        this.lastEdited = this.projectData.updated_at
        //inserire controllo che se il projectData contiene anche il csv per base o gold cambiano i valori baseCsvStatus e goldCsvStatus nei data
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
@media screen and (max-width: 400px) {
    .contenutoPrj {
        width: 100%;
    }
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
    z-index: 100;
    left: 0;
    transition: opacity 0.3s ease-in-out;
    opacity: 0;
    transition-delay: 0.15s;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 85%;
}
</style>
