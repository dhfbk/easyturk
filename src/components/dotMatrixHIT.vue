<template>
    <div class="container mx-auto">
        <span v-for="(x, y) in sortedData.length" :key="y">
            <matrixPart :num="sortedData[x - 1]" :color="colors[y]" :total="totalHITs" :hoverColor="hoverArr" />
            <br v-if="y == totalComp - 1 || y == totalComp + totalAvai - 1" />
        </span>
        <div class="text-lg mb-1 font-bold">Legend</div>
        <span class="flex flex-row flex-wrap justify-around border rounded p-2 bg-white">
            <span v-if="totalComp > 0" :class="classCreator(totalAvai, totalNotTou)">
                <div class="text-center">Completed HITs</div>
                <span class="flex flex-row mt-2 justify-around ">
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
            <span class="w-px bg-gray-300" v-if="totalComp > 0"></span>
            <span v-if="totalAvai > 0" :class="classCreator(totalComp, totalNotTou)">
                <div class="text-center">Incomplete HITs</div>
                <span class="flex flex-row mt-2 justify-around ">
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
            <span class="w-px bg-gray-300" v-if="!(totalComp == 0 && totalAvai == 0)"></span>
            <span v-if="totalNotTou > 0" :class="classCreator(totalComp, totalAvai)">
                <div class="text-center">New HITs</div>
                <span class="flex flex-row mt-2 justify-around">
                    <span class="tooltip relative w-full">
                        <span
                            class="h-5 w-full inline-block transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                            :style="{
                                background: colors[totalComp + totalAvai],
                            }"
                            @mouseenter="hoverGroup(colors[totalComp + totalAvai])"
                            @mouseleave="hoverGroup('')"
                        ></span>
                        <span
                            class="tooltip-text regular bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light topHalf"
                            >{{ sortedData[totalComp + totalAvai].count }} HITs<br />Approved:
                            {{ sortedData[totalComp + totalAvai].assignments_approved }}<br />Rejected:
                            {{ sortedData[totalComp + totalAvai].assignments_rejected }}<br />Available:
                            {{ sortedData[totalComp + totalAvai].assignments_available }}
                        </span>
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
            totalNotTou: 0,
        }
    },
    methods: {
        classCreator(x, y) {
            if (x == 0 && y == 0) {
                return 'w-full'
            } else if ((x > 0 && y == 0) || (x == 0 && y > 0)) {
                return 'w-full md:w-12/25'
            } else {
                return 'w-full md:w-8/25'
            }
        },
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
        var notTou = 0
        var grad = []

        for (let x = 0; x < this.sortedData.length; x++) {
            if (this.sortedData[x].assignments_completed == 0) {
                notTou++
            } else if (this.sortedData[x].assignments_available > 0) {
                this.sortedData[x].assignments_rejected == 0 ? avaPos++ : avaNeg++
            } else if (this.sortedData[x].assignments_rejected == 0) {
                pos++
            } else {
                neg++
            }
        }

        this.totalComp = pos + neg
        this.totalAvai = avaPos + avaNeg
        this.totalNotTou = notTou

        var avaPosArr = this.interpolateColors('rgb(14, 173, 105)', 'rgb(4, 240, 106)', avaPos)
        var avaNegArr = this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', avaNeg)
        var notCompCol = 'rgb(163, 206, 241)'

        grad = avaPosArr.concat(avaNegArr)

        for (let i = 0; i < grad.length; i++) {
            grad[i] = 'linear-gradient(rgb(163, 206, 241), ' + grad[i] + ')'
        }

        this.colors = this.interpolateColors('rgb(14, 173, 105)', 'rgb(49, 203, 0)', pos)
            .concat(this.interpolateColors('rgb(255, 209, 0)', 'rgb(255,90,0)', neg))
            .concat(grad)

        this.colors.push(notCompCol)
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
    visibility: hidden;
    text-align: center;
    padding: 4px 8px;
    z-index: 100;
    left: 0;
    transition: opacity 0.15s ease-out !important;
    opacity: 0;
    transition-delay: 0.15s;
}

.tooltip-text {
    font-weight: 500;
    left: 50% !important;
    -webkit-transform: translateX(-50%) !important;
    transform: translateX(-50%) !important;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    transition: opacity 0.2s ease-out !important;
    opacity: 85%;
}
@media (max-width: 460px) {
    .tooltip-text {
        visibility: hidden !important;
    }
}
</style>
