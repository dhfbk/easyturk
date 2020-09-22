<template>
    <div id="app" class="antialiased pb-6">
        <navbar />
        <div
            v-if="sandbox"
            class="w-full customEl bg-orange-400 text-center flex justify-center content-center items-center"
        >
            <p>You are using the sandbox version</p>
        </div>
        <snack-bar :msg="messaggio" v-if="snack" />
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
        showSnack(msg) {
            //this.snackType = arr[0]
            this.messaggio = msg
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

.customEl {
    min-height: 2rem;
    padding: 5px 10px;
}
</style>
