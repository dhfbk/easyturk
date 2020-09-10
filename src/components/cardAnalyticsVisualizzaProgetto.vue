<template>
    <div class="bg-white rounded-md shadow-lg p-4 mb-4">
        <div class="text-lg mb-4">
            <span class="font-bold">Totale HIT:&nbsp;</span>
            <span>{{dati.totale}}</span>
        </div>
        <div class="flex justify-evenly flex-wrap">
            <vue-ellipse-progress
                v-for="progress in dati.ellipse_progress"
                :key="progress.caption"
                :progress="progress.progress"
                :legend-value="progress.legend_value"
                :color="progress.color"
                :size="pieSize"
                :half="progress.half"
                :angle="progress.angle"
                class="mx-2 mb-2"
            >
                <span slot="legend-value">/{{dati.totale}}</span>
                <p slot="legend-caption">{{progress.caption}}</p>
            </vue-ellipse-progress>
        </div>
        <div class="w-full flex justify-start -mt-4" v-if="dati.type == 'HIT'">
            <button
                type="submit"
                class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 focus:outline-none"
            >Vai alle HIT</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cardAnalytics',
    props: {
        dati: Object,
    },
    data() {
        return {
            pieSize: 150,
        }
    },
}
</script>

<style>
</style>