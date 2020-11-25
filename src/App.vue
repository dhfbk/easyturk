<template>
    <div id="app" class="antialiased pb-6">
        <navbar class="relative z-10" />
        <div
            v-if="sandbox"
            class="w-full customEl bg-orange-400 text-center flex justify-center content-center items-center"
        >
            <p>You are using the sandbox version</p>
        </div>
        <snack-bar :msg="messaggio" v-if="snack" />
        <transition name="fade" mode="out-in" v-if="!wait">
            <router-view @snackbar="showSnack" @sandbox="setSandbox" />
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
            timeout: 4000,
        }
    },
    created() {
        axios
            .all([this.API.get('?action=getOptions'), this.API.get('?action=getUserInfo')])
            .then(
                axios.spread((...res) => {
                    if (res[1].data.result == 'ERR') {
                        if (this.$route.name != 'login') {
                            this.$router.replace({ path: '/login' })
                        }
                        this.wait = false
                    } else {
                        if (this.$route.name == 'login') {
                            this.$router.replace({ path: '/' })
                        }
                        this.$store.state.defaults = res[0].data.defaults
                        this.$store.state.userInfo = res[1].data.data
                        this.setSandbox(this.$store.state.userInfo.use_sandbox)
                        this.wait = false
                    }
                })
            )
            .catch((err) => {
                console.log(err)
            })
    },
    methods: {
        setSandbox(val) {
            this.sandbox = val
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
