<template>
    <div id="app">
        <navbar />
        <transition name="fade" mode="out-in">
            <router-view />
        </transition>
    </div>
</template>

<script>
import navbar from './components/navBar'
export default {
    components: {
        navbar,
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition-duration: 0.3s;
    transition-property: opacity;
    transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
    opacity: 0;
}
</style>