<template>
    <div class="h-full">
        <div class="absolute left-0 bottom-0 top-0 w-full flex items-center justify-center bg-primary">
            <form class="bg-white shadow-md rounded px-6 pt-4 pb-4 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm mb-2" for="username">
                        Username
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="username"
                        type="text"
                        placeholder="Username"
                        v-model="username"
                    />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm mb-2" for="password">
                        Password
                    </label>
                    <input
                        :class="password == '' ? 'border-red-500' : ''"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        id="password"
                        type="password"
                        placeholder="Password"
                        v-model="password"
                    />
                    <p class="text-red-500 text-xs italic" v-if="password == ''">Please choose a password.</p>
                    <p class="text-red-500 text-xs italic invisible" v-else>OK</p>
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="hidden md:block md:mr-2 ripple transition ease-out duration-100 bg-primary hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                        type="submit"
                    >
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            username: '',
            password: '',
        }
    },
}
</script>

<style></style>
