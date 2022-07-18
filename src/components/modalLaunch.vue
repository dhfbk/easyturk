<template>
    <transition name="fade" mode="out-in" appear>
        <div
            class="
                flex
                items-center
                justify-center
                fixed
                left-0
                bottom-0
                w-full
                h-full
                bg-gray-800 bg-opacity-25
                customZ
            "
            @click="toggleModal"
        >
            <div
                tabindex="-1"
                id="modal"
                class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto focus:outline-none"
                @click.stop
            >
                <div class="flex flex-col p-4">
                    <div class="flex w-full mb-2">
                        <div class="font-bold text-lg text-primary">{{ title }}</div>
                        <span class="ml-auto rounded hover:bg-gray-300 p-1" @click="toggleModal()">
                            <svg
                                class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                            <span class="sr-only">Close</span>
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center content-center w-full pb-1">
                        <label
                            class="block tracking-wide text-gray-900 text-md font-bold mb-2 sm:mb-0 mr-2"
                            for="hitNum"
                            >How many HITs do you want to launch?</label
                        >
                        <div class="relative">
                            <input
                                @change="calculatePrice('basic')"
                                :class="$v.hitNum.$error ? 'border-red-400' : ''"
                                class="
                                    appearance-none
                                    focus:outline-none
                                    focus:border-blue-500
                                    hover:border-blue-500
                                    block
                                    w-full
                                    sm:max-w-xs
                                    border border-gray-200
                                    rounded
                                    py-2
                                    pl-4
                                    pr-16
                                "
                                id="hitNum"
                                type="number"
                                min="1"
                                :max="hitMax"
                                step="1"
                                placeholder="100"
                                v-model.trim="$v.hitNum.$model"
                                required
                            />
                            <div class="absolute top-0 right-0">
                                <div class="relative">
                                    <p
                                        @click="calculatePrice('max')"
                                        class="
                                            absolute
                                            top-0
                                            h-full
                                            ripple
                                            right-0
                                            py-2
                                            px-2
                                            rounded-r
                                            hover:bg-gray-200
                                            cursor-pointer
                                        "
                                    >
                                        MAX
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="w-full italic">You have {{ hitMax }} HITs left.</p>
                    <hr class="my-1" />
                    <p class="font-bold">Price</p>
                    <p>Workers' price: ${{ workerPrice }}</p>
                    <p>Mechanical Turk Fee: ${{ mtFee }}</p>
                    <p>
                        Masters Qualification Fee: ${{ masterFee }}
                        <span v-if="qualifications.master == 0">(you don't use the Masters Qualification)</span>
                    </p>
                    <p class="text-lg mt-2">
                        Total:
                        <b>${{ Math.round((workerPrice + mtFee + masterFee + 0.00001) * 100) / 100 }}</b>
                    </p>

                    <div class="ml-auto flex flex-col xs2:flex-row mt-2">
                        <button
                            class="
                                ripple
                                flex flex-row
                                transition-colors
                                duration-100
                                ease-out
                                bg-primary
                                hover:bg-blue-600
                                text-gray-100
                                py-2
                                px-4
                                rounded
                                focus:outline-none
                            "
                            @click="submit()"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                                style="width: 24px; height: 24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                            >Proceed
                        </button>
                        <button
                            class="
                                ripple
                                transition-colors
                                duration-100
                                ease-out
                                hover:bg-gray-300
                                focus:outline-none
                                mt-2
                                xs2:mt-0 xs2:ml-2
                                bg-transparent
                                text-gray-800
                                py-2
                                px-4
                                rounded
                            "
                            @click="toggleModal()"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
//aggiungere calcolo per le qualifications master e adult
import { required, between } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
export default {
    name: 'modalLaunch',
    data() {
        return {
            hitNum: 1,
            hitMax: 0,
            loading: false,
            title: 'Set HITs to be launched',
            mtFee: 0,
            workerPrice: 0,
            masterFee: 0,
        }
    },
    props: {
        hitsSubmitted: Number,
        hitsTotal: Number,
        priceData: Object,
        id: String,
        qualifications: Object,
    },
    setup: () => ({ $v: useVuelidate() }),
    created() {
        this.hitMax = parseInt(this.hitsTotal) - parseInt(this.hitsSubmitted)
        this.calculatePrice()
        if (this.$route.name == 'Home') {
            this.API()
                .get('?action=getProjectInfo&id=' + this.id)
                .then((res) => {
                    this.$emit('changeQualification', res.data.values.master)
                })
                .catch((err) => {
                    console.error(err)
                })
        }
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
        document.getElementById('modal').focus()
    },
    validations() {
        return {
            hitNum: {
                required,
                between: between(1, this.hitMax),
            },
        }
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.toggleModal()
            } else if (event.code == 'Enter') {
                this.submit()
            }
        },
        calculatePrice(mode) {
            if (mode == 'max') {
                this.hitNum = this.hitMax
            }
            if (this.priceData.assignment >= 10) {
                this.mtFee =
                    Math.round(
                        (this.hitNum * this.priceData.assignment * this.priceData.reward * 0.4 + 0.00001) * 100
                    ) /
                        100 <
                    0.01
                        ? 0.01
                        : Math.round(
                              (this.hitNum * this.priceData.assignment * this.priceData.reward * 0.4 + 0.00001) * 100
                          ) / 100
            } else {
                this.mtFee =
                    Math.round(
                        (this.hitNum * this.priceData.assignment * this.priceData.reward * 0.2 + 0.00001) * 100
                    ) /
                        100 <
                    0.01
                        ? 0.01
                        : Math.round(
                              (this.hitNum * this.priceData.assignment * this.priceData.reward * 0.2 + 0.00001) * 100
                          ) / 100
            }

            this.workerPrice =
                Math.round((this.hitNum * this.priceData.assignment * this.priceData.reward + 0.00001) * 100) / 100
            if (this.qualifications.master == 0) {
                this.masterFee = 0.0
            } else {
                this.masterFee =
                    Math.round(
                        (this.hitNum * this.priceData.assignment * this.priceData.reward * 0.05 + 0.00001) * 100
                    ) / 100
            }
        },
        toggleModal() {
            this.$emit('launchModal')
        },
        submit() {
            this.$v.$touch()
            if (this.hitsSubmitted == 0) {
                var x = 'Project'
            } else {
                x = 'HITs'
            }
            if (!this.$v.$invalid) {
                this.loading = true
                var url = '?action=updateProjectStatus&id=' + this.id
                this.API()
                    .post(
                        url,
                        {
                            toStatus: 3,
                            num: this.hitNum,
                        },
                        { 'Content-Type': 'application/x-www-form-urlencoded' }
                    )
                    .then((response) => {
                        console.log(response.data)
                        if (response.data.result == 'ERR') {
                            response.data.error.includes('User')
                                ? this.$emit('err', 'Error: ' + response.data.error + '.')
                                : this.$emit('err', 'Error: ' + response.data.error)
                        } else {
                            this.$emit('launched', x + ' launched successfully!')
                            this.toggleModal()
                        }
                        this.loading = false
                    })
                    .catch(() => {
                        this.$emit('err', 'Error: server unreacheable')
                        this.loading = false
                    })
            } else {
                this.$emit('snackbar', 'Error: check inserted values')
            }
        },
    },
    watch: {
        qualifications() {
            this.calculatePrice('basic')
        },
    },
}
</script>

<style scoped>
.fade-enter-active {
    transition: opacity 0.25s ease-out !important;
}
.fade-leave-active {
    transition: opacity 0.2s ease-out !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
.customZ {
    z-index: 990;
}
</style>
