<template>
    <div id="app" class="antialiased pb-6">
        <navbar />
        <snack-bar :type="snackType" v-if="snack" />
        <transition name="fade" mode="out-in">
            <router-view @snackbar="showSnack" />
        </transition>
    </div>
</template>

<script>
import navbar from './components/navBar.vue'
import snackBar from './components/snackBar.vue'
export default {
    components: {
        navbar,
        snackBar,
    },
    data() {
        return {
            snack: false,
            snackType: '',
        }
    },
    methods: {
        showSnack(mode) {
            this.snackType = mode
            this.snack = true
            setTimeout(() => {
                this.snack = false
            }, 4000)
        },
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
