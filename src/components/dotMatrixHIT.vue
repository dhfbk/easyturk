<template>
    <div class="container mx-auto">
        <!-- <div class="rounded border mb-4 py-2 px-4 flex items-center flex-wrap bg-white relative cursor-pointer grow">
            <div class="w-full flex justify-between content-center items-center">
                <span>Legend</span>
                <button
                    class="bg-white ripple-light py-2 px-2 m-1 rounded focus:outline-none hover:bg-gray-400 transition duration-100 ease-out"
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
                    <span v-for="(i, e) in sortedData.length" :key="e">
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
                                >Approved: {{ sortedData[i - 1].assignments_approved }}<br />Rejected:
                                {{ sortedData[i - 1].assignments_rejected }}<br />Available:
                                {{ sortedData[i - 1].assignments_available }}
                            </span>
                        </span>
                    </span>
                </span>
            </transition>
        </div> -->

        <span v-for="(x, y) in sortedData.length" :key="y">
            <matrixPart :num="sortedData[x - 1]" :color="colors[y]" :total="totalHITs" :hoverColor="hoverArr" />
            <br v-if="y == totalComp - 1" />
        </span>
        <div class="text-lg mb-1 font-bold">Legend</div>
        <span class="flex flex-row flex-wrap justify-around border rounded p-2 bg-white">
            <span class="w-full md:w-12/25">
                <div class="text-center">Completed HITs</div>
                <span class="flex flex-row mt-2 justify-around " v-if="totalComp > 1">
                    <span v-for="(i, e) in totalComp" :key="e" :style="{ width: (1 / totalComp) * 100 + '%' }">
                        <span class="tooltip relative">
                            <span
                                class="h-5 w-full inline-block transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                                :style="{
                                    background: colors[e],
                                }"
                                @mouseenter="hoverGroup(colors[e])"
                                @mouseleave="hoverGroup('')"
                            ></span>
                            <span
                                class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                                >{{ sortedData[i - 1].count }} HITs<br />Approved:
                                {{ sortedData[i - 1].assignments_approved }}<br />Rejected:
                                {{ sortedData[i - 1].assignments_rejected }}<br />Available:
                                {{ sortedData[i - 1].assignments_available }}
                            </span>
                        </span>
                    </span>
                </span>
                <span class="flex justify-between">
                    <span>More approved</span>
                    <span>More rejected</span>
                </span>
            </span>
            <span class="w-px bg-gray-300"></span>
            <span class="w-full md:w-12/25">
                <div class="text-center">Incomplete HITs</div>
                <span class="flex flex-row mt-2 justify-around " v-if="totalAvai > 1">
                    <span v-for="(i, e) in totalAvai" :key="e" :style="{ width: (1 / totalAvai) * 100 + '%' }">
                        <span class="tooltip relative">
                            <span
                                class="h-5 w-full inline-block transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                                :style="{
                                    background: colors[e + totalComp],
                                }"
                                @mouseenter="hoverGroup(colors[e + totalComp])"
                                @mouseleave="hoverGroup('')"
                            ></span>
                            <span
                                class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                                >{{ sortedData[e + totalComp].count }} HITs<br />Approved:
                                {{ sortedData[e + totalComp].assignments_approved }}<br />Rejected:
                                {{ sortedData[e + totalComp].assignments_rejected }}<br />Available:
                                {{ sortedData[e + totalComp].assignments_available }}
                            </span>
                        </span>
                    </span>
                </span>
                <span class="flex justify-between">
                    <span>More approved</span>
                    <span>More rejected</span>
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
    props: {
        sortedData: Array,
    },
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
            totalComp: 0,
            totalAvai: 0,
        }
    },
    methods: {
        hoverGroup(arr) {
            this.hoverArr = arr
        },
        interpolateColors(color1, color2, step) {
            if (step == 1) return [color2]
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
        for (let i = 0; i < this.sortedData.length; i++) {
            this.totalHITs += this.sortedData[i].count
        }

        //colors and gradients creation

        var pos = 0
        var neg = 0
        var avaPos = 0
        var avaNeg = 0
        var grad = []

        for (let x = 0; x < this.sortedData.length; x++) {
            if (this.sortedData[x].assignments_available > 0) {
                this.sortedData[x].assignments_rejected == 0 ? avaPos++ : avaNeg++
            } else if (this.sortedData[x].assignments_rejected == 0) {
                pos++
            } else {
                neg++
            }
        }

        this.totalComp = pos + neg
        this.totalAvai = avaPos + avaNeg

        var avaPosArr = this.interpolateColors('rgb(14, 173, 105)', 'rgb(4, 240, 106)', avaPos)
        var avaNegArr = this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', avaNeg)

        grad = avaPosArr.concat(avaNegArr)

        for (let i = 0; i < grad.length; i++) {
            grad[i] = 'linear-gradient(rgb(163, 206, 241), ' + grad[i] + ')'
        }

        this.colors = this.interpolateColors('rgb(14, 173, 105)', 'rgb(49, 203, 0)', pos)
            .concat(this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', neg))
            .concat(grad)

        console.log(this.colors)

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

.tooltip .tooltip-text {
    visibility      : hidden;
    text-align      : center;
    padding         : 4px 8px;
    z-index         : 100;
    left            : 0;
    transition      : opacity .15s ease-out !important;
    opacity         : 0;
    transition-delay: 0.15s;
}

.tooltip-text {
    font-weight      : 500;
    left             : 50% !important;
    -webkit-transform: translateX(-50%) !important;
    transform        : translateX(-50%) !important;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    transition: opacity .2s ease-out !important;
    opacity   : 85%;
}
@media (max-width: 460px) {
    .tooltip-text {
        visibility: hidden !important;
    }
}
</style>
