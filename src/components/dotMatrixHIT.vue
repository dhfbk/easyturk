<template>
    <div class="container mx-auto">
        <span class="flex flex-row flex-wrap">
            <span class="tooltip relative flex flex-wrap">
                <span
                    v-for="s in submitted"
                    :key="s + '_submitted'"
                    class="bg-green-500 h-5 w-5 m-1 rounded-full"
                ></span>
                <span
                    v-for="p in published"
                    :key="p + '_published'"
                    class="bg-blue-500 h-5 w-5 m-1 rounded-full"
                ></span>
                <span
                    v-for="n in notPublished"
                    :key="n + '_notPublished'"
                    class="bg-gray-500 h-5 w-5 m-1 rounded-full"
                ></span>
                <span
                    class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                >
                    {{ submitted }} submitted
                </span>
            </span>
        </span>
    </div>
</template>

<script>
//aggiungere i mouseover e sistemare per il tooltip
export default {
    name: 'dotMatrixHIT',
    props: {
        progressData: Object,
    },
    data() {
        return {
            colors: ['green', 'blue', 'grey'],
            published: 0,
            submitted: 0,
            total: 100,
            notPublished: 0,
        }
    },
    created() {
        this.published = Math.floor((this.progressData.hits_inserted * this.total) / this.progressData.hits_total)
        this.submitted = Math.floor((this.progressData.hits_submitted * this.total) / this.progressData.hits_total)
        this.notPublished = this.total - this.published - this.submitted
    },
}
</script>

<style scoped>
</style>