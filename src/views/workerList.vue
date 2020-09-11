<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <h1 class="text-2xl mb-4 text-orange-400">Lista lavoratori</h1>
        <tabellaWorker />
    </div>
</template>

<script>
import tabellaWorker from '../components/tabellaWorker.vue'
export default {
    name: 'workerList',
    components: {
        tabellaWorker,
    },
}
</script>

<style>
</style>