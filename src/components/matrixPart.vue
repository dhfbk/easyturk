<template>
    <span @mouseenter="hovering = true" @mouseleave="hovering = false">
        <span>
            <span v-for="(i, p) in num.count" :key="p">
                <span class="tooltip relative ">
                    <span
                        class="h-5 w-5 m-1 rounded-full inline-block transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-focus"
                        :style="{ 'background-color': '#' + color }"
                    ></span>
                    <span
                        class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                        >ID: {{ i }}
                        <!-- <br />Status:<br />
                    {{ num.assignments_approved }} approved <br />
                    {{ num.assignments_rejected }} rejected -->
                    </span>
                </span>
            </span>
        </span>
        <!-- <transition name="fade">
            <div class="text-center" v-if="hovering">{{ num.count }}/{{ total }}</div>
        </transition> -->
    </span>
</template>

<script>
export default {
    props: {
        color: String,
        num: Object,
        ids: Array,
        total: Number,
    },
    data() {
        return {
            hovering: false,
        }
    },
    created() {
        console.log('ok')
    },
}
</script>

<style scoped>
.topHalf {
    margin: 0 !important;
    top: 110%;
    -webkit-transform: translateY(50%) !important;
    transform: translateY(50%) !important;
    -webkit-transform: translateX(-50%) !important;
}
.fade-enter-active,
.fade-leave-active {
    transition-duration: 0.2s;
    transition-property: opacity;
    transition-timing-function: ease-out;
}

.fade-enter,
.fade-leave-active {
    opacity: 0;
}
</style>
