<template>
  <div id="app" class="antialiased pb-6">
    <navbar class="relative z-10" />
    <div
      v-if="sandbox"
      class="w-full customEl bg-yellow-500 text-center flex justify-center content-center items-center"
    >
      <p>You are using the sandbox version</p>
    </div>
    <snack-bar :msg="messaggio" v-if="snack" />
    <router-view v-slot="{ Component }" @snackbar="showSnack" @sandbox="setSandbox">
      <transition name="fade" mode="out-in">
        <component :is="Component" v-if="!wait" />
      </transition>
    </router-view>
  </div>
</template>

<script>
import navbar from './components/navBar.vue'
import snackBar from './components/snackBar.vue'
import axios from 'axios'
import globalMixin from './globalMixin.js'
export default {
  mixins: [globalMixin],
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
  updated() {
    if (this.$route.path != '/login') {
      this.$store.state.currentRoute = this.$route.path
    }
  },
  created() {
    localStorage.getItem('session_id') ? null : localStorage.setItem('session_id', '')
    axios
      .all([this.API().get('?action=getOptions'), this.API().get('?action=getUserInfo')])
      .then(
        axios.spread((...res) => {
          if (res[1].data.result == 'ERR') {
            if (this.$route.name != 'LoginView') {
              this.$router.replace({ path: '/login' })
            }
            setTimeout(() => {
              this.wait = false
            }, 500)
          } else {
            if (this.$route.name == 'LoginView') {
              this.$router.replace({ path: '/' })
            }
            this.$store.state.userInfo = res[1].data.data
            this.setSandbox(this.$store.state.userInfo.use_sandbox)
            this.wait = false
          }
          this.$store.state.defaults = res[0].data.defaults
        })
      )
      .catch((err) => {
        console.error(err)
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

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.customEl {
  min-height: 2rem;
  padding: 5px 10px;
}
</style>
