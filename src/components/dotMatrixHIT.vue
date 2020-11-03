<template>
    <div class="container mx-auto">
        <div
            class="rounded shadow-md transition duration-200 ease-out mb-4 py-2 px-4 flex items-center flex-wrap bg-white relative hover:shadow-lg cursor-pointer grow"
        >
            <div class="w-full flex justify-between content-center items-center">
                <span>Legend</span>
                <button
                    class="bg-white ripple-light py-2 px-2 m-1 rounded focus:outline-none hover:bg-gray-400 transition duration-100"
                    @click.stop="isOpen = !isOpen"
                >
                    <svg
                        class="transition duration-300 ease-out"
                        :class="{ 'transform  rotate-180': isOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="black"
                        width="24px"
                        height="24px"
                    >
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
                    </svg>
                </button>
            </div>

            <transition name="slide-toggle">
                <span class="flex flex-row flex-wrap mt-2 justify-around" v-if="isOpen">
                    <span v-for="(i, e) in tmpData.length" :key="e">
                        <span class="flex flex-col items-center m-2">
                            <div
                                class="w-5 h-5 rounded-full my-1 mr-2 hover:shadow-focus"
                                :style="{
                                    background: colors[e],
                                }"
                                @mouseenter="hoverGroup(colors[e])"
                                @mouseleave="hoverGroup('')"
                            ></div>
                            <span
                                >Approved: {{ tmpData[i - 1].assignments_approved }}<br />Rejected:
                                {{ tmpData[i - 1].assignments_rejected }}<br />Available:
                                {{ tmpData[i - 1].assignments_available }}
                            </span>
                        </span>
                    </span>
                </span>
            </transition>
        </div>

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
            hoverArr: '',
            isOpen: false,
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
                {
                    assignments_approved: '1',
                    assignments_available: '2',
                    assignments_completed: '2',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 12,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '1',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 10,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '1',
                    assignments_completed: '2',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 29,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '2',
                    assignments_completed: '1',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 21,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '1',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '2',
                    count: 3,
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

            var arr = []

            for (var i = 0; i < step; i++) {
                arr.push(this.interpolateColor(color1, color2, stepFactor * i))
            }
            return arr
        },
        interpolateColor(color1, color2, factor) {
            if (arguments.length < 3) {
                factor = 0.5
            }
            var result = color1.slice()
            for (var i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]))
            }
            return 'rgb(' + result[0] + ',' + result[1] + ',' + result[2] + ')'
        },
    },
    created() {
        for (let i = 0; i < this.tmpData.length; i++) {
            this.totalHITs += this.tmpData[i].count
        }

        //data sorting: first completed hits, then incomplete hits (each category ordered by most approved hits  to least approved)

        var arrComp = []
        var arrNotComp = []

        for (let i = 0; i < this.tmpData.length; i++) {
            this.tmpData[i].assignments_available > 0 ? arrNotComp.push(this.tmpData[i]) : arrComp.push(this.tmpData[i])
        }

        arrComp = arrComp.sort(function(a, b) {
            return a.assignments_rejected / a.assignments_approved - b.assignments_rejected / b.assignments_approved
        })

        arrNotComp = arrNotComp.sort(function(a, b) {
            return a.assignments_rejected / a.assignments_approved - b.assignments_rejected / b.assignments_approved
        })

        this.tmpData = arrComp.concat(arrNotComp)

        //

        //colors and gradients creation

        var pos = 0
        var neg = 0
        var avaPos = 0
        var avaNeg = 0
        var grad = []

        for (let x = 0; x < this.tmpData.length; x++) {
            if (this.tmpData[x].assignments_available > 0) {
                this.tmpData[x].assignments_approved > this.tmpData[x].assignments_rejected ? avaPos++ : avaNeg++
            } else if (this.tmpData[x].assignments_approved > this.tmpData[x].assignments_rejected) {
                pos++
            } else {
                neg++
            }
        }

        var avaPosArr = this.interpolateColors('rgb(14, 173, 105)', 'rgb(0,255,0)', avaPos)
        var avaNegArr = this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', avaNeg)

        grad = avaPosArr.concat(avaNegArr)

        for (let i = 0; i < grad.length; i++) {
            grad[i] = 'linear-gradient(rgb(163, 206, 241), ' + grad[i] + ')'
        }

        console.log(grad)

        this.colors = this.interpolateColors('rgb(14, 173, 105)', 'rgb(0,255,0)', pos)
            .concat(this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', neg))
            .concat(grad)

        //
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
