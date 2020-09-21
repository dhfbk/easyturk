<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div class="text-lg mb-4">
            <div class="flex content-center flex-col sm:flex-row" v-if="dati.type == 'HIT'">
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path
                        d="M19 3H14.82C14.4 1.84 13.3 1 12 1S9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M7 8H9V12H8V9H7V8M10 17V18H7V17.08L9 15H7V14H9.25C9.66 14 10 14.34 10 14.75C10 14.95 9.92 15.14 9.79 15.27L8.12 17H10M11 4C11 3.45 11.45 3 12 3S13 3.45 13 4 12.55 5 12 5 11 4.55 11 4M17 17H12V15H17V17M17 11H12V9H17V11Z"
                    />
                </svg>
                <div>
                    <span class="font-bold tracking-tight">Totale HIT:&nbsp;</span>
                    <span>{{ dati.totale }}</span>
                </div>
            </div>
            <div class="flex content-center flex-col sm:flex-row" v-if="dati.type == 'aggregate'">
                <svg style="width:24px" viewBox="0 0 24 24">
                    <path
                        d="M16,11.78L20.24,4.45L21.97,5.45L16.74,14.5L10.23,10.75L5.46,19H22V21H2V3H4V17.54L9.5,8L16,11.78Z"
                    />
                </svg>
                <div>
                    <span class="font-bold tracking-tight">Hit completate:&nbsp;</span>
                    <span>{{ dati.totale }}</span>
                </div>
            </div>
            <div class="flex content-center flex-col sm:flex-row" v-if="dati.type == 'assignment'">
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path
                        d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"
                    />
                </svg>
                <div>
                    <span class="font-bold tracking-tight">Totale assignment:&nbsp;</span>
                    <span>{{ dati.totale }}</span>
                </div>
            </div>
        </div>
        <div class="flex justify-evenly flex-wrap">
            <vue-ellipse-progress
                v-for="progress in dati.ellipse_progress"
                :key="progress.caption"
                :progress="progress.progress"
                :legend-value="progress.legend_value"
                :color="'#0091FF'"
                :size="pieSize"
                :half="progress.half"
                :angle="progress.angle"
                class="mx-2 mb-2"
            >
                <span slot="legend-value">/{{ dati.totale }}</span>
                <p slot="legend-caption">{{ progress.caption }}</p>
            </vue-ellipse-progress>
        </div>
        <div class="w-full flex justify-start -mt-4" v-if="dati.type == 'HIT'">
            <router-link
                :to="{
                    name: 'HITlist',
                    params: {
                        projectId: dati.idPrj,
                    },
                }"
            >
                <button
                    type="submit"
                    class="py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid text-gray-800 border-primary hover:bg-primary hover:text-gray-100 focus:outline-none"
                >
                    Vai alle HIT
                </button>
            </router-link>
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

<style scoped>
path {
    fill: rgba(45, 55, 72, 1);
}
</style>
