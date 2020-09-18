<template>
    <div class="md:w-5/6 bg-white shadow-md rounded px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <p class="text-2xl mb-4 text-primary">{{ pageTitle }}</p>
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold mb-2" for="nome"
                    >Nome progetto</label
                >
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="nome"
                    type="text"
                    placeholder="Nome progetto"
                    maxlength="255"
                    v-model="name"
                    required
                />
                <p class="text-gray-700 text-xs italic">Il nome non è mostrato ai lavoratori.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold mb-2" for="titolo"
                    >Titolo</label
                >
                <input
                    :class="errorTitolo ? 'border-red-600' : 'border-gray-200'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="titolo"
                    type="text"
                    placeholder="Titolo"
                    maxlength="255"
                    v-model="title"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Descrivi il progetto ai lavoratori. Sii più specifico/a possibile, es. "answer a
                    survey about movies", instead of "short survey", così che i lavoratori sappiano
                    cosa aspettarsi.
                </p>
                <p :class="errorTitolo ? '' : 'hidden'" class="text-red-600 text-xs italic">
                    Si è pregati di inserire il titolo
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="descrizione"
                    >Descrizione</label
                >
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="descrizione"
                    type="text"
                    placeholder="Descrizione"
                    v-model="description"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Fornisci più dettagli sul progetto. Questo consente ai lavoratori di avere più
                    informazioni prima che decidano di visualizzare il compito.
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
                    v-model="keywords"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Fornisci keywords che aiuteranno i lavoratori a trovare i tuoi compiti.
                </p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="ricompensa"
                    >Ricompensa per ogni assignment</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="ricompensa"
                    type="number"
                    min="0.01"
                    step="0.01"
                    placeholder="0.01 $"
                    v-model="reward"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Questo è quanto un lavoratore verrà pagato per completare un compito. Considera
                    quanto tempo dovrà impiegare per completare il compito.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="lavoratori"
                    >Numero di lavoratori/assignment per task</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="lavoratori"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    v-model="workers"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Quanti lavoratori vuoi che completino i tuoi task?
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="tempoMax"
                    >Tempo massimo</label
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
                        v-model="max_time"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorTempoMax"
                            v-model="selectTempoMax"
                            @change="fixMax(1)"
                        >
                            <option value="giorni">Giorni</option>
                            <option selected value="ore">Ore</option>
                            <option value="minuti">Minuti</option>
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
                    Tempo massimo che un lavoratore ha per completare il task. Sii generoso/a così
                    che i lavoratori non vadano di fretta.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="scadenza"
                    >Scadenza</label
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
                        v-model="expiry"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorScadenza"
                            v-model="selectExpiry"
                            @change="fixMax(2)"
                        >
                            <option selected value="giorni">Giorni</option>
                            <option value="ore">Ore</option>
                            <option value="minuti">Minuti</option>
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
                    Tempo massimo che il compito sarà disponibile per i lavoratori su Mechanical
                    Turk.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="autoApproval"
                    >Auto approva e paga i lavoratori in</label
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
                        v-model="auto_approve"
                        required
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorApproval"
                            v-model="selectAutoApprove"
                            @change="fixMax(3)"
                        >
                            <option selected value="giorni">Giorni</option>
                            <option value="ore">Ore</option>
                            <option value="minuti">Minuti</option>
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
                    Questo è il tempo che hai per rifiutare un assignment di un lavoratore dopo che
                    è stato inviato.
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
                    v-model="layout_id"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Questo è l'id del layout da far visualizzare al lavoratore.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="parametri"
                    >Numero di parametri</label
                >
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="parametri"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    v-model="params"
                    required
                />
            </div>
        </div>
        <div class="w-full flex justify-end flex-row">
            <button
                @click="caricaProgetto()"
                type="submit"
                class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary focus:outline-none"
                v-if="mode == 'modificaProgetto'"
            >
                Salva modifiche
            </button>
            <button
                @click="caricaProgetto()"
                type="submit"
                class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary focus:outline-none"
                v-else
            >
                Salva
            </button>
        </div>
    </div>
</template>

