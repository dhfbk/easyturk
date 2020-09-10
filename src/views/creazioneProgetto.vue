<template>
    <div class="md:w-5/6 bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <p class="text-2xl mb-4 text-orange-400">{{pageTitle}}</p>
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="nome"
                >Nome progetto</label>
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="nome"
                    type="text"
                    placeholder="Nome progetto"
                />
                <p class="text-gray-700 text-xs italic">Il nome non è mostrato ai lavoratori.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="titolo"
                >Titolo</label>
                <input
                    :class="errorTitolo ? 'border-red-600': 'border-gray-200'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="titolo"
                    type="text"
                    placeholder="Titolo"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Descrivi il progetto ai lavoratori. Sii più specifico/a possibile, es. "answer a survey about movies", instead of "short survey", così che i lavoratori sappiano cosa aspettarsi.</p>
                <p
                    :class="errorTitolo ? '': 'hidden'"
                    class="text-red-600 text-xs italic"
                >Si è pregati di inserire il titolo</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="descrizione"
                >Descrizione</label>
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="descrizione"
                    type="text"
                    placeholder="Descrizione"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Fornisci più dettagli sul progetto. Questo consente ai lavoratori di avere più informazioni prima che decidano di visualizzare il compito.</p>
            </div>
            <div class="md:w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="keywords"
                >Keywords</label>
                <input
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="keywords"
                    type="text"
                    placeholder="Keywords"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Fornisci keywords che aiuteranno i lavoratori a trovare i tuoi compiti.</p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="ricompensa"
                >Ricompensa per ogni assignment</label>
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="ricompensa"
                    type="number"
                    min="0.01"
                    step="0.01"
                    placeholder="0.01 $"
                    value="0.01"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Questo è quanto un lavoratore verrà pagato per completare un compito. Considera quanto tempo dovrà impiegare per completare il compito.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="lavoratori"
                >Numero di lavoratori/assignment per task</label>
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="lavoratori"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    value="5"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Quanti lavoratori vuoi che completino i tuoi task?</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="tempoMax"
                >Tempo massimo</label>
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="tempoMax"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="1"
                        value="1"
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorTempoMax"
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
                <p
                    class="text-gray-700 text-xs italic"
                >Tempo massimo che un lavoratore ha per completare il task. Sii generoso/a così che i lavoratori non vadano di fretta.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="scadenza"
                >Scadenza</label>
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="scadenza"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="1"
                        value="7"
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorScadenza"
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
                <p
                    class="text-gray-700 text-xs italic"
                >Tempo massimo che il compito sarà disponibile per i lavoratori su Mechanical Turk.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="autoApproval"
                >Auto approva e paga i lavoratori in</label>
                <div class="flex flex-col flex-grow sm:flex-row">
                    <input
                        class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="autoApproval"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="1"
                        value="3"
                    />
                    <div class="relative mt-1 sm:mt-0 sm:ml-2">
                        <select
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorApproval"
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
                <p
                    class="text-gray-700 text-xs italic"
                >Questo è il tempo che hai per rifiutare un assignment di un lavoratore dopo che è stato inviato.</p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="layoutId"
                >Layout ID</label>
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="layoutId"
                    type="text"
                    placeholder="Layout ID"
                />
                <p
                    class="text-gray-700 text-xs italic"
                >Questo è l'id del layout da far visualizzare al lavoratore.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold mb-2"
                    for="parametri"
                >Numero di parametri</label>
                <input
                    class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mb-2 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="parametri"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    value="1"
                />
            </div>
        </div>
        <div class="w-full flex justify-end flex-row">
            <button
                type="submit"
                class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 focus:outline-none "
            >Salva</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'creazioneProgetto',
    data() {
        return {
            errorTitolo: false,
            pageTitle: ""
        }
    },
    created(){
        if(this.$route.path == "/create"){
            this.pageTitle = "Crea un nuovo progetto"
        }
        else{
            this.pageTitle = "Modifica progetto"
        }
    }
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