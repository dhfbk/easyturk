<template>
    <form
        class="md:w-5/6 bg-white shadow-md rounded px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto"
        @submit.prevent="caricaProgetto"
    >
        <p class="text-2xl mb-4 text-primary">{{ pageTitle }}</p>
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold mb-2" for="nome"
                    >Project Name</label
                >
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="nome"
                    type="text"
                    placeholder="Nome progetto"
                    maxlength="255"
                    v-model.trim="$v.name.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">This name is not displayed to Workers.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold mb-2" for="titolo"
                    >Title</label
                >
                <input
                    :class="errorTitolo ? 'border-red-600' : 'border-gray-200'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="titolo"
                    type="text"
                    placeholder="Titolo"
                    maxlength="255"
                    v-model.trim="$v.title.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Describe the survey to Workers. Be as specific as possible, e.g. "answer a
                    survey about movies", instead of "short survey", so Workers know what to expect.
                </p>
                <p :class="errorTitolo ? '' : 'hidden'" class="text-red-600 text-xs italic">
                    Si è pregati di inserire il titolo
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="descrizione"
                    >Description</label
                >
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="descrizione"
                    type="text"
                    placeholder="Descrizione"
                    v-model.trim="$v.description.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Give more detail about this survey. This gives Workers a bit more information
                    before they decide to view your survey.
                </p>
            </div>
            <div class="md:w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="keywords"
                    >Keywords</label
                >
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="keywords"
                    type="text"
                    placeholder="Keywords"
                    v-model.trim="$v.keywords.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Provide keywords that will help Workers search for your tasks.
                </p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="ricompensa"
                    >Reward per response</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="ricompensa"
                    type="number"
                    min="0.01"
                    step="0.01"
                    placeholder="0.01 $"
                    v-model.trim="$v.reward.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    This is how much a Worker will be paid for completing your survey. Consider how
                    long it will take a Worker to complete your survey.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="lavoratori"
                    >Number of respondents</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="lavoratori"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    v-model.trim="$v.workers.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    How many unique Workers do you want to complete your survey?
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="tempoMax"
                    >Time allotted per Worker</label
                >
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="tempoMax"
                        type="number"
                        min="1"
                        :max="max1"
                        step="1"
                        placeholder="1"
                        v-model.trim="$v.max_time.$model"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorTempoMax"
                            v-model="selectTempoMax"
                            @change="fixMax(1)"
                        >
                            <option selected value="days">Days</option>
                            <option value="hours">Hours</option>
                            <option value="minutes">Minutes</option>
                        </select>
                        <div
                            class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                        >
                            <svg
                                class="h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 text-xs italic">
                    Maximum time a Worker has to complete the survey. Be generous so that Workers
                    are not rushed.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="scadenza"
                    >Survey expires in</label
                >
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="scadenza"
                        type="number"
                        min="1"
                        :max="max2"
                        step="1"
                        placeholder="1"
                        v-model.trim="$v.expiry.$model"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorScadenza"
                            v-model="selectExpiry"
                            @change="fixMax(2)"
                        >
                            <option selected value="days">Days</option>
                            <option value="hours">Hours</option>
                            <option value="minutes">Minutes</option>
                        </select>
                        <div
                            class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                        >
                            <svg
                                class="h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 text-xs italic">
                    Maximum time your survey will be available to Workers on Mechanical Turk.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="autoApproval"
                    >Auto-approve and pay Workers in</label
                >
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="autoApproval"
                        type="number"
                        min="1"
                        :max="max3"
                        step="1"
                        placeholder="1"
                        v-model.trim="$v.auto_approve.$model"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorApproval"
                            v-model="selectAutoApprove"
                            @change="fixMax(3)"
                        >
                            <option selected value="days">Days</option>
                            <option value="hours">Hours</option>
                            <option value="minutes">Minutes</option>
                        </select>
                        <div
                            class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                        >
                            <svg
                                class="h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 text-xs italic">
                    This is the amount of time you have to reject a Worker's assignment after they
                    submit the assignment.
                </p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="layoutId"
                    >Layout ID</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="layoutId"
                    type="text"
                    placeholder="Layout ID"
                    maxlength="255"
                    v-model.trim="$v.layout_id.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    This is the id of the layout that will be shown to the Worker.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="parametri"
                    >Parameters number</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="parametri"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    v-model.trim="$v.params.$model"
                    required
                />
            </div>
        </div>
        <div class="w-full flex justify-end flex-row">
            <button
                type="submit"
                class="flex flex-row py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary focus:outline-none"
                v-if="mode == 'modificaProgetto'"
            >
                <svg
                    :class="loading ? 'animate-spin mr-1' : 'hidden'"
                    style="width:24px;height:24px"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                >Save changes
            </button>
            <button
                type="submit"
                class="flex flex-row py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary focus:outline-none"
                v-else
            >
                <svg
                    :class="loading ? 'animate-spin mr-1' : 'hidden'"
                    style="width:24px;height:24px"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                >Save
            </button>
            <button
                @click="router.go(-1)"
                class="flex flex-row bg-gray-300 hover:bg-gray-400 py-2 px-4 ml-4 rounded-md transition duration-150 ease-in-out focus:outline-none"
            >
                Cancel
            </button>
        </div>
    </form>
