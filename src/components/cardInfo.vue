<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div v-if="mode == 'csv'">
            <span class="font-bold ">CSV status</span>
            <div class="mb-1">
                <span v-if="projectData.numData > 0">
                    <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
                        <span class="mr-2">
                            <div>
                                <span>Data:&nbsp;</span>
                                <span class="text-green-600">uploaded</span>
                            </div>
                            <div>
                                <span>Rows:&nbsp;</span>
                                <span>{{ projectData.numData }}</span>
                            </div>
                        </span>
                        <span class="flex flex-row buttonsData justify-center">
                            <button
                                class="px-2 py-1 ripple bg-gray-200 transition duration-150 hover:bg-gray-300 rounded focus:outline-none"
                                @click="
                                    $router.push({
                                        name: 'csv',
                                        params: { projectId: projectData.id, type: 'data' },
                                    })
                                "
                            >
                                View data
                            </button>
                            <span class="tooltip relative ml-2" v-if="projectData.status < 1">
                                <button
                                    @click="$emit('modal', 'std')"
                                    class="tooltip ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 focus:outline-none flex items-center justify-center"
                                >
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path
                                            fill="rgba(26, 32, 44, 1)"
                                            d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z"
                                        />
                                    </svg>
                                </button>
                                <span
                                    v-if="projectData.numData > 0"
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix1"
                                >
                                    Overwrite standard csv
                                </span>

                                <span
                                    v-else
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix2"
                                >
                                    Upload standard csv
                                </span>
                            </span>
                        </span>
                    </div>
                </span>
                <span v-else>
                    <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
                        <span class="mr-2">
                            <span>Data:&nbsp;</span><span class="text-red-500"> not uploaded</span>
                        </span>
                        <span class="flex flex-row buttonsData justify-center">
                            <span class="tooltip relative ml-2" v-if="projectData.status < 1">
                                <button
                                    @click="$emit('modal', 'std')"
                                    class="tooltip ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10  focus:outline-none flex items-center justify-center"
                                >
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path
                                            fill="rgba(26, 32, 44, 1)"
                                            d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z"
                                        />
                                    </svg>
                                </button>
                                <span
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix2"
                                >
                                    Upload standard csv
                                </span>
                            </span>
                        </span>
                    </div>
                </span>
            </div>
            <hr />
            <div class="mt-1">
                <span v-if="projectData.numGold > 0">
                    <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
                        <span class="mr-2">
                            <div>
                                <span>Gold:&nbsp;</span>
                                <span class="text-green-600">uploaded</span>
                            </div>
                            <div>
                                <span>Rows:&nbsp;</span>
                                <span>{{ projectData.numGold }}</span>
                            </div>
                        </span>
                        <span class="flex flex-row buttonsData justify-center">
                            <button
                                class="px-2 py-1 ripple bg-gray-200 transition duration-150 hover:bg-gray-300 rounded focus:outline-none"
                                @click="
                                    $router.push({
                                        name: 'csv',
                                        params: { projectId: projectData.id, type: 'gold' },
                                    })
                                "
                            >
                                View gold
                            </button>
                            <span class="tooltip relative ml-2" v-if="projectData.status < 1">
                                <button
                                    @click="$emit('modal', 'gld')"
                                    :class="{ 'cursor-not-allowed': projectData.numData == 0 }"
                                    class="ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10  focus:outline-none flex items-center justify-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        style="width:24px; height:24px;"
                                    >
                                        <path
                                            fill="rgba(26, 32, 44, 1)"
                                            d="m11.99806,14.99892l-5,0l0,2l5,0m5,-6l-10,0l0,2l7.69,0c-1.62,1.07 -2.69,2.91 -2.69,5c0,1.09 0.29,2.12 0.8,3l-7.8,0c-1.11,0 -2,-0.9 -2,-2l0,-14c0,-1.11 0.89,-2 2,-2l14,0a2,2 0 0 1 2,2l0,7.8c-0.88,-0.51 -1.91,-0.8 -3,-0.8l-1,0.08l0,-1.08m0,-2l0,-2l-10,0l0,2"
                                        />
                                        <g>
                                            <path
                                                fill="rgba(26, 32, 44, 1)"
                                                d="m15.75665,19.4418l1.01849,0c0,0.54999 0.69767,1.01849 1.52774,1.01849c0.83007,0 1.52774,-0.46851 1.52774,-1.01849c0,-0.56017 -0.52962,-0.76387 -1.64996,-1.03377c-1.0796,-0.2699 -2.42402,-0.606 -2.42402,-2.02171c0,-0.91155 0.74859,-1.68561 1.78237,-1.94532l0,-1.11016l1.52774,0l0,1.11016c1.03377,0.25972 1.78237,1.03377 1.78237,1.94532l-1.01849,0c0,-0.54999 -0.69767,-1.01849 -1.52774,-1.01849c-0.83007,0 -1.52774,0.46851 -1.52774,1.01849c0,0.56017 0.52962,0.76387 1.64996,1.03377c1.0796,0.2699 2.42402,0.606 2.42402,2.02171c0,0.91155 -0.74859,1.68561 -1.78237,1.94532l0,1.11016l-1.52774,0l0,-1.11016c-1.03377,-0.25972 -1.78237,-1.03377 -1.78237,-1.94532z"
                                            />
                                        </g>
                                    </svg>
                                </button>
                                <span
                                    v-if="projectData.numGold > 0"
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix3"
                                >
                                    Overwrite gold csv
                                </span>
                                <span
                                    v-else
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix4"
                                >
                                    Upload gold csv
                                </span>
                            </span>
                        </span>
                    </div>
                </span>
                <span v-else>
                    <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
                        <span class="mr-2">
                            <span>Gold:&nbsp;</span><span class="text-red-500"> not uploaded</span>
                        </span>
                        <span class="flex flex-row buttonsData justify-center">
                            <span class="tooltip relative ml-2" v-if="projectData.status < 1">
                                <button
                                    @click="$emit('modal', 'gld')"
                                    :class="{ 'cursor-not-allowed': projectData.numData == 0 }"
                                    class="ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10  focus:outline-none flex items-center justify-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        style="width:24px; height:24px;"
                                    >
                                        <path
                                            fill="rgba(26, 32, 44, 1)"
                                            d="m11.99806,14.99892l-5,0l0,2l5,0m5,-6l-10,0l0,2l7.69,0c-1.62,1.07 -2.69,2.91 -2.69,5c0,1.09 0.29,2.12 0.8,3l-7.8,0c-1.11,0 -2,-0.9 -2,-2l0,-14c0,-1.11 0.89,-2 2,-2l14,0a2,2 0 0 1 2,2l0,7.8c-0.88,-0.51 -1.91,-0.8 -3,-0.8l-1,0.08l0,-1.08m0,-2l0,-2l-10,0l0,2"
                                        />
                                        <g>
                                            <path
                                                fill="rgba(26, 32, 44, 1)"
                                                d="m15.75665,19.4418l1.01849,0c0,0.54999 0.69767,1.01849 1.52774,1.01849c0.83007,0 1.52774,-0.46851 1.52774,-1.01849c0,-0.56017 -0.52962,-0.76387 -1.64996,-1.03377c-1.0796,-0.2699 -2.42402,-0.606 -2.42402,-2.02171c0,-0.91155 0.74859,-1.68561 1.78237,-1.94532l0,-1.11016l1.52774,0l0,1.11016c1.03377,0.25972 1.78237,1.03377 1.78237,1.94532l-1.01849,0c0,-0.54999 -0.69767,-1.01849 -1.52774,-1.01849c-0.83007,0 -1.52774,0.46851 -1.52774,1.01849c0,0.56017 0.52962,0.76387 1.64996,1.03377c1.0796,0.2699 2.42402,0.606 2.42402,2.02171c0,0.91155 -0.74859,1.68561 -1.78237,1.94532l0,1.11016l-1.52774,0l0,-1.11016c-1.03377,-0.25972 -1.78237,-1.03377 -1.78237,-1.94532z"
                                            />
                                        </g>
                                    </svg>
                                </button>
                                <span
                                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1 smallScreenFix4"
                                >
                                    Upload gold csv
                                </span>
                            </span>
                        </span>
                    </div>
                </span>
            </div>
        </div>
        <div v-else-if="mode == 'status'">
            <span class="font-bold ">Status:</span>
            <span class="flex flex-col justify-between buttonsData items-start">
                <span v-if="projectData.status == 0 && projectData.numData > 0">
                    <span class="text-green-500">data uploaded. </span>Ready to
                    <span class="font-bold text-primary"> create HITs.</span>
                </span>
                <span v-else-if="projectData.status == 0 && projectData.numData == 0">
                    <span class="text-green-500">project created. </span
                    ><span class="font-bold text-primary">Upload csv data </span>and<span
                        class="font-bold text-primary"
                    >
                        csv gold</span
                    >
                    to advance.
                </span>
                <span v-if="projectData.status == 1">
                    <span class="text-green-500">HITs created. </span>Ready to
                    <span class="font-bold text-primary"> set the layout.</span>
                </span>
                <span v-if="projectData.status == 2">
                    <span class="text-green-500">HIT layout set. </span>Ready to finally
                    <span class="font-bold text-primary"> publish the project!</span>
                </span>
                <span v-if="projectData.status == 3">
                    <span class="text-green-500">Project has been launched and is now available to the Workers. </span
                    >You can launch more HITs or <span class="font-bold text-primary"> check the results.</span>
                </span>
                <button
                    @click="$emit('modal', 'instructions')"
                    class="ripple px-2 py-1 mt-1 bg-gray-200 hover:bg-gray-300 rounded focus:outline-none transition duration-150 self-end"
                >
                    Need help?
                </button>
            </span>
        </div>
        <div v-for="(n, i) in titles.length" :key="n" v-else class="">
            <span class="font-bold ">{{ titles[i] }}:&nbsp;</span>
            <p class="overflow-ellipsis  ">{{ data[i] }}</p>
            <hr v-if="n != titles.length" />
        </div>
    </div>
