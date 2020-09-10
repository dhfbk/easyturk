<template>
    <div>
        <div class="rounded shadow-lg m-1 p-4 flex items-center flex-wrap overflow-hidden">
            <router-link to="view"><span class="w-1/6">{{ projectData.id }}</span></router-link>
            <span class="w-1/4">{{ projectData.title }}</span>
            <span class="">{{ projectData.date }}</span>
            <span class="flex-grow flex justify-end w-1/3">
                <button
                    class="bg-orange-500 hover:bg-orange-700 text-white  py-2 px-4 rounded m-1 focus:outline-none"
                >
                    Publish Batch
                </button>
                <button
                    class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1 focus:outline-none"
                >
                    Edit
                </button>
                <button
                    class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1 focus:outline-none"
                >
                    Upload Data
                </button>
                <button
                    class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1 focus:outline-none"
                >
                    Upload Gold
                </button>
                <button
                    class="bg-red-600 hover:bg-red-700 text-white  py-2 px-4 rounded m-1 focus:outline-none"
                >
                    Delete
                </button>
                <button
                    class="bg-white hover:bg-gray-300 py-2 px-2 m-1 rounded focus:outline-none"
                    @click="isOpen = !isOpen"
                >
                    <svg
                        class="transition duration-300 ease-in-out "
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
                <div v-if="isOpen" class="content w-full flex justify-center">
                    <vue-ellipse-progress
                        :progress="(projectData.pendingReviewHITs * 100) / projectData.totalHITs"
                        :legend-value="projectData.pendingReviewHITs"
                        :color="'#f6ad55'"
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
                        :color="'#f6ad55'"
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
                        :color="'#f6ad55'"
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
    </div>
</template>

<script>
export default {
    props: {
        projectData: Object,
    },
    data() {
        return {
            isOpen: false,
            pieSize: 150,
            myWidth: 0,
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
    },
}
</script>

<style scoped>
.slide-toggle-enter-active,
.slide-toggle-leave-active {
    transition: max-height 0.4s;
}
.slide-toggle-enter-active {
    max-height: 200px;
}
.slide-toggle-enter,
.slide-toggle-leave-active {
    max-height: 0;
    overflow: hidden;
}
.slide-toggle-leave {
    max-height: 200px;
    overflow: hidden;
}
</style>
