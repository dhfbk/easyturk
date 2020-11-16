<template>
    <div class="block lg:w-5/6 mx-2 xs2:mx-4 lg:mx-auto pt-2">
        <button
            @click="$router.push({ name: 'Home' })"
            :content="'Back'"
            v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
            class="rounded ripple bg-transparent hover:bg-gray-400 p-2 focus:outline-none"
        >
            <svg class="inline" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
            </svg>
            <span class="sr-only">Back to project list</span>
        </button>
        <p v-if="!loading" class="inline text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">
            {{ filename }}
        </p>
        <div class="flex flex-row justify-start mr-auto mb-1 ml-2">
            <p class="text-md my-auto mr-1">Results per page:</p>
            <input
                class="appearance-none h-full w-20 m-2 rounded border block appearance-none bg-white border-gray-400 text-gray-700 pl-2 pr-1 py-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                type="number"
                id="quantity"
                name="quantity"
                min="10"
                max="100"
                step="10"
                v-model="numPerPage"
            />
        </div>
        <loader :type="'csvData'" v-if="loading" />
        <div class="overflow-x-auto" v-else>
            <table class="shadow-md rounded xs2:min-w-5/6 bg-white mx-auto">
                <thead>
                    <tr class="text-left text-white bg-primary border-b border-gray-300 uppercase">
                        <th class="px-2" v-for="x in headers" :key="x">{{ x }}</th>
                        <th class="px-2" v-if="cluster.length > 0">Cluster indexes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(x, index) in current" :key="index" class="border-b border-gray-300 hover:bg-gray-100">
                        <td
                            class="p-1 px-2 border-r border-gray-300"
                            v-for="i in headers.length"
                            :key="i"
                            v-html="x[i - 1]"
                        ></td>
                        <td class="p-1 px-2" v-if="cluster[index]">{{ cluster[index] }}</td>
                        <td class="p-1 px-2" v-else>No cluster</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="grid grid-cols-3 mt-2" v-if="totalNum > numPerPage">
            <button
                @click="page--"
                v-if="totalNum > numPerPage && page >= 1"
                class="ripple-light sm:w-48 w-32 hover:bg-gray-400 py-2 px-4 rounded m-2 focus:outline-none place-self-start"
            >
                Previous
            </button>
            <div v-else></div>
            <p class="text-center">
                Page
                <input
                    class="appearance-none w-16 m-2 rounded border appearance-none bg-white border-gray-400 text-gray-700 pl-2 pr-1 py-2 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    type="number"
                    name="quantity"
                    min="1"
                    :max="pageNum"
                    v-model="page"
                />
                of
                {{ pageNum }}
            </p>
            <button
                @click="page++"
                v-if="totalNum > numPerPage && page < pageNum"
                class="ripple-light sm:w-48 w-32 hover:bg-gray-400 py-2 px-4 rounded m-2 focus:outline-none place-self-end"
            >
                Next
            </button>
            <div v-else></div>
        </div>
    </div>
</template>

<script>
import loader from '../components/loader'

export default {
    components: { loader },
    data() {
        return {
            current: [],
            cluster: [],
            headers: [],
            projectId: this.$route.params.projectId,
            page: 1,
            numPerPage: 50,
            totalNum: 0,
            filename: '',
            loading: true,
            pageNum: 1,
            isGold: 0,
        }
    },
    created() {
        if (this.$route.params.type == 'gold') {
            this.isGold = 1
        } else {
            this.isGold = 0
        }
        this.update()
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.$router.push({
                    name: 'projectView',
                    params: { projectId: this.$route.params.projectId },
                })
            }
        },
        update() {
            this.loading = true
            var url =
                '?action=getData&id=' +
                this.projectId +
                '&howMany=' +
                this.numPerPage +
                '&page=' +
                this.page +
                '&isGold=' +
                this.isGold
            if (isNaN(this.projectId)) {
                this.$router.replace({ name: 'Home' })
            } else {
                this.API.get(url)
                    .then((res) => {
                        console.log(res.data)
                        this.current = res.data.data
                        this.cluster = res.data.cluster_indexes
                        this.headers = res.data.fields
                        this.totalNum = res.data.num
                        this.pageNum = Math.ceil(this.totalNum / this.numPerPage)
                        this.filename = res.data.filename
                        this.loading = false
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            }
        },
    },
    watch: {
        page: function () {
            this.pageNum = Math.ceil(this.totalNum / this.numPerPage)
            this.current = []
            this.cluster = []
            this.update()
        },
        numPerPage: function () {
            this.pageNum = Math.ceil(this.totalNum / this.numPerPage)
            this.page = 1
            this.current = []
            this.cluster = []
            this.update()
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>

<style></style>
