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
            <router-view v-if="!wait" @snackbar="showSnack" @sandbox="setSandbox" />
        </transition>
    </div>
</template>

<script>
import navbar from './components/navBar.vue'
import snackBar from './components/snackBar.vue'
import axios from 'axios'
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
            wait: true,
            timeout: 4000
        }
    },
    created() {
        axios
            .get(this.APIURL + '?action=getOptions')
            .then(res => {
                this.$store.state.defaults = res.data.defaults
                this.wait = false
                //this.loadingProjects = false
            })
            .catch(err => {
                console.log(err)
            })
    },
    methods: {
        setSandbox(show) {
            this.sandbox = show
            if (show == 1) {
                this.$store.state.isSandbox = true
            } else {
                this.$store.state.isSandbox = false
            }
            // console.log(this.$store.state.isSandbox)
        },
        showSnack(msg) {
            //this.snackType = arr[0]
            this.messaggio = msg
            this.snack = true
            this.timeout = 4000
            setTimeout(() => {
                this.snack = false
            }, this.timeout)
        },
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition-duration: 0.3s;
    transition-property: opacity;
    transition-timing-function: ease-out;
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
