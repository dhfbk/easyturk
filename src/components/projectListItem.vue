<template>
    <div class="rounded shadow-md my-1 mx-2 p-2 pl-0 flex items-center flex-wrap bg-white">
        <div class="w-full md:w-3/5 flex contenutoPrj text-center items-center flex-wrap">
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Project Id:</p>
                <router-link to="view" class>
                    <p>{{ projectData.id }}</p>
                </router-link>
            </div>
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Title:</p>
                <p class>{{ projectData.title }}</p>
            </div>
            <div class="flex flex-col w-1/3 justify-center text-center flex-wrap my-1 sm:my-0">
                <p class="block md:hidden text-md font-bold tracking-tight">Date:</p>
                <p class>{{ projectData.date }}</p>
            </div>
        </div>
        <span class="flex-grow flex justify-end w-2/5">
            <button
                class="bg-primary hover:bg-orange-500 text-white py-2 px-4 rounded m-1 focus:outline-none hidden sm:inline-block"
            >Publish Batch</button>

            <button
                @click="upload('std')"
                class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1 focus:outline-none hidden xl:inline-block"
            >Upload Data</button>
            <button
                @click="upload('gld')"
                class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1 focus:outline-none hidden xl:inline-block"
            >Upload Gold</button>
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
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-primary hover:text-white rounded-t-md"
                        >Edit</a>
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
        </span>
        <transition name="slide-toggle">
            <div v-if="isOpen" class="content w-full flex flex-wrap justify-center">
                <vue-ellipse-progress
                    :progress="(projectData.pendingReviewHITs * 100) / projectData.totalHITs"
                    :legend-value="projectData.pendingReviewHITs"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ projectData.totalHITs }}</span>
                    <p slot="legend-caption">Pending Review</p>
                </vue-ellipse-progress>
                <vue-ellipse-progress
                    :progress="(projectData.inProgressHITs * 100) / projectData.totalHITs"
                    :legend-value="projectData.inProgressHITs"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ projectData.totalHITs }}</span>
                    <p slot="legend-caption">In Progress</p>
                </vue-ellipse-progress>
                <vue-ellipse-progress
                    :progress="(projectData.completedHITs * 100) / projectData.totalHITs"
                    :legend-value="projectData.completedHITs"
                    :color="'#0091FF'"
                    :size="pieSize"
                    :half="true"
                    :angle="0"
                    class="mt-8 mx-8"
                >
                    <span slot="legend-value">/{{ projectData.totalHITs }}</span>
                    <p slot="legend-caption">Completed</p>
                </vue-ellipse-progress>
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
        }
    },
    created() {
        this.displayWindowSize()
        window.onresize = this.displayWindowSize
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
            this.dropdown = !this.dropdown
            this.$emit('deleteThis')
        },
        upload(type) {
            this.dropdown = !this.dropdown
            this.$emit('upload', type)
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
</style>
