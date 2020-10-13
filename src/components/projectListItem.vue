<template>
    <div
        class="rounded shadow-md transition duration-150 my-4 mx-2 py-2 px-4 flex items-center flex-wrap bg-white relative hover:shadow-lg cursor-pointer"
        @click="openProject"
    >
        <div
            class="flex contenutoPrj text-center items-center flex-wrap text-md w-full xs2:w-1/2 md:w-2/3 focus:outline-none"
        >
            <div class="flex flex-col w-1/2 md:w-1/4 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden font-bold tracking-tight">Project Id:</p>
                <p>{{ projectData.id }}</p>
            </div>
            <div class="block w-1/2 md:w-1/4 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden font-bold tracking-tight">Title:</p>
                <p class="overflow-ellipsis">{{ projectData.title }}</p>
            </div>
            <div class="hidden lg:flex flex-col w-1/4 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden font-bold tracking-tight">Created:</p>
                <p>{{ date }}</p>
            </div>
            <div class="hidden xl:flex flex-col w-1/4 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden font-bold tracking-tight">Last edited:</p>
                <p>{{ lastEdited }}</p>
            </div>
        </div>
        <span class="flex-grow flex justify-end md:w-1/4">
            <span class="tooltip relative" v-if="projectData.status < 1">
                <button
                    @click.stop="upload('std')"
                    class="tooltip ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden sm:flex flex-row"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >Upload standard csv</span
                >
            </span>
            <span class="tooltip relative" v-if="projectData.status < 1">
                <button
                    :class="{ 'cursor-not-allowed': baseCsvStatus == 0 }"
                    @click.stop="upload('gld')"
                    class="ripple bg-transparent hover:bg-gray-300 py-2 px-4 text-gray-900 rounded m-1 focus:outline-none hidden sm:flex flex-row"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width:24px; height:24px;">
                        <path
                            fill="rgba(26, 32, 44, 1)"
                            d="m11.99806,14.99892l-5,0l0,2l5,0m5,-6l-10,0l0,2l7.69,0c-1.62,1.07 -2.69,2.91 -2.69,5c0,1.09 0.29,2.12 0.8,3l-7.8,0c-1.11,0 -2,-0.9 -2,-2l0,-14c0,-1.11 0.89,-2 2,-2l14,0a2,2 0 0 1 2,2l0,7.8c-0.88,-0.51 -1.91,-0.8 -3,-0.8l-1,0.08l0,-1.08m0,-2l0,-2l-10,0l0,2"
                        />
                        <g>
                            <path
                                fill="rgba(26, 32, 44, 1)"
                                d="m15.75665,19.4418l1.01849,0c0,0.54999 0.69767,1.01849 1.52774,1.01849c0.83007,0 1.52774,-0.46851 1.52774,-1.01849c0,-0.56017 -0.52962,-0.76387 -1.64996,-1.03377c-1.0796,-0.2699 -2.42402,-0.606 -2.42402,-2.02171c0,-0.91155 0.74859,-1.68561 1.78237,-1.94532l0,-1.11016l1.52774,0l0,1.11016c1.03377,0.25972 1.78237,1.03377 1.78237,1.94532l-1.01849,0c0,-0.54999 -0.69767,-1.01849 -1.52774,-1.01849c-0.83007,0 -1.52774,0.46851 -1.52774,1.01849c0,0.56017 0.52962,0.76387 1.64996,1.03377c1.0796,0.2699 2.42402,0.606 2.42402,2.02171c0,0.91155 -0.74859,1.68561 -1.78237,1.94532l0,1.11016l-1.52774,0l0,-1.11016c-1.03377,-0.25972 -1.78237,-1.03377 -1.78237,-1.94532z"
                            />
                        </g>
                    </svg>
                </button>
                <span
                    class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >Upload gold csv</span
                >
            </span>
            <span class="tooltip relative" v-if="projectData.status < 3">
                <button
                    @click.stop="
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
                    class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >Edit</span
                >
            </span>
            <span class="tooltip relative" v-if="projectData.status < 3">
                <button
                    @click.stop="deleteItem()"
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
                    class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >Delete</span
                >
            </span>
            <span class="tooltip relative">
                <button
                    @click.stop="launch()"
                    v-if="projectData.status >= 2"
                    class="ripple hover:bg-blue-600 bg-primary text-white py-2 px-4 rounded m-1 focus:outline-none hidden xs:flex"
                >
                    <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                        <path
                            d="M13.13 22.19L11.5 18.36C13.07 17.78 14.54 17 15.9 16.09L13.13 22.19M5.64 12.5L1.81 10.87L7.91 8.1C7 9.46 6.22 10.93 5.64 12.5M21.61 2.39C21.61 2.39 16.66 .269 11 5.93C8.81 8.12 7.5 10.53 6.65 12.64C6.37 13.39 6.56 14.21 7.11 14.77L9.24 16.89C9.79 17.45 10.61 17.63 11.36 17.35C13.5 16.53 15.88 15.19 18.07 13C23.73 7.34 21.61 2.39 21.61 2.39M14.54 9.46C13.76 8.68 13.76 7.41 14.54 6.63S16.59 5.85 17.37 6.63C18.14 7.41 18.15 8.68 17.37 9.46C16.59 10.24 15.32 10.24 14.54 9.46M8.88 16.53L7.47 15.12L8.88 16.53M6.24 22L9.88 18.36C9.54 18.27 9.21 18.12 8.91 17.91L4.83 22H6.24M2 22H3.41L8.18 17.24L6.76 15.83L2 20.59V22M2 19.17L6.09 15.09C5.88 14.79 5.73 14.47 5.64 14.12L2 17.76V19.17Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                >
                    Launch HITs
                </span>
            </span>
            <span class="tooltip relative">
                <button
                    @click.stop="layout()"
                    v-if="projectData.status == 1"
                    class="ripple hover:bg-blue-600 bg-primary text-white py-2 px-4 rounded m-1 focus:outline-none hidden xs:flex"
                >
                    <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12.89,3L14.85,3.4L11.11,21L9.15,20.6L12.89,3M19.59,12L16,8.41V5.58L22.42,12L16,18.41V15.58L19.59,12M1.58,12L8,5.58V8.41L4.41,12L8,15.58V18.41L1.58,12Z"
                        />
                    </svg>
                </button>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                    >Set layout</span
                >
            </span>
            <span class="tooltip relative">
                <button
                    @click.stop="createHIT()"
                    v-if="projectData.status == 0"
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
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                    >Create HITs</span
                >
            </span>
            <span v-click-outside="hide" class="md:hidden flex align-center">
                <button
                    class="ripple-light py-2 px-2 m-1 focus:outline-none bg-white rounded"
                    @click.stop="dropdown = !dropdown"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
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
                            @click.stop="layout()"
                            v-if="projectData.status == 1"
                            :class="projectData.status == 1 ? 'rounded-t-md' : ''"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white xs:hidden"
                            >Set layout</a
                        >
                        <a
                            @click.stop="createHIT()"
                            v-if="projectData.status == 0"
                            :class="projectData.status == 0 ? 'rounded-t-md' : ''"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white xs:hidden"
                            >Create HITs</a
                        >
                        <a
                            @click.stop="upload('std')"
                            v-if="projectData.status < 1"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white xs:rounded-t-md sm:hidden"
                            >Upload data</a
                        >
                        <a
                            @click.stop="upload('gld')"
                            v-if="projectData.status < 1"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white sm:hidden"
                            >Upload Gold</a
                        >
                        <a
                            @click.stop="
                                $router.push({
                                    name: 'edit',
                                    params: {
                                        projectId: projectData.id,
                                    },
                                })
                            "
                            class="block md:hidden px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white sm:rounded-t-md"
                            >Edit</a
                        >
                        <a
                            @click.stop="deleteItem()"
                            class="block md:hidden px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-b-md"
                            >Delete</a
                        >
                    </div>
                </transition>
            </span>
            <!-- <button
                class="bg-white ripple-light py-2 px-2 m-1 rounded focus:outline-none"
                @click.stop="isOpen = !isOpen"
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
            </button> -->
            <!-- <span
                class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1"
                v-if="projectData.status == 0 && baseCsvStatus == 1 && goldCsvStatus == 0"
            >
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"
                ></span>
                <span
                    class="relative inline-flex justify-center items-center rounded-full h-3 w-3 bg-blue-500 text-xs text-white"
                >
                </span>
            </span>
            <span
                class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1"
                v-if="projectData.status == 0 && baseCsvStatus == 1 && goldCsvStatus == 1"
            >
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
                ></span>
                <span
                    class="relative inline-flex justify-center items-center rounded-full h-3 w-3 bg-green-500 text-xs text-white"
                >
                </span>
            </span>
            <span
                class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1"
                v-if="projectData.status == 0 && baseCsvStatus == 0 && goldCsvStatus == 0"
            >
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span
                    class="relative inline-flex justify-center items-center rounded-full h-3 w-3 bg-red-500 text-xs text-white"
                >
                </span>
            </span> -->
        </span>
        <!-- <transition name="slide-toggle">
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
                    @click.stop="refresh()"
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
        </transition> -->
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
        //this.displayWindowSize()
        //window.onresize = this.displayWindowSize
        //for (let i = 0; i < this.projectData.length; i++) {}
        //controllare con un loop se ci sono HIT che attendono review
        //se ci sono isTherePending = true
        // this.isTherePending = true
        this.date = new Date(this.projectData.created_at).toLocaleDateString()
        this.lastEdited = this.projectData.updated_at
        if (this.projectData.count_train > 0) {
            this.baseCsvStatus = 1
        }
        if (this.projectData.count_gold > 0) {
            this.goldCsvStatus = 1
        }
        //inserire controllo che se il projectData contiene anche il csv per base o gold cambiano i valori baseCsvStatus e goldCsvStatus nei data
    },
    methods: {
        openProject() {
            this.$router.push({ name: 'projectView', params: { projectId: this.projectData.id } })
        },
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
            this.$emit('deleteThis', ['delete', this.projectData.id])
        },
        upload(type) {
            if (this.dropdown) {
                this.dropdown = !this.dropdown
            }
            if (type == 'std') {
                this.$emit('upload', [
                    type,
                    this.projectData.id, // this.projectData.count_train, this.projectData.count_gold
                    'noSnack',
                ])
            } else if (type == 'gld' && this.baseCsvStatus == 0) {
                this.$emit('snackbar', 'Warning. To upload the gold CSV, you first have to uplaod the standard.', '')
            } else if (type == 'gld' && this.baseCsvStatus == 1) {
                this.$emit('upload', [
                    type,
                    this.projectData.id, // this.projectData.count_train, this.projectData.count_gold
                    'noSnack',
                ])
            }
        },
        createHIT() {
            if (this.baseCsvStatus == 0) {
                this.$emit('snackbar', 'Warning. To create the HITs, you first have to upload CSV data.')
            } else {
                this.$emit('createHit', [
                    this.projectData.id,
                    this.projectData.count_train,
                    this.projectData.count_gold,
                    this.projectData.params,
                ])
            }
        },
        layout() {
            this.$emit('layoutModal', ['layout', this.projectData.id])
        },
        launch() {
            this.$emit('launchModal', ['launch', this.projectData.id])
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
</style>
