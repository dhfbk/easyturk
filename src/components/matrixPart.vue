<template>
    <span @mouseenter="hovering = true" @mouseleave="hovering = false">
        <span>
            <span v-for="(i, p) in num.count" :key="p">
                <span
                    @click="$router.push({ name: 'viewHIT', params: { hitId: num.hits[p] } })"
                    class="h-5 w-5 m-1 rounded-full inline-block transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-focus"
                    :style="{ background: color }"
                    :class="hoveringFull ? 'shadow-focus' : ''"
                    :content="'ID: ' + num.hits[p]"
                    v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
                ></span>
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
        total: Number,
        hoverColor: String,
    },
    data() {
        return {
            hovering: false,
            hoveringFull: false,
        }
    },
    created() {},
    watch: {
        hoverColor() {
            if (this.hoverColor == this.color) {
                this.hoveringFull = true
            } else {
                this.hoveringFull = false
            }
        },
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
