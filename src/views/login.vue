<template>
  <div class="fixed left-0 bottom-0 w-full h-full flex items-center justify-center bg-primary">
    <form
      class="bg-white shadow-md rounded w-5/6 md:w-1/2 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none p-4"
    >
      <div class="mb-2">
        <label class="block text-gray-700 text-sm mb-2" for="username">Username</label>
        <input
          :class="v$.username.$error ? 'border-red-400' : ''"
          class="appearance-none border border-gray-200 rounded w-full py-2 px-3 text-gray-700 leading-tight transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
          id="username"
          type="text"
          placeholder="Username"
          required
          v-model.trim="username"
          maxlength="100"
        />
      </div>
      <div class="mb-2">
        <label class="block text-gray-700 text-sm mb-2" for="password">Password</label>
        <input
          :class="v$.password.$error ? 'border-red-400' : ''"
          class="appearance-none border border-gray-200 rounded w-full py-2 px-3 text-gray-700 leading-tight transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
          id="password"
          type="password"
          placeholder="Password"
          required
          v-model.trim="password"
          maxlength="100"
        />
        <p class="text-red-500 text-xs italic mt-2" v-if="v$.password.$error">Please enter your credentials</p>
      </div>
      <button
        class="ripple transition-colors ease-out duration-100 bg-primary hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded text-white"
        type="button"
        @click="login"
      >
        <svg
          :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
          style="width: 24px; height: 24px"
          viewBox="0 0 24 24"
        >
          <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
        </svg>
        Sign In
      </button>
    </form>
  </div>
</template>

<script>
import { required, maxLength } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  data() {
    return {
      username: '',
      password: '',
      loading: false,
    }
  },
  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
      username: {
        required,
        maxLength: maxLength(100),
      },
      password: {
        required,
        maxLength: maxLength(100),
      },
    }
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  methods: {
    keyboardEvent(event) {
      if (event.code == 'Enter') {
        this.login()
      }
    },
    login() {
      this.loading = true
      this.v$.$touch()
      if (!this.v$.$invalid) {
        //this.loading = true
        this.API()
          .get('?action=login&username=' + this.username + '&password=' + this.password)
          .then((res) => {
            if (res.data.result == 'ERR') {
              this.loading = false
              this.$emit('snackbar', 'Error. ' + res.data.error)
            } else {
              localStorage.setItem('session_id', res.data.session_id)
              var path = ''
              this.API()
                .get('?action=getUserInfo')
                .then((res2) => {
                  if (res2.data.result == 'ERR') {
                    this.$emit('snackbar', 'Error. ' + res2.data.error)
                  } else {
                    this.$store.state.userInfo = res2.data.data
                    this.$store.state.currentRoute != '' ? (path = this.$store.state.currentRoute) : (path = '/')
                    this.$emit('sandbox', this.$store.state.userInfo.use_sandbox)
                    this.$router.replace({ path: path })
                    this.$emit('snackbar', 'Login successful')
                  }
                  this.loading = false
                })
                .catch((err) => {
                  console.error(err)
                })
            }
            this.loading = false
          })
          .catch((err) => {
            console.error(err)
            this.loading = false
          })
      } else {
        this.loading = false
        this.$emit('snackbar', 'Error: check your credentials')
      }
    },
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>
