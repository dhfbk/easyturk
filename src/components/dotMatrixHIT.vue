<template>
    <div class="container mx-auto">
        <span class="flex flex-row flex-wrap">
            <span class="flex flex-wrap">
                <span
                    v-for="f in first"
                    :key="f + 'first'"
                    class="bg-green-500 h-5 w-5 m-1 rounded-full tooltip relative"
                >
                    <span
                        class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                        v-html="texts[0]"
                    >
                    </span>
                </span>
                <span
                    v-for="s in second"
                    :key="s + 'second'"
                    class="bg-blue-500 h-5 w-5 m-1 rounded-full tooltip relative"
                >
                    <span
                        class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                        v-html="texts[1]"
                    >
                    </span>
                </span>
                <span
                    v-for="t in third"
                    :key="t + 'third'"
                    class="bg-orange-500 h-5 w-5 m-1 rounded-full tooltip relative"
                >
                    <span
                        class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                        v-html="texts[2]"
                    >
                    </span>
                </span>
                <span
                    v-for="f1 in fourth"
                    :key="f1 + 'fourth'"
                    class="bg-purple-500 h-5 w-5 m-1 rounded-full tooltip relative"
                >
                    <span
                        class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                        v-html="texts[3]"
                    >
                    </span>
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
            texts: [],
            tmpData: [
                {
                    assignments_approved: '3',
                    assignments_available: '0',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 252,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '3',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 45,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '2',
                    count: 78,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '3',
                    count: 26,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '0',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '4',
                    count: 5,
                    max_assignments: '4',
                },
            ],
            first: 0,
            second: 0,
            third: 0,
            fourth: 0,
        }
    },
    created() {
        var tmp = ''
        for (var i = 0; i < this.tmpData.length; i++) {
            this.total += this.tmpData[i].count
            tmp =
                'Approved: ' +
                parseInt(this.tmpData[i].assignments_approved) +
                '/' +
                parseInt(this.tmpData[i].max_assignments) +
                '<br/>' +
                'Available: ' +
                parseInt(this.tmpData[i].assignments_available) +
                '/' +
                parseInt(this.tmpData[i].max_assignments) +
                '<br/>' +
                'Completed: ' +
                parseInt(this.tmpData[i].assignments_completed) +
                '/' +
                parseInt(this.tmpData[i].max_assignments) +
                '<br/>' +
                'Pending: ' +
                parseInt(this.tmpData[i].assignments_pending) +
                '/' +
                parseInt(this.tmpData[i].max_assignments) +
                '<br/>' +
                'Rejected: ' +
                parseInt(this.tmpData[i].assignments_rejected) +
                '/' +
                parseInt(this.tmpData[i].max_assignments) +
                '<br/>'
            this.texts.push(tmp)
        }
        this.first = this.tmpData[0].count
        this.second = this.tmpData[1].count
        this.third = this.tmpData[2].count
        this.fourth = this.tmpData[3].count
    },
}
</script>

<style scoped>
.topHalf {
    margin: 0 !important;
    top: 110%;
    -webkit-transform: translateY(50%) !important;
    transform: translateY(50%) !important;
    -webkit-transform: translateX(-50%) !important;
}
@media (max-width: 460px) {
    .tooltip-text {
        visibility: hidden !important;
    }
}
</style>