<script>
//let $ = require('jquery')
import axios from 'axios'

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
            selectTempoMax: 'ore',
            selectExpiry: 'giorni',
            selectAutoApprove: 'giorni',
            max1: 23,
            max2: 364,
            max3: 364,
            mode: 'nuovoProgetto',
        }
    },
    created() {
        this.mode = this.$route.name
        if (this.$route.path == '/create') {
            this.mode = this.$route.name
            this.pageTitle = 'Crea un nuovo progetto'
        } else {
            this.pageTitle = 'Modifica progetto'
            this.getDatiPrj()
        }
    },
    methods: {
        getDatiPrj() {
            axios({
                url:
                    'https://web.apnetwork.it/mturk/?action=editProject&id=' +
                    this.$route.params.idProgetto,
                method: 'get',
            }).then(res => {
                this.id = res.data.values.id
                this.name = res.data.values.name
                this.title = res.data.values.title
                this.description = res.data.values.description
                this.keywords = res.data.values.keywords
                this.reward = res.data.values.reward
                this.max_time = res.data.values.max_time
                this.expiry = res.data.values.expiry
                this.auto_approve = res.data.values.auto_approve
                this.layout_id = res.data.values.layout_id
                this.params = res.data.values.params
            })
            // var self = this
            // $.ajax({
            //     url:
            //         'https://web.apnetwork.it/mturk/?action=editProject&id=' +
            //         this.$route.params.idProgetto,
            //     dataType: 'json',
            //     method: 'get',
            //     success: function(data) {
            //         self.id = data.values.id
            //         self.name = data.values.name
            //         self.title = data.values.title
            //         self.description = data.values.description
            //         self.keywords = data.values.keywords
            //         self.reward = data.values.reward
            //         self.max_time = data.values.max_time
            //         self.expiry = data.values.expiry
            //         self.auto_approve = data.values.auto_approve
            //         self.layout_id = data.values.layout_id
            //         self.params = data.values.params
            //     },
            //     error: function(data) {
            //         console.log(data)
            //     },
            // })
        },
        caricaProgetto() {
            this.elaboraTempo('max_time')
            this.elaboraTempo('expiry')
            this.elaboraTempo('auto_approve')
            this.parseNumbers()
            // var url = 'https://web.apnetwork.it/mturk/?action=addProject'
            // if (this.mode == 'modificaProgetto') {
            //     url = url + '&id=' + this.id
            // }
            // $.ajax({
            //     url: url,
            //     dataType: 'json',
            //     data: {
            //         name: this.name,
            //         title: this.title,
            //         description: this.description,
            //         keywords: this.keywords,
            //         reward: this.reward,
            //         workers: this.workers,
            //         max_time: this.max_time_send,
            //         expiry: this.expiry_send,
            //         auto_approve: this.auto_approve_send,
            //         layout_id: this.layout_id,
            //         params: this.params,
            //     },
            //     method: 'post',
            //     success() {
            //         console.log('informazioni inserite')
            //     },
            //     error(data) {
            //         console.log(data)
            //     },
            // })
            axios({
                method: 'post',
                url: 'https://web.apnetwork.it/mturk/?action=addProject',
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
                    console.log(response)
                    if (response.data.result == 'OK') {
                        console.log('Inserimento avvenuto')
                    } else {
                        console.log('Errore')
                    }
                })
                .catch(err => {
                    console.log(err)
                })
        },
        //modifica il tempo da minuti a minuti/ore/giorni
        elaboraTempo(nomeVar) {
            if (nomeVar == 'max_time') {
                if (this.selectTempoMax == 'ore') {
                    this.max_time_send = this.max_time * 60
                } else if (this.selectTempoMax == 'giorni') {
                    this.max_time_send = this.max_time * 1440
                }
            } else if (nomeVar == 'expiry') {
                if (this.selectExpiry == 'ore') {
                    this.expiry_send = this.expiry * 60
                } else if (this.selectExpiry == 'giorni') {
                    this.expiry_send = this.expiry * 1440
                }
            } else {
                if (this.selectAutoApprove == 'ore') {
                    this.auto_approvesend = this.auto_approve * 60
                } else if (this.selectAutoApprove == 'giorni') {
                    this.auto_approvesend = this.auto_approve * 1440
                }
            }
        },
        fixMax(num) {
            switch (num) {
                case 1:
                    if (this.selectTempoMax == 'minuti') {
                        this.max1 = 59
                    } else if (this.selectTempoMax == 'ore') {
                        this.max1 = 23
                    } else if (this.selectTempoMax == 'giorni') {
                        this.max1 = 364
                    }
                    break
                case 2:
                    if (this.selectExpiry == 'minuti') {
                        this.max2 = 59
                    } else if (this.selectExpiry == 'ore') {
                        this.max2 = 23
                    } else if (this.selectExpiry == 'giorni') {
                        this.max2 = 364
                    }
                    break
                case 3:
                    if (this.selectAutoApprove == 'minuti') {
                        this.max3 = 59
                    } else if (this.selectAutoApprove == 'ore') {
                        this.max3 = 23
                    } else if (this.selectAutoApprove == 'giorni') {
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
