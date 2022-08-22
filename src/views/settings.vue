<template>
  <div class="bg-white shadow-md rounded py-4 flex flex-col px-8 mt-4 mx-2 xs2:mx-4 lg:mx-auto lg:w-5/6 pt-2">
    <p class="text-2xl my-4 text-primary">Account settings</p>
    <div class="-mx-3 md:flex md:flex-col">
      <div class="w-full px-3 mb-4 md:max-w-lg">
        <h2 class="text-lg my-4 text-primary">Account details</h2>
        <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="username">Username</label>
        <input
          :class="v$.username.$error ? 'border-red-400 bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700'"
          class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 mb-2 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
          id="username"
          type="text"
          placeholder="Username"
          maxlength="255"
          v-model.trim="v$.username.$model"
          required
        />
        <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="common">Common name</label>
        <input
          :class="v$.common.$error ? 'border-red-400 bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700'"
          class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 mb-2 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
          id="common"
          type="text"
          placeholder="Common name"
          maxlength="255"
          v-model.trim="v$.common.$model"
          required
        />
        <button
          type="submit"
          @click="handleAccount"
          class="ripple hover:bg-primaryDark bg-primary py-2 px-4 rounded transition-colors duration-100 ease-out text-white"
        >
          <svg
            :class="loading_account ? 'animate-spin mr-1 fill-current' : 'hidden'"
            style="width: 24px; height: 24px"
            viewBox="0 0 24 24"
          >
            <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
          </svg>
          <span v-if="!loading_account">Update account</span>
        </button>
      </div>
      <hr />
      <div class="w-full px-3 mb-4 md:max-w-lg">
        <h2 class="text-lg my-4 text-primary">Change password</h2>
        <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="old_pass">Old password</label>
        <input
          :class="v$.old_pass.$error ? 'border-red-400 bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700'"
          class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 mb-2 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
          id="old_pass"
          type="password"
          placeholder="Old password"
          maxlength="255"
          v-model.trim="v$.old_pass.$model"
          required
        />
        <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="new_pass">New password</label>
        <input
          :class="v$.new_pass.$error ? 'border-red-400 bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700'"
          class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 mb-2 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
          id="new_pass"
          type="password"
          placeholder="New password"
          maxlength="255"
          v-model.trim="v$.new_pass.$model"
          required
        />
        <p class="text-gray-700 text-xs italic pb-2">The new password must contain at least 8 characters.</p>
        <button
          type="submit"
          @click="handlePass"
          :class="!allow_pass_send ? 'cursor-not-allowed' : ''"
          class="ripple hover:bg-primaryDark bg-primary py-2 px-4 rounded transition-colors duration-100 ease-out text-white"
          :disabled="!allow_pass_send"
        >
          <svg
            :class="loading_pass ? 'animate-spin mr-1 fill-current' : 'hidden'"
            style="width: 24px; height: 24px"
            viewBox="0 0 24 24"
          >
            <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
          </svg>
          <span v-if="!loading_pass">Update password</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
//import axios from 'axios'
import { required, maxLength, minLength } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'settings',
  data() {
    return {
      username: this.$store.state.userInfo.username,
      common: this.$store.state.userInfo.common_name,
      old_pass: '',
      new_pass: '',
      loading_pass: false,
      loading_account: false,
    }
  },
  computed: {
    allow_pass_send: function () {
      return this.old_pass.length > 0 && this.new_pass.length >= 8
    },
  },
  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {
      username: {
        required,
        maxLength: maxLength(255),
      },
      common: {
        required,
        maxLength: maxLength(255),
      },
      old_pass: {
        required,
        maxLength: maxLength(255),
      },
      new_pass: {
        required,
        maxLength: maxLength(255),
        minLength: minLength(8),
      },
    }
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  methods: {
    handleAccount() {
      this.v$.common.$touch()
      this.v$.username.$touch()
      if (!this.v$.common.$invalid && !this.v$.username.$invalid) {
        this.loading_pass = true
        this.loading_pass = false
      } else {
        this.$emit('snackbar', 'Error: check the inserted values')
      }
    },
    handlePass() {
      this.v$.old_pass.$touch()
      this.v$.new_pass.$touch()
      if (!this.v$.old_pass.$invalid && !this.v$.new_pass.$invalid) {
        this.loading_pass = true
        this.loading_pass = false
      } else {
        this.$emit('snackbar', 'Error: check the inserted passwords')
      }
    },
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.$router.go(-1)
      }
    },
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>
