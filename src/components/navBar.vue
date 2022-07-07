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
                class="
                    ripple
                    items-center
                    mx-0
                    px-3
                    py-2
                    border
                    rounded
                    transition-colors
                    duration-100
                    ease-out
                    text-white
                    border-white
                    hover:text-blue-300 hover:border-blue-300
                    focus:outline-none
                "
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
            class="
                w-full
                flex-grow
                sm:flex sm:items-center sm:w-auto
                justify-end
                text-right
                sm:text-justify
                pt-4
                sm:pt-0
            "
        >
            <div class="sm:flex items-center content-center">
                <router-link
                    :to="{ name: 'Home' }"
                    class="
                        flex
                        gap-x-1
                        mt-4
                        sm:mt-0
                        transition-colors
                        duration-100
                        ease-out
                        text-white
                        hover:text-blue-300
                        sm:mr-4
                        focus:outline-none
                    "
                >
                    <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                        <path
                            d="M12 5.69L17 10.19V18H15V12H9V18H7V10.19L12 5.69M12 3L2 12H5V20H11V14H13V20H19V12H22L12 3Z"
                        />
                    </svg>
                    Home
                </router-link>
                <!--
                <router-link
                    :to="{ name: 'workerList' }"
                    class="block mt-4 sm:inline-block sm:mt-0 transition-colors duration-100 ease-out text-white hover:text-blue-300 sm:mr-4 focus:outline-none"
                >
                    Workers
                </router-link>
                -->
                <router-link
                    :to="{ name: 'new' }"
                    class="
                        flex
                        gap-x-1
                        mt-4
                        sm:mt-0
                        transition-colors
                        duration-100
                        ease-out
                        text-white
                        hover:text-blue-300
                        sm:mr-4
                        focus:outline-none
                    "
                >
                    <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                        <path
                            d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M13,7H11V11H7V13H11V17H13V13H17V11H13V7Z"
                        />
                    </svg>
                    Create Project
                </router-link>
                <a
                    @click="logout"
                    class="
                        mt-4
                        flex
                        gap-x-1
                        sm:mt-0
                        transition-colors
                        duration-100
                        ease-out
                        text-white
                        hover:text-blue-300
                        cursor-pointer
                        focus:outline-none
                        rounded
                    "
                >
                    <svg
                        :class="loadLogout ? 'hidden' : ''"
                        class="fill-current"
                        style="width: 24px; height: 24px"
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
                    Logout
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
            localStorage.setItem('session_id', '')
            this.loadLogout = true
            this.API()
                .get('?action=logout')
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
