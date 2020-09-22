<template>
    <div id="app" class="antialiased pb-6">
        <navbar />
        <div
            v-if="sandbox"
            class="w-full h-8 bg-orange-400 text-center"
        >You are using the sandbox version</div>
        <snack-bar :type="snackType" :msg="messaggio" v-if="snack" />
        <transition name="fade" mode="out-in">
            <router-view @snackbar="showSnack" @sandbox="setSandbox" />
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
            messaggio: '',
            sandbox: false,
        }
    },
    methods: {
        setSandbox(show) {
            this.sandbox = show
        },
        showSnack(arr) {
            this.snackType = arr[0]
            this.messaggio = arr[2]
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
