<template>
    <div class="h-full">
        <div class="absolute left-0 bottom-0 top-0 w-full flex items-center justify-center bg-primary">
            <form class="bg-white shadow-md rounded px-6 pt-4 pb-4 mb-4">
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-2" for="username"> Username </label>
                    <input
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="username"
                        type="text"
                        placeholder="Username"
                        required
                        v-model.trim="$v.username.$model"
                    />
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-2" for="password"> Password </label>
                    <input
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="password"
                        type="password"
                        placeholder="Password"
                        required
                        v-model.trim="$v.password.$model"
                    />
                    <p class="text-red-500 text-xs italic mt-2" v-if="$v.password.$error">
                        Please enter your credentials
                    </p>
                </div>
                <div class="flex items-center justify-end">
                    <button
                        class="ripple transition ease-out duration-100 bg-primary hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
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
                </div>
            </form>
        </div>
    </div>
</template>

<script>
const { required } = require('vuelidate/lib/validators')
export default {
    data() {
        return {
            username: '',
            password: '',
            loading: false,
        }
    },
    validations() {
        return {
            username: {
                required,
            },
            password: {
                required,
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
            this.$v.$touch()
            if (!this.$v.$invalid) {
                //this.loading = true
                this.API.get('?action=login&username=' + this.username + '&password=' + this.password)
                    .then((res) => {
                        console.log(res)
                        if (res.data.result == 'ERR') {
                            this.loading = false
                            this.$emit('snackbar', 'Error. ' + res.data.error)
                        } else {
                            var path
                            this.API.get('?action=getUserInfo')
                                .then((res) => {
                                    this.$store.state.userInfo = res.data.data
                                    this.$store.state.currentRoute != ''
                                        ? (path = this.$store.state.currentRoute)
                                        : (path = '/')
                                    this.$emit('sandbox', this.$store.state.userInfo.use_sandbox)
                                    this.$router.replace({ path: path })
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
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>