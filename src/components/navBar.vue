<template>
    <nav class="bg-primary p-6">
        <div class="text-white mr-6 w-auto">
            <router-link to="/" :disabled="$route.name == 'login'" class="flex items-center content-center w-auto absolute">
                <img src="../assets/easyTurk.png" alt="EasyTurk logo" class="w-10" />
                <span class="text-md ml-1">EasyTurk</span>
            </router-link>
        </div>
        <div class="block sm:hidden float-right ml-2" v-if="$route.name != 'login'">
            <button
                @click="open = !open"
                class="ripple items-center mx-0 px-3 py-2 border rounded transition duration-100 ease-out text-black border-black hover:text-white hover:border-white focus:outline-none"
            >
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>
        <div class="clearfix sm:hidden"></div>
        <div
            v-if="$route.name != 'login'"
            :class="open ? 'block' : 'hidden'"
            class="w-full flex-grow sm:flex sm:items-center sm:w-auto justify-end text-right sm:text-justify"
        >
            <div class="sm:flex items-center content-center">
                <router-link
                    :to="{ name: 'Home' }"
                    class="block mt-4 sm:inline-block sm:mt-0 transition duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 focus:outline-none"
                >
                    Home
                </router-link>
                <router-link
                    :to="{ name: 'workerList' }"
                    class="block mt-4 sm:inline-block sm:mt-0 transition duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 focus:outline-none"
                >
                    Workers
                </router-link>
                <router-link
                    :to="{ name: 'new' }"
                    class="block mt-4 sm:inline-block sm:mt-0 transition duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 focus:outline-none"
                >
                    Create Project
                </router-link>
                <a
                    @click="logout"
                    class="mt-4 p-1 inline-block sm:mt-0 transition duration-100 ease-out bg-red-700 hover:bg-red-500 cursor-pointer focus:outline-none rounded"
                    :content="'Logout'"
                    v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
                >
                    <svg
                        :class="loadLogout ? 'hidden' : ''"
                        style="width: 24px; height: 24px; fill: white"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z"
                        />
                    </svg>
                    <svg
                        :class="loadLogout ? 'animate-spin' : 'hidden'"
                        style="width: 24px; height: 24px; fill: white"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                    </svg>
                    <span class="sr-only">Logout</span>
                </a>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'navbar',
    data() {
        return {
            open: false,
            loadLogout: false,
        }
    },
    methods: {
        logout() {
            this.loadLogout = true
            this.API.get('?action=logout')
                .then(() => {
                    this.$router.replace({ path: '/login' })
                    this.loadLogout = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
    },
}
</script>

<style></style>
