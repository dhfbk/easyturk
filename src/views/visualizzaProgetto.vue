<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <transition name="fade" v-if="modal">
            <div
                class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
            >
                <div class="bg-white rounded-lg w-1/2">
                    <div class="flex flex-col p-4">
                        <div class="flex w-full">
                            <div class="text-gray-900 font-bold text-lg">Conferma azione</div>
                            <svg
                                class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                                @click="toggleModal()"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                        </div>
                        <div
                            class="py-2"
                        >Sicuro di voler eliminare il progetto? Questa azione è irreversibile.</div>

                        <div class="ml-auto">
                            <button
                                class="transition duration-150 ease-in-out bg-orange-400 hover:bg-orange-700 text-black hover:text-white font-bold py-2 px-4 rounded focus:outline-none"
                                @click="toggleModal()"
                            >Elimina</button>
                            <button
                                class="transition duration-150 ease-in-out border border-solid border-gray-400 hover:bg-gray-400 focus:outline-none ml-2 bg-transparent text-black font-semibold py-2 px-4 rounded"
                                @click="toggleModal()"
                            >Annulla</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="flex justify-between flex-wrap">
            <h1 class="text-2xl mb-4 text-orange-400">{{datiProgetto.nome}}</h1>
            <div class="flex relative">
                <button
                    v-show="!mini"
                    type="submit"
                    class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-green-400 hover:bg-green-400 mr-2 focus:outline-none"
                >Pubblica</button>
                <button
                    v-show="!mini"
                    type="submit"
                    class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 mr-2 focus:outline-none"
                >Risultati</button>
                <button
                    @click="dropdownOpen = !dropdownOpen"
                    v-click-outside="hide"
                    class="py-2 px-2 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 focus:outline-none"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" v-if="!dropdownOpen">
                        <path
                            fill="currentColor"
                            d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"
                        />
                    </svg>
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" v-else>
                        <path
                            fill="currentColor"
                            d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"
                        />
                    </svg>
                </button>
                <transition name="fade">
                    <div
                        v-show="dropdownOpen"
                        class="absolute bottom-1 right-0 mt-16 w-56 bg-white rounded-md shadow-xl z-20"
                    >
                        <a
                            v-show="mini"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400 rounded-t-md"
                        >Pubblica</a>
                        <a
                            v-show="mini"
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400"
                        >Risultati</a>
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400 rounded-t-md"
                            @click="toggleModal()"
                        >Elimina</a>
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400"
                        >Modifica</a>
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400"
                        >Carica dati</a>
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-orange-400 rounded-b-md"
                        >Carica gold</a>
                    </div>
                </transition>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2">
            <div class="mx-0 sm:mx-2">
                <div class="bg-white rounded-md shadow-lg p-4 mb-4">
                    <div>
                        <span class="font-bold">Titolo:&nbsp;</span>
                        <span>{{datiProgetto.titolo}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Descrizione:&nbsp;</span>
                        <span>{{datiProgetto.descrizione}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Keywords:&nbsp;</span>
                        <span>{{datiProgetto.keywords}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Data creazione:&nbsp;</span>
                        <span>{{datiProgetto.creazione}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Stato:&nbsp;</span>
                        <span>{{datiProgetto.stato}}</span>
                    </div>
                </div>
                <div class="bg-white rounded-md shadow-lg p-4 mb-4">
                    <div>
                        <span class="font-bold">Ricompensa per ogni assignment:&nbsp;</span>
                        <span>{{datiProgetto.ricompensa}}&nbsp;$</span>
                    </div>
                    <div>
                        <span class="font-bold">Numero di lavoratori/assignment per task:&nbsp;</span>
                        <span>{{datiProgetto.numLavoratori}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Tempo massimo:&nbsp;</span>
                        <span>{{datiProgetto.tempoMax}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Scadenza:&nbsp;</span>
                        <span>{{datiProgetto.scadenza}}</span>
                    </div>
                    <div>
                        <span class="font-bold">Auto approva e paga lavoratori in:&nbsp;</span>
                        <span>{{datiProgetto.autoApproval}}</span>
                    </div>
                </div>
                <div class="bg-white rounded-md shadow-lg p-4 mb-4">
                    <div>
                        <span class="font-bold">Layout ID:&nbsp;</span>
                        <span>{{datiProgetto.layoutID}}&nbsp;</span>
                    </div>
                    <div>
                        <span class="font-bold">Numero parametri:&nbsp;</span>
                        <span>{{datiProgetto.parametri}}</span>
                    </div>
                </div>
            </div>
            <div class="mx-0 sm:mx-2">
                <div class="bg-white rounded-md shadow-lg p-4 mb-4">
                    <div class="text-lg mb-4">
                        <span class="font-bold">Totale HIT:&nbsp;</span>
                        <span>{{datiProgetto.totaleHIT}}</span>
                    </div>
                    <div class="flex justify-evenly flex-wrap">
                        <vue-ellipse-progress
                            :progress="datiProgetto.completateProgress"
                            :legend-value="datiProgetto.HITcompletate"
                            :color="'#f6ad55'"
                            :size="pieSize"
                            :half="true"
                            :angle="0"
                            class="mx-2 mb-2"
                        >
                            <span slot="legend-value">/{{datiProgetto.totaleHIT}}</span>
                            <p slot="legend-caption">Completate</p>
                        </vue-ellipse-progress>
                        <vue-ellipse-progress
                            :progress="datiProgetto.disponibiliProgress"
                            :legend-value="datiProgetto.HITdisponibili"
                            :color="'#f6ad55'"
                            :size="pieSize"
                            :half="true"
                            :angle="0"
                            class="mx-2 mb-2"
                        >
                            <span slot="legend-value">/{{datiProgetto.totaleHIT}}</span>
                            <p slot="legend-caption">Disponibili</p>
                        </vue-ellipse-progress>
                        <vue-ellipse-progress
                            :progress="datiProgetto.incorsoProgress"
                            :legend-value="datiProgetto.HITinCorso"
                            :color="'#f6ad55'"
                            :size="pieSize"
                            :half="true"
                            :angle="0"
                            class="mx-2"
                        >
                            <span slot="legend-value">/{{datiProgetto.totaleHIT}}</span>
                            <p slot="legend-caption">In corso</p>
                        </vue-ellipse-progress>
                    </div>
                    <div class="w-full flex justify-start -mt-4">
                        <button
                            type="submit"
                            class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 focus:outline-none"
                        >Vai alle HIT</button>
                    </div>
                </div>
                <div class="bg-white rounded-md shadow-lg p-4 mb-4">
                    <div class="text-lg mb-4">
                        <span class="font-bold">Risposte aggregate:</span>
                    </div>
                    <div class="flex justify-evenly flex-wrap">
                        <vue-ellipse-progress
                            :progress="datiProgetto.siProgress"
                            :legend-value="datiProgetto.risposteSI"
                            :color="'#f6ad55'"
                            :size="pieSize"
                            class="mx-2 mb-2"
                        >
                            <span slot="legend-value">/{{datiProgetto.HITcompletate}}</span>
                            <p slot="legend-caption">SI</p>
                        </vue-ellipse-progress>
                        <vue-ellipse-progress
                            :progress="datiProgetto.noProgress"
                            :legend-value="datiProgetto.risposteNO"
                            :color="'#f6ad55'"
                            :size="pieSize"
                            class="mx-2 mb-2"
                        >
                            <span slot="legend-value">/{{datiProgetto.HITcompletate}}</span>
                            <p slot="legend-caption">NO</p>
                        </vue-ellipse-progress>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
export default {
    name: 'visualizzaProgetto',
    directives: {
        ClickOutside,
    },
    data() {
        return {
            datiProgetto: {
                nome: 'nomeProgetto',
                titolo: "Italy's food",
                descrizione:
                    "If you know Italian food, we ask you to take a look at various different serving pictures and tell us if you think it's italian or not.",
                keywords: 'food, italy, image, recognition, culture',
                stato: 'In corso',
                ricompensa: '0.25',
                creazione: '2020-09-02',
                numLavoratori: 5,
                tempoMax: 60,
                scadenza: '2020-09-10',
                autoApproval: 86400,
                layoutID: 'hdf7777cv4cc34c',
                parametri: 3,
                totaleHIT: 400,
                HITcompletate: 200,
                HITdisponibili: 90,
                HITinCorso: 110,
                risposteSI: 180,
                risposteNO: 20,
                completateProgress: 0,
                disponibiliProgress: 0,
                incorsoProgress: 0,
                siProgress: 0,
                noProgress: 0,
            },
            pieSize: 150,
            myWidth: 0,
            dropdownOpen: false,
            mini: false,
            modal: false,
        }
    },
    created() {
        this.elaboraTempo(this.datiProgetto.tempoMax)
        this.elaboraTempo(this.datiProgetto.autoApproval)
        this.calcolaProgress()
        this.displayWindowSize()
        window.onresize = this.displayWindowSize
    },
    mounted() {
        this.popupItem = this.$el
    },
    methods: {
        toggleModal() {
            this.modal = !this.modal
            this.hide()
        },
        hide() {
            this.dropdownOpen = false
        },
        setPieSize() {
            if (this.myWidth < 640) {
                this.pieSize = 120
            } else {
                this.pieSize = 150
            }
        },
        displayWindowSize() {
            this.myWidth = window.innerWidth
            this.setPieSize()
            if (this.myWidth < 700) {
                this.mini = true
            } else {
                this.mini = false
            }
        },
        calcolaProgress() {
            this.datiProgetto.completateProgress =
                (100 * this.datiProgetto.HITcompletate) / this.datiProgetto.totaleHIT
            this.datiProgetto.disponibiliProgress =
                (100 * this.datiProgetto.HITdisponibili) / this.datiProgetto.totaleHIT
            this.datiProgetto.incorsoProgress =
                (100 * this.datiProgetto.HITinCorso) / this.datiProgetto.totaleHIT
            this.datiProgetto.siProgress =
                (100 * this.datiProgetto.risposteSI) / this.datiProgetto.HITcompletate
            this.datiProgetto.noProgress =
                (100 * this.datiProgetto.risposteNO) / this.datiProgetto.HITcompletate
        },
        elaboraTempo(tempo) {
            var tmp = 0
            if (tempo < 60) {
                tmp = Math.floor(tempo) + ' secondi'
            } else if (tempo < 3600) {
                tmp = Math.floor(tempo / 60) + ' minuti'
            } else if (tempo < 86400) {
                tmp = Math.floor(tempo / 3600) + ' ore'
            } else {
                tmp = Math.floor(tempo / 86400) + ' giorni'
            }
            if (tempo == this.datiProgetto.tempoMax) {
                this.datiProgetto.tempoMax = tmp
            } else {
                this.datiProgetto.autoApproval = tmp
            }
        },
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>