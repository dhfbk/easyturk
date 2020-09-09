<template>
    <div class="home sm:mx-8 md:mx-16">
        <!-- <img alt="Vue logo" src="../assets/logo.png" /> -->
        <!-- <HelloWorld msg="Welcome to Your Vue.js App" /> -->
        <div
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center "
        >
            <div v-for="i in projects" :key="i.id" class="col-span-5">
                <projectListItem :projectData="i" />
            </div>
            <div class="rounded shadow-lg m-4 p-2">
                <div class="font-bold text-xl  m-1">Saldo account</div>
                <p class="text-gray-700 text-base m-1">
                    54$
                </p>
            </div>
            <div class="rounded  shadow-lg m-4 p-2">
                <div class="font-bold text-xl  m-1">Reviewable results</div>
                <p class="text-gray-700 text-base m-1">
                    93
                </p>
            </div>

            <div class="rounded  shadow-lg m-4 p-4 col-span-3 row-span-3	">
                <div class="font-bold text-lg  m-1">Workers</div>
                <!-- component -->
                <div class="container">
                    <!-- <h1 class="mb-8">
                        Scrollable Table Fixed Height
                    </h1> -->

                    <table class="text-left w-full">
                        <thead class="bg-orange-500 text-white flex w-full ">
                            <tr class="flex w-full">
                                <th class="p-4 w-1/3">workerId</th>
                                <th class="p-4 w-1/3">approvalRate</th>
                                <th class="p-4 w-1/3">status</th>
                            </tr>
                        </thead>
                        <!-- Remove the nasty inline CSS fixed height on production and replace it with a CSS class — this is just for demonstration purposes! -->
                        <tbody
                            class="bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full h-64 border"
                        >
                            <tr class="flex w-full  ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                            <tr class="flex w-full ">
                                <td class="p-4 w-1/3">workerId</td>
                                <td class="p-4 w-1/3">approvalRate</td>
                                <td class="p-4 w-1/3">status</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mt-4"
                >
                    View all
                </button>
            </div>
        </div>
    </div>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from "@/components/HelloWorld.vue";
import projectListItem from '../components/projectListItem'

export default {
    name: 'Home',
    components: {
        projectListItem,
    },
    data() {
        return {
            projects: [
                {
                    id: 'URJYRLU1PP',
                    title: 'Offensive Tweet Recognition',
                    date: '27/08/2018',
                    hasGold: true,
                    hasData: true,
                },
                {
                    id: 'G7Q5EOBNDG',
                    title: 'Image Tagging',
                    date: '19/06/2018',
                    hasGold: false,
                    hasData: true,
                },
                {
                    id: 'YQOKWUAP6A',
                    title: 'Soccer Events Recognition',
                    date: '04/12/2019',
                    hasGold: false,
                    hasData: true,
                },
                {
                    id: '8GIS78FLI2',
                    title: 'Survey on Sports',
                    date: '30/05/2020',
                    hasGold: false,
                    hasData: false,
                },
            ],
        }
    },
}
</script>
