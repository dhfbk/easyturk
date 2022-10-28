<template>
  <nav class="bg-primary p-6 pb-8 sm:pb-6 sm:flex content-center items-center">
    <router-link
      to="/"
      :disabled="$route.name == 'login'"
      class="flex items-center content-center text-white mr-6 w-auto absolute"
    >
      <img src="../assets/easyTurk.png" alt="EasyTurk logo" class="w-10" />
      <span class="text-md ml-1">EasyTurk</span>
    </router-link>
    <div class="block sm:hidden float-right ml-2" v-if="$route.name != 'login'">
      <button
        @click="open = !open"
        class="ripple items-center mx-0 px-3 py-2 border rounded transition-colors duration-100 ease-out text-white border-white hover:text-blue-300 hover:border-blue-300"
      >
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <title>Menu</title>
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
        </svg>
      </button>
    </div>
    <div class="clear-both sm:hidden"></div>
    <div
      v-if="$route.name != 'login'"
      :class="open ? 'block' : 'hidden'"
      class="w-full flex-grow sm:flex sm:items-center sm:w-auto justify-end text-right sm:text-justify pt-4 sm:pt-0 relative"
    >
      <div class="sm:flex items-center content-center">
        <router-link
          :to="{ name: 'Home' }"
          class="flex gap-x-1 mt-4 sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4"
        >
          <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
            <path d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22L12 3Z" />
          </svg>
          Home
        </router-link>
        <!--
                <router-link
                    :to="{ name: 'workerList' }"
                    class="block mt-4 sm:inline-block sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 "
                >
                    Workers
                </router-link>
                -->
        <router-link
          :to="{ name: 'new' }"
          class="flex gap-x-1 mt-4 sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4"
        >
          <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
            <path
              d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M13,7H11V11H7V13H11V17H13V13H17V11H13V7Z"
            />
          </svg>
          Create Project
        </router-link>
        <div
          class="flex gap-x-1 mt-4 sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 cursor-pointer"
          @click="profile = !profile"
        >
          <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
            <path
              d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M12,13C14.67,13 20,14.33 20,17V20H4V17C4,14.33 9.33,13 12,13M12,14.9C9.03,14.9 5.9,16.36 5.9,17V18.1H18.1V17C18.1,16.36 14.97,14.9 12,14.9Z"
            />
          </svg>
          <p>Profile</p>
        </div>
        <transition name="slidedown" mode="out-in">
          <div v-if="profile" class="px-4 sm:hidden">
            <router-link
              :to="{ name: 'settings' }"
              class="flex gap-x-1 mt-4 sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4"
            >
              <!-- <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
              <path
                d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z"
              />
            </svg> -->
              Settings
            </router-link>
            <a
              @click="logout"
              class="flex gap-x-1 mt-4 sm:mt-0 transition-colors duration-100 ease-out text-red-500 font-bold hover:text-red-600 sm:mr-4 cursor-pointer"
            >
              <!-- <svg
              :class="loadLogout ? 'hidden' : ''"
              class="fill-current"
              style="width: 24px; height: 24px"
              viewBox="0 0 24 24"
            >
              <path
                d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z"
              />
            </svg> -->
              Logout
              <svg
                :class="loadLogout ? 'animate-spin' : 'hidden'"
                style="width: 24px; height: 24px; fill: white"
                viewBox="0 0 24 24"
              >
                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
              </svg>
            </a>
          </div>
        </transition>
        <transition name="slidedown" mode="out-in">
          <div
            v-if="profile"
            class="hidden sm:flex flex-col absolute right-0 bg-white rounded-md shadow-xl z-20 -mr-1 mt-32 cursor-pointer w-28"
            v-click-outside="handleOutside"
          >
            <router-link
              :to="{ name: 'settings' }"
              @click="profile = false"
              class="px-4 py-2 capitalize text-gray-700 hover:bg-primaryDark transition-colors duration-20 hover:text-white xs:rounded-t-md"
            >
              Settings
            </router-link>
            <a
              @click="logout"
              class="px-4 py-2 flex content-center items-center gap-x-1 capitalize text-gray-700 hover:bg-primaryDark transition-colors duration-20 hover:text-white rounded-b-md"
            >
              Logout
              <svg
                class="fill-current"
                :class="loadLogout ? 'animate-spin' : 'hidden'"
                style="width: 20px; height: 20px"
                viewBox="0 0 24 24"
              >
                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
              </svg>
            </a>
          </div>
        </transition>
      </div>
    </div>
  </nav>
</template>

<script>
import globalMixin from '../globalMixin.js'
import { directive } from 'vue3-click-away'

export default {
  mixins: [globalMixin],
  name: 'navbar',
  directives: {
    ClickOutside: directive,
  },
  data() {
    return {
      open: false,
      loadLogout: false,
      profile: false,
    }
  },
  methods: {
    handleOutside() {
      this.profile = false
    },
    logout() {
      localStorage.setItem('session_id', '')
      this.loadLogout = true
      this.open = false
      this.profile = false
      this.API()
        .get('?action=logout')
        .then(() => {
          this.$router.replace({ path: '/login' })
          this.loadLogout = false
        })
        .catch((err) => {
          console.error(err)
        })
    },
  },
}
</script>

<style scoped>
.slidedown-enter-active,
.slidedown-leave-active {
  transition: max-height 0.12s ease-out;
}

.slidedown-enter-to,
.slidedown-leave-from {
  overflow: hidden;
  max-height: 112px;
}

.slidedown-enter-from,
.slidedown-leave-to {
  overflow: hidden;
  max-height: 0;
}
</style>
