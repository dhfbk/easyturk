<template>
    <div class="container mx-auto">
        <!-- <span v-for="i in colors" :key="i"><div class="w-4 h-4" :style="{ 'background-color': '#' + i }"></div></span> -->

        <span v-for="(x, y) in tmpData.length" :key="y">
            <matrixPart :num="tmpData[x - 1]" :color="colors[y]" :total="totalHITs" :hoverColor="hoverArr" />

            <!-- <span
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
                    </span>-->
        </span>
        <span class="flex flex-row flex-wrap mt-2 justify-around">
            <span v-for="(i, e) in tmpData.length" :key="e">
                <span class="flex flex-row items-center mt-2 md:mt-1">
                    <div
                        class="w-5 h-5 rounded-full my-1 mr-2 hover:shadow-focus"
                        :style="{
                            'background-color': 'rgb(' + colors[e][0] + ',' + colors[e][1] + ',' + colors[e][2] + ')',
                        }"
                        @mouseenter="hoverGroup(colors[e])"
                        @mouseleave="hoverGroup([])"
                    ></div>
                    <span
                        >Approved: {{ tmpData[i - 1].assignments_approved }}<br />Rejected:
                        {{ tmpData[i - 1].assignments_rejected }}<br />Pending: {{ tmpData[i - 1].assignments_pending
                        }}<br />Available: {{ tmpData[i - 1].assignments_available }}
                    </span>
                </span>
            </span>
        </span>
    </div>
</template>

<script>
//aggiungere i mouseover e sistemare per il tooltip
import matrixPart from '../components/matrixPart'
export default {
    name: 'dotMatrixHIT',
    // props: {
    //     tmpData: Array,
    // },
    components: {
        matrixPart,
    },
    data() {
        return {
            texts: [],
            totalHITs: 0,
            hoverArr: [],

            colors: [],

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
        }
    },
    methods: {
        // col(num) {
        //     if (this.tmpData[num].assignments_approved > this.tmpData[num].assignments_rejected) {
        //         return this.colorsPositive[this.countPos]
        //     } else {
        //         return this.colorsNegative[this.countNeg]
        //     }
        // },
        hoverGroup(arr) {
            this.hoverArr = arr
        },
        interpolateColors(color1, color2, step) {
            var stepFactor = 1 / (step - 1)

            color1 = color1.match(/\d+/g).map(Number)
            color2 = color2.match(/\d+/g).map(Number)

            for (var i = 0; i < step; i++) {
                this.colors.push(this.interpolateColor(color1, color2, stepFactor * i))
            }
        },
        interpolateColor(color1, color2, factor) {
            if (arguments.length < 3) {
                factor = 0.5
            }
            var result = color1.slice()
            for (var i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]))
            }
            return result
        },
    },
    created() {
        var half = Math.floor(this.tmpData.length / 2)
        for (let i = 0; i < this.tmpData.length; i++) {
            this.totalHITs += this.tmpData[i].count
        }
        this.interpolateColors('rgb(14, 173, 105)', 'rgb(0,255,0)', half)
        this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', this.tmpData.length - half)
        // var tmp = ''
        // for (var i = 0; i < this.tmpData.length; i++) {
        //     this.total += this.tmpData[i].count
        //     tmp =
        //         'Approved: ' +
        //         parseInt(this.tmpData[i].assignments_approved) +
        //         '/' +
        //         parseInt(this.tmpData[i].max_assignments) +
        //         '<br/>' +
        //         'Available: ' +
        //         parseInt(this.tmpData[i].assignments_available) +
        //         '/' +
        //         parseInt(this.tmpData[i].max_assignments) +
        //         '<br/>' +
        //         'Completed: ' +
        //         parseInt(this.tmpData[i].assignments_completed) +
        //         '/' +
        //         parseInt(this.tmpData[i].max_assignments) +
        //         '<br/>' +
        //         'Pending: ' +
        //         parseInt(this.tmpData[i].assignments_pending) +
        //         '/' +
        //         parseInt(this.tmpData[i].max_assignments) +
        //         '<br/>' +
        //         'Rejected: ' +
        //         parseInt(this.tmpData[i].assignments_rejected) +
        //         '/' +
        //         parseInt(this.tmpData[i].max_assignments) +
        //         '<br/>'
        //     this.texts.push(tmp)
        // }
        // this.first = this.tmpData[0].count
        // this.second = this.tmpData[1].count
        // this.third = this.tmpData[2].count
        // this.fourth = this.tmpData[3].count
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
