<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <h1 class="text-2xl mb-4 text-primary">Workers list</h1>
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

<style></style>