</template>

<script>
export default {
    name: 'cardInfo',
    props: {
        projectData: Object,
        mode: String,
    },

    data() {
        return {
            titles: [],
            data: [],
            statusText: [],
        }
    },
    created() {
        switch (this.mode) {
            case 'general':
                this.titles = ['Title', 'Description', 'Keywords', 'Creation date']
                this.data = [
                    this.projectData.title,
                    this.projectData.description,
                    this.projectData.keywords,
                    this.projectData.created_at,
                ]
                break
            case 'layout':
                this.titles = ['Layout ID', 'Number of records per HIT']
                this.data = [this.projectData.layout_id, this.projectData.params]
                break
            case 'payment':
                this.titles = [
                    'Reward per assignment',
                    'Number of assignments per task',
                    'Time allotted per assignment',
                    'Task expires in',
                    'Auto-approve and pay Workers in',
                ]
                this.data = [
                    this.projectData.reward + '$',
                    this.projectData.workers,
                    this.time(parseInt(this.projectData.max_time)),
                    this.time(parseInt(this.projectData.expiry)),
                    this.time(parseInt(this.projectData.auto_approve)),
                ]
                break
            case 'hits':
                this.titles = ['HITs status']
                if (this.projectData.hits_submitted == 0) {
                    this.data = [this.projectData.hits_total + ' HITs created.']
                } else if (
                    this.projectData.hits_submitted > 0 &&
                    this.projectData.hits_submitted != this.projectData.hits_total
                ) {
                    this.data = [
                        this.projectData.hits_submitted +
                            ' out of ' +
                            this.projectData.hits_total +
                            ' HITs submitted. ' +
                            (parseInt(this.projectData.hits_total) - parseInt(this.projectData.hits_submitted)) +
                            ' HITs still available.',
                    ]
                } else if (this.projectData.hits_submitted == this.projectData.hits_total) {
                    this.data = ['All ' + this.projectData.hits_submitted + ' HITs have been submitted!']
                }
                break
            case 'qualifications':
                this.titles = ['Adult content', 'Master Workers only', 'Location of Workers']
                var loc = ''
                for (let i = 0; i < this.projectData.countries.length; i++) {
                    loc += this.projectData.countries[i]
                    if (i != this.projectData.countries.length - 1) {
                        loc += ', '
                    }
                }
                this.data = [
                    this.projectData.adult == 0 ? 'No' : 'Yes',
                    this.projectData.master == 0 ? 'No' : 'Yes',
                    loc,
                ]
        }
    },
    methods: {
        time(num) {
            if (num < 60) {
                num += ' minutes'
            } else if (this.projectData.tempoMax < 1440) {
                num = num / 60 + ' hours'
            } else {
                num = num / 1440 + ' days'
            }
            return num
        },
    },
}
</script>

<style scoped>
/* .tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 2px 6px;
    z-index: 100;
    left: 0;
    transition: opacity 0.3s ease-in-out;
    opacity: 0;
    transition-delay: 0.15s;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 85%;
} */
@media screen and (max-width: 648px) {
    .buttonsData {
        margin: 0 auto;
        width: 100%;
        align-items: center;
    }
}
@media (max-width: 1023px) and (min-width: 649px) {
    .smallScreenFix1{
        margin-left: -60px;
    }
    .smallScreenFix2{
        margin-left: -52px;
    }
    .smallScreenFix3{
        margin-left: -46px;
    }
    .smallScreenFix4{
        margin-left: -38px;
    }
}
</style>