</template>

<script>
//let $ = require('jquery')
import axios from 'axios'
const { required } = require('vuelidate/lib/validators')

export default {
    name: 'creazioneProgetto',
    data() {
        return {
            errorTitolo: false,
            id: '',
            name: '',
            title: '',
            description: '',
            keywords: '',
            reward: 0.05,
            workers: 3,
            max_time: 1,
            max_time_send: 1,
            expiry: 1,
            expiry_send: 1,
            auto_approve: 1,
            auto_approve_send: 1,
            layout_id: '',
            params: 3,
            /*
            pageTitle: '',
            name: '',
            title: '',
            description: '',
            keywords: '',
            reward: 0.05,
            workers: 5,
            max_time: 0,
            max_time_send: 0,
            expiry: 0,
            expiry_send: 0,
            auto_approve: 0,
            auto_approve_send: 0,
            layout_id: '',
            params: 1,
            */
            selectTempoMax: 'hours',
            selectExpiry: 'days',
            selectAutoApprove: 'days',
            max1: 23,
            max2: 364,
            max3: 364,
            mode: 'nuovoProgetto',
            loading: false,
        }
    },
    validations() {
        return {
            name: {
                required,
            },
            title: {
                required,
            },
            description: {
                required,
            },
            keywords: {
                required,
            },
            layout_id: {
                required,
            },
            workers: {
                required,
            },
            max_time: {
                required,
            },
            expiry: {
                required,
            },
            auto_approve: {
                required,
            },
            params: {
                required,
            },
            reward: {
                required,
            },
        }

        /*
        title: {
            required,
            minLength: minLength(2),
        },
        description: {
            required,
            minLength: minLength(2),
        },
        keywords: {
            required,
            minLength: minLength(2),
        },
        */
    },
    created() {
        this.mode = this.$route.name
        if (this.$route.path == '/create') {
            this.mode = this.$route.name
            this.pageTitle = 'Create new project'
        } else {
            this.pageTitle = 'Edit project ' + this.$route.params.projectId
            this.getDatiPrj()
        }
    },
    methods: {
        emitSnackbar(msg) {
            this.$emit('snackbar', msg)
        },
        getDatiPrj() {
            axios({
                url: this.APIURL + '?action=getProjectInfo&id=' + this.$route.params.projectId,
                method: 'get',
            }).then(res => {
                console.log(res.data)
                this.id = res.data.values.id
                this.name = res.data.values.name
                this.title = res.data.values.title
                this.description = res.data.values.description
                this.keywords = res.data.values.keywords
                this.reward = res.data.values.reward
                this.max_time = parseInt(res.data.values.max_time)
                this.expiry = parseInt(res.data.values.expiry)
                this.auto_approve = parseInt(res.data.values.auto_approve)
                this.layout_id = res.data.values.layout_id
                this.params = res.data.values.params
                this.elaboraTempoGET('max_time')
                this.elaboraTempoGET('expiry')
                this.elaboraTempoGET('auto_approve')
            })
        },
        caricaProgetto() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                this.loading = true
                this.elaboraTempo('max_time')
                this.elaboraTempo('expiry')
                this.elaboraTempo('auto_approve')
                this.parseNumbers()
                var url = this.APIURL + '?action=addProject'
                if (this.mode == 'edit') {
                    url = url + '&id=' + this.id
                }
                axios({
                    method: 'post',
                    url: url,
                    data: {
                        name: this.name,
                        title: this.title,
                        description: this.description,
                        keywords: this.keywords,
                        reward: this.reward,
                        workers: this.workers,
                        max_time: this.max_time_send,
                        expiry: this.expiry_send,
                        auto_approve: this.auto_approve_send,
                        layout_id: this.layout_id,
                        params: this.params,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                })
                    .then(response => {
                        this.loading = false
                        console.log(response.data.result)
                        if (response.data.result == 'OK') {
                            console.log('Inserimento avvenuto')
                        } else {
                            console.log('Errore')
                        }
                        this.goHome()
                    })
                    .catch(() => {
                        this.loading = false
                        var msg = 'Error. Try Again'
                        this.emitSnackbar(msg)
                    })
            }
        },
        goHome() {
            var msg
            if (this.mode == 'edit') {
                msg = 'Edit successful'
            } else {
                msg = 'Project created'
            }
            this.emitSnackbar(msg)
            this.$router.push({ path: '/', name: 'Home' })
        },
        elaboraTempoGET(nomeVar) {
            if (nomeVar == 'max_time') {
                if (this.max_time < 60) {
                    this.selectTempoMax = 'minutes'
                } else if (this.max_time < 1440) {
                    this.max_time = this.max_time / 60
                    this.selectTempoMax = 'hours'
                } else {
                    this.max_time = this.max_time / 1440
                    this.selectTempoMax = 'days'
                }
            } else if (nomeVar == 'expiry') {
                if (this.expiry < 60) {
                    this.selectExpiry = 'minutes'
                } else if (this.expiry < 1440) {
                    this.expiry = this.expiry / 60
                    this.selectExpiry = 'hours'
                } else {
                    this.expiry = this.expiry / 1440
                    this.selectExpiry = 'days'
                }
            } else {
                if (this.auto_approve < 60) {
                    this.selectAutoApprove = 'minutes'
                } else if (this.auto_approve < 1440) {
                    this.auto_approve = this.auto_approve / 60
                    this.selectAutoApprove = 'hours'
                } else {
                    this.auto_approve = this.auto_approve / 1440
                    this.selectAutoApprove = 'days'
                }
            }
        },
        //modifica il tempo da minuti a minuti/ore/giorni
        elaboraTempo(nomeVar) {
            if (nomeVar == 'max_time') {
                if (this.selectTempoMax == 'ore') {
                    this.max_time_send = this.max_time * 60
                } else if (this.selectTempoMax == 'days') {
                    this.max_time_send = this.max_time * 1440
                }
            } else if (nomeVar == 'expiry') {
                if (this.selectExpiry == 'hours') {
                    this.expiry_send = this.expiry * 60
                } else if (this.selectExpiry == 'days') {
                    this.expiry_send = this.expiry * 1440
                }
            } else {
                if (this.selectAutoApprove == 'hours') {
                    this.auto_approvesend = this.auto_approve * 60
                } else if (this.selectAutoApprove == 'days') {
                    this.auto_approvesend = this.auto_approve * 1440
                }
            }
        },
        fixMax(num) {
            switch (num) {
                case 1:
                    if (this.selectTempoMax == 'minutes') {
                        this.max1 = 59
                    } else if (this.selectTempoMax == 'hours') {
                        this.max1 = 23
                    } else if (this.selectTempoMax == 'days') {
                        this.max1 = 364
                    }
                    break
                case 2:
                    if (this.selectExpiry == 'minutes') {
                        this.max2 = 59
                    } else if (this.selectExpiry == 'hours') {
                        this.max2 = 23
                    } else if (this.selectExpiry == 'days') {
                        this.max2 = 364
                    }
                    break
                case 3:
                    if (this.selectAutoApprove == 'minutes') {
                        this.max3 = 59
                    } else if (this.selectAutoApprove == 'hours') {
                        this.max3 = 23
                    } else if (this.selectAutoApprove == 'days') {
                        this.max3 = 364
                    }
                    break
            }
        },
        parseNumbers() {
            this.reward = parseFloat(this.reward)
            this.workers = parseInt(this.workers)
            this.max_time = parseInt(this.max_time)
            this.expiry = parseInt(this.expiry)
            this.auto_approve = parseInt(this.auto_approve)
            this.params = parseInt(this.params)
        },
    },
}
</script>

<style scoped>
.pin-r {
    right: 0;
}
.pin-y {
    top: 0;
    bottom: 0;
}
hr.solid {
    border-top: 1px solid #bbb;
}
</style>
