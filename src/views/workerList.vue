<template>
    <div class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
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
