<template>
    <div class="home sm:mx-8 md:mx-16">
        <!-- <img alt="Vue logo" src="../assets/logo.png" /> -->
        <!-- <HelloWorld msg="Welcome to Your Vue.js App" /> -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center ">
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
                <div class="font-bold text-xl  m-1">Reviewable results</div>
                <!-- component -->
                <div class="container">
                    <!-- <h1 class="mb-8">
                        Scrollable Table Fixed Height
                    </h1> -->

                    <table class="text-left w-full">
                        <thead class="bg-orange-400	 flex  w-full ">
                            <tr class="flex w-full mb-4">
                                <th class="p-4 w-1/4">One</th>
                                <th class="p-4 w-1/4">Two</th>
                                <th class="p-4 w-1/4">Three</th>
                                <th class="p-4 w-1/4">Four</th>
                            </tr>
                        </thead>
                        <!-- Remove the nasty inline CSS fixed height on production and replace it with a CSS class — this is just for demonstration purposes! -->
                        <tbody
                            class="bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full h-64 border"
                        >
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
                            </tr>
                            <tr class="flex w-full mb-4">
                                <td class="p-4 w-1/4">Dogs</td>
                                <td class="p-4 w-1/4">Cats</td>
                                <td class="p-4 w-1/4">Birds</td>
                                <td class="p-4 w-1/4">Fish</td>
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

export default {
    name: 'Home',
    components: {
        //HelloWorld
    },
}
</script>
