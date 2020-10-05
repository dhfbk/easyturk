<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div v-if="mode == 'csv'">
            <span class="font-bold tracking-tight">CSV status</span>
            <div class="mb-1">
                <span v-if="projectData.numData > 0">
                    <div class="flex flex-row flex-wrap justify-between content-center items-center">
                        <span>
                            <span>Data:&nbsp;</span>
                            <span class="text-green-600">uploaded</span>
                        </span>
                        <button
                            class="px-2 py-1 ripple bg-transparent transition duration-150 hover:bg-gray-300 rounded focus:outline-none"
                            @click="
                                $router.push({
                                    name: 'csv',
                                    params: { projectId: projectData.id, type: 'data' },
                                })
                            "
                        >
                            View data
                        </button>
                    </div>
                </span>
                <span v-else><span>Data:&nbsp;</span><span class="text-red-500"> not uploaded</span></span>
            </div>
            <hr />
            <div class="mt-1">
                <span v-if="projectData.numGold > 0">
                    <div class="flex flex-row flex-wrap justify-between content-center items-center">
                        <span>
                            <span>Gold:&nbsp;</span>
                            <span class="text-green-600">uploaded</span>
                        </span>
                        <button
                            class="px-2 py-1 ripple bg-transparent transition duration-150 hover:bg-gray-300 rounded focus:outline-none"
                            @click="
                                $router.push({
                                    name: 'csv',
                                    params: { projectId: projectData.id, type: 'gold' },
                                })
                            "
                        >
                            View gold
                        </button>
                    </div>
                </span>
                <span v-else><span>Gold:&nbsp;</span><span class="text-red-500"> not uploaded</span></span>
            </div>
        </div>
        <div v-else-if="mode == 'status'">
            <span class="font-bold tracking-tight">Status:</span>
            <span class="flex justify-between items-center">
                <span v-if="projectData.status == 0 && projectData.numData > 0">
                    <span class="text-green-500">data uploaded. </span>Ready to
                    <span class="font-bold text-primary"> create HITs.</span>
                </span>
                <span v-else-if="projectData.status == 0 && projectData.numData == 0">
                    <span class="text-green-500">project created. </span
                    ><span class="font-bold text-primary">Upload csv data </span>and<span
                        class="font-bold text-primary"
                    >
                        csv gold</span
                    >
                    to advance.
                </span>
                <span v-if="projectData.status == 1">
                    <span class="text-green-500">HITs created. </span>Ready to
                    <span class="font-bold text-primary"> set the layout.</span>
                </span>
                <span v-if="projectData.status == 2">
                    <span class="text-green-500">HIT layout set. </span>Ready to finally
                    <span class="font-bold text-primary"> publish the project!</span>
                </span>
                <button
                    @click="$emit('modal', 'instructions')"
                    class="ripple px-2 py-1 bg-white hover:bg-gray-300 rounded focus:outline-none transition duration-150"
                >
                    Need help?
                </button>
            </span>
        </div>
        <div v-for="(n, i) in titles.length" :key="n" v-else class="">
            <span class="font-bold tracking-tight">{{ titles[i] }}:&nbsp;</span>
            <span class="">{{ data[i] }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cardInfo',
    props: {
        projectData: Object,
        mode: String,
    },

    data() {
        return {
            titles: [],
            data: [],
            statusText: [],
        }
    },
    created() {
        switch (this.mode) {
            case 'general':
                this.titles = ['Title', 'Description', 'Keywords', 'Creation date']
                this.data = [
                    this.projectData.title,
                    this.projectData.description,
                    this.projectData.keywords,
                    this.projectData.created_at,
                ]
                break
            case 'layout':
                this.titles = ['Layout ID', 'Number of records per HIT']
                this.data = [this.projectData.layout_id, this.projectData.params]
                break
            case 'payment':
                this.titles = [
                    'Ricompensa per ogni assignment',
                    'Numero di lavoratori / assignment per task',
                    'Tempo massimo',
                    'Scadenza',
                    'Auto approva e paga lavoratori in',
                ]
                this.data = [
                    this.projectData.reward + '$',
                    this.projectData.workers,
                    this.time(parseInt(this.projectData.max_time)),
                    this.time(parseInt(this.projectData.expiry)),
                    this.time(parseInt(this.projectData.auto_approve)),
                ]
                break
            case 'csv':
                this.titles = ['Base CSV status', 'Gold CSV status']
                this.data = [this.projectData.numData, parseInt(this.projectData.numGold)]
                break
        }
    },
    methods: {
        time(num) {
            if (num < 60) {
                num += ' minuti'
            } else if (this.projectData.tempoMax < 1440) {
                num = num / 60 + ' ore'
            } else {
                num = num / 1440 + ' giorni'
            }
            return num
        },
    },
}
</script>

<style></style>
