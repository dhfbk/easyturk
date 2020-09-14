<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div v-for="(n, i) in length" :key="n">
            <span class="font-bold tracking-tight">{{titoli[i]}}:&nbsp;</span>
            <span>{{dati[i]}}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cardInfo',
    props: {
        dati: Array,
        titoli: Array,
    },
    data() {
        return {
            length: 0,
        }
    },
    created() {
        this.length = this.dati.length
    },
}
</script>

<style>
</style>