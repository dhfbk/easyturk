<template>
    <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <modalEliminazione v-if="modalElim" @deleteModal="toggleModal('delete')" @deleted="deleted" :id="id" />
        <modalUpload v-if="modalStd" :type="'std'" :id="id" @uploadModal="toggleModal('std')" @uploaded="uploaded" />
        <modalUpload v-if="modalGld" :type="'gld'" :id="id" @uploadModal="toggleModal('gld')" @uploaded="uploaded" />
        <modalHIT
            v-if="modalHIT"
            :id="id"
            :baseDataStatus="project.numData"
            :goldDataStatus="project.numGold"
            :params="project.params"
            @hitModal="toggleModal('hit')"
            @hitCreated="uploaded"
        />
        <modalRevert
            v-if="modalRevert"
            :id="id"
            :toStatus="project.status - 1"
            @revertModal="toggleModal('revert')"
            @reverted="uploaded"
        />
        <modalLayout
            v-if="modalLayout"
            :project="project"
            @layoutSet="uploaded"
            @layoutModal="toggleModal('layout')"
            @snackbar="uploaded"
        />
        <modalInstructions v-if="modalInstructions" :status="project.status" @modal="toggleModal('instructions')" />
        <modalLaunch
            v-if="modalLaunch"
            :id="id"
            :hitsSubmitted="hitsSubmitted"
            :hitsTotal="hitsTotal"
            @launchModal="toggleModal('launch')"
            @launched="uploaded"
            :priceData="priceData"
            :qualifications="qualifications"
        />
        <div v-if="!loading">
            <h1 class="text-2xl mb-4 text-primary w-auto inline-block">{{ project.name }}</h1>
            <div class="w-auto flex relative justify-between content-center items-center float-right">
                <span class="tooltip hidden md:block relative md:mr-2" v-if="project.status == 3">
                    <button
                        @click="toggleModal('launch')"
                        type="submit"
                        :class="{ 'cursor-not-allowed': hitsSubmitted == hitsTotal }"
                        class="ripple transition ease-out duration-100 bg-primary hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M18,14H20V17H23V19H20V22H18V19H15V17H18V14M12,3C16.42,3 20,4.79 20,7C20,9.21 16.42,11 12,11C7.58,11 4,9.21 4,7C4,4.79 7.58,3 12,3M4,9C4,11.21 7.58,13 12,13C16.42,13 20,11.21 20,9V9L20,12.08L19,12C16.41,12 14.2,13.64 13.36,15.94L12,16C7.58,16 4,14.21 4,12V9M4,14C4,16.21 7.58,18 12,18H13C13,19.05 13.27,20.04 13.75,20.9L12,21C7.58,21 4,19.21 4,17V14Z"
                            />
                        </svg>
                        <span class="sr-only">Launch HITs</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Launch HITs
                    </span>
                    <!--
                    <span
                        v-else
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >
                        Launch other HITs
                    </span>
                    -->
                </span>
                <span class="tooltip hidden md:block relative md:mr-2" v-if="project.status == 2">
                    <button
                        @click="toggleModal('launch')"
                        type="submit"
                        class="ripple bg-primary transition duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M13.13 22.19L11.5 18.36C13.07 17.78 14.54 17 15.9 16.09L13.13 22.19M5.64 12.5L1.81 10.87L7.91 8.1C7 9.46 6.22 10.93 5.64 12.5M21.61 2.39C21.61 2.39 16.66 .269 11 5.93C8.81 8.12 7.5 10.53 6.65 12.64C6.37 13.39 6.56 14.21 7.11 14.77L9.24 16.89C9.79 17.45 10.61 17.63 11.36 17.35C13.5 16.53 15.88 15.19 18.07 13C23.73 7.34 21.61 2.39 21.61 2.39M14.54 9.46C13.76 8.68 13.76 7.41 14.54 6.63S16.59 5.85 17.37 6.63C18.14 7.41 18.15 8.68 17.37 9.46C16.59 10.24 15.32 10.24 14.54 9.46M8.88 16.53L7.47 15.12L8.88 16.53M6.24 22L9.88 18.36C9.54 18.27 9.21 18.12 8.91 17.91L4.83 22H6.24M2 22H3.41L8.18 17.24L6.76 15.83L2 20.59V22M2 19.17L6.09 15.09C5.88 14.79 5.73 14.47 5.64 14.12L2 17.76V19.17Z"
                            />
                        </svg>
                        <span class="sr-only">Publish project</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Publish project
                    </span>
                    <!--
                    <span
                        v-else
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                    >
                        Launch other HITs
                    </span>
                    -->
                </span>
                <span class="tooltip hidden md:block relative md:mr-2" v-if="project.status == 1">
                    <button
                        @click="toggleModal('layout')"
                        type="submit"
                        class="ripple bg-primary transition duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12.89,3L14.85,3.4L11.11,21L9.15,20.6L12.89,3M19.59,12L16,8.41V5.58L22.42,12L16,18.41V15.58L19.59,12M1.58,12L8,5.58V8.41L4.41,12L8,15.58V18.41L1.58,12Z"
                            />
                        </svg>
                        <span class="sr-only">Set layout</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Set layout
                    </span>
                </span>
                <span class="tooltip hidden md:block relative md:mr-2" v-if="project.status == 0">
                    <button
                        :class="{ 'cursor-not-allowed': project.numData == 0 }"
                        @click="toggleModal('hit')"
                        type="submit"
                        class="ripple bg-primary transition duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M10,2H14A2,2 0 0,1 16,4V6H20A2,2 0 0,1 22,8V13.53C20.94,12.58 19.54,12 18,12A6,6 0 0,0 12,18C12,19.09 12.29,20.12 12.8,21H4C2.89,21 2,20.1 2,19V8C2,6.89 2.89,6 4,6H8V4C8,2.89 8.89,2 10,2M14,6V4H10V6H14Z"
                            />
                        </svg>
                        <span class="sr-only">Create HITs</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Create HITs
                    </span>
                </span>
                <span
                    class="tooltip hidden md:block relative md:mr-2"
                    v-if="
                        (project.status >= 1 && project.status != 3) ||
                            (project.status == 3 && $store.state.isSandbox == true)
                    "
                >
                    <button
                        @click="toggleModal('revert')"
                        type="submit"
                        class="ripple transition duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"
                            />
                        </svg>
                        <span class="sr-only">Revert HIT settings</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Revert HIT settings
                    </span>
                </span>
                <span class="tooltip hidden md:block relative md:mr-2" v-if="project.status != 3">
                    <button
                        @click="$router.push({ name: 'edit', params: { projectId: id } })"
                        type="submit"
                        class="ripple transition duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                            />
                        </svg>
                        <span class="sr-only">Edit</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Edit
                    </span>
                </span>
                <span class="tooltip hidden md:block relative" v-if="project.status != 3">
                    <button
                        @click="toggleModal('delete')"
                        type="submit"
                        class="ripple transition duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                            />
                        </svg>
                        <span class="sr-only">Delete</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Delete
                    </span>
                </span>
                <span class="tooltip hidden md:block relative" v-if="project.status == 3">
                    <button
                        @click="open('results')"
                        type="submit"
                        class="ripple transition duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                            />
                        </svg>
                        <span class="sr-only">Results</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Results
                    </span>
                    <!-- <span class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"
                        ></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
                    </span> -->
                </span>
                <span v-click-outside="hide">
                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        class="md:hidden ripple hover:bg-primary flex flex-row items-center py-2 px-2 bg-transparent rounded-md transition duration-100 ease-out border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg
                            class="transition duration-100 ease-out fill-current"
                            :class="{ 'transform  rotate-180': dropdownOpen }"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="black"
                            width="24px"
                            height="24px"
                        >
                            <path
                                d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z"
                            />
                        </svg>
                        <span class="sr-only">Open dropdown</span>
                    </button>
                    <transition name="slide-toggle">
                        <div
                            v-show="dropdownOpen"
                            class=" absolute bottom-1 right-0 mt-1 w-56 bg-white rounded-md shadow-xl z-20"
                        >
                            <a
                                v-if="project.status >= 2 && hitsSubmitted == 0"
                                @click="toggleModal('launch')"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Launch project</a
                            >
                            <a
                                v-else
                                @click="toggleModal('launch')"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Launch other HITs</a
                            >
                            <a
                                @click="toggleModal('hit')"
                                v-if="project.status == 0"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Set HITs</a
                            >
                            <a
                                @click="toggleModal('layout')"
                                v-if="project.status == 1"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Set layout</a
                            >
                            <a
                                v-if="project.status >= 1 && project.status != 3"
                                @click="toggleModal('revert')"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary hover:text-gray-100"
                                >Revert HIT settings</a
                            >
                            <router-link
                                v-if="project.status == 3"
                                to="results"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary hover:text-gray-100"
                                >Results</router-link
                            >
                            <a
                                v-if="project.status != 3"
                                class="cursor-pointer block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary hover:text-gray-100"
                                @click="toggleModal('delete')"
                                >Delete</a
                            >
                            <router-link
                                :to="{
                                    name: 'edit',
                                    params: { projectId: id },
                                }"
                                :class="project.status >= 0 && project.status != 3 ? '' : 'rounded-b-md'"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary hover:text-gray-100"
                                >Edit</router-link
                            >
                            <!-- <a
                                v-if="project.status == 0"
                                @click="toggleModal('std')"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-out hover:bg-primary md:rounded-t-md hover:text-gray-100"
                                >Base CSV upload</a
                            >
                            <a
                                v-if="project.status == 0"
                                @click="toggleModal('gld')"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-out hover:bg-primary rounded-b-md hover:text-gray-100"
                                >Gold CSV upload</a
                            > -->
                            <!-- <a
                                v-if="project.status == 0 && projectbaseCsvStatus == 1"
                                @click="uploadModal(['hit', ''])"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-out hover:bg-primary rounded-b-md hover:text-gray-100"
                                >Load HITs</a
                            >-->
                        </div>
                    </transition>
                </span>
            </div>
        </div>
        <div class="col-span-2 mt-2">
            <loader :type="'progressVisualizza'" v-if="loading" />
            <div class="bg-white rounded shadow-md p-4 mb-4" v-else-if="!loading && project.status == 3">
                <span class="font-bold">HITs progress:</span>
                <progressBar :progressData="progressData" />
                <div class="flex md:flex-row flex-col-reverse">
                    <div class="md:w-1/2">
                        <table class="w-full rounded mr-2 mt-2">
                            <thead>
                                <tr class="text-center bg-primary border border-grey uppercase ">
                                    <th class="text-sm text-white">
                                        TOTAL HITs
                                    </th>
                                    <th class="text-sm text-white">APPROVED</th>
                                    <th class="text-sm text-white">REJECTED</th>
                                    <th class="text-sm text-white">COMPLETED</th>
                                    <th class="text-sm text-white">AVAILABLE</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="(i, k) in project.summary" :key="k" class="border hover:bg-gray-100">
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium">
                                        {{ i.count }}
                                    </td>
                                    <td class="py-1 border-r text-sm text-gray-700 font-medium">
                                        {{ i.assignments_approved }}
                                    </td>
                                    <td class="py-1 border-r text-sm text-gray-700 font-medium">
                                        {{ i.assignments_rejected }}
                                    </td>
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium">
                                        {{ i.assignments_completed }}
                                    </td>
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium">
                                        {{ i.assignments_available }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium">
                                        {{ project.hits_total }}
                                    </td>
                                    <td class="py-1 border-r text-sm text-gray-700 font-medium">
                                        {{ approved }}
                                    </td>
                                    <td class="py-1 border-r text-sm text-gray-700 font-medium">
                                        {{ rejected }}
                                    </td>
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium"></td>
                                    <td class="border-r py-1 text-sm text-gray-700 font-medium"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-row flex-wrap w-full md:w-1/2 mt-2 self-start ml-2">
                        <div class="w-full flex flex-row justify-around">
                            <span class="flex flex-row mb-2 w-full xs2:w-auto xs2:mr-4">
                                <div class="w-8 h-4 rounded-md bg-green-500 my-1 mr-2"></div>
                                <span>Submitted</span>
                            </span>
                            <span class="flex flex-row mb-2 w-full xs2:w-auto xs2:mr-4">
                                <div class="w-8 h-4 rounded-md bg-blue-500 my-1 mr-2"></div>
                                <span>Published</span>
                            </span>
                            <span class="flex flex-row mb-2 w-full xs2:w-auto xs2:mr-4">
                                <div class="w-8 h-4 rounded-md bg-gray-400 my-1 mr-2"></div>
                                <span>Not published</span></span
                            >
                        </div>
                        <span class="w-full">
                            <div>
                                <span class="font-bold">Remaining assignments projected:</span>
                                <span> {{ totalProjected }}</span>
                            </div>
                            <hr />
                            <div>
                                <span class="font-bold">Assignments approved:</span>
                                <span> {{ approved }}</span>
                            </div>
                            <hr />
                            <div>
                                <span class="font-bold">Assignments rejected:</span><span> {{ rejected }}</span>
                            </div>
                            <hr />
                        </span>
                    </div>
                </div>
                <button
                    @click="
                        $router.push({
                            name: 'HITlist',
                            params: { projectId: id },
                        })
                    "
                    class="ripple px-2 py-1 bg-gray-200 hover:bg-gray-400 rounded focus:outline-none transition duration-100 ease-out float-right"
                >
                    View details
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="grid grid-cols-1 xs2:grid-cols-2">
            <div class="mr-0 xs2:mr-1">
                <div class="w-full flex flex-col justify-center" v-if="loading">
                    <loader :type="'cardInfoVisualizza'" v-for="n in 4" :key="n" />
                </div>
                <div v-else>
                    <cardInfo :projectData="project" :mode="'general'" />
                    <cardInfo :projectData="project" :mode="'layout'" />
                    <cardInfo :projectData="project" :mode="'status'" @modal="toggleModal" />
                    <cardInfo v-if="project.status > 0" :projectData="project" :mode="'hits'" />
                </div>
            </div>
            <div class="ml-0 xs2:ml-1">
                <div class="w-full flex flex-col justify-center" v-if="loading">
                    <loader :type="'cardInfoVisualizza'" v-for="n in 3" :key="n" />
                </div>
                <div v-else>
                    <cardInfo :projectData="project" :mode="'payment'" />
                    <cardInfo :projectData="project" :mode="'qualifications'" />
                    <cardInfo :projectData="project" :mode="'csv'" @modal="toggleModal" />
                    <!--
                        <cardAnalytics
                        :dati="datiCardAnalytics.cardHIT"
                    />
                    <cardAnalytics :dati="datiCardAnalytics.cardAggregate" />-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
//import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import modalUpload from '../components/modalUpload.vue'
import modalHIT from '../components/modalHIT.vue'
import modalRevert from '../components/modalRevert.vue'
import modalLayout from '../components/modalLayout.vue'
import modalLaunch from '../components/modalLaunch.vue'
import loader from '../components/loader.vue'
import axios from 'axios'
import modalInstructions from '../components/modalInstructions.vue'
import progressBar from '../components/progressBar'

export default {
    name: 'visualizzaProgetto',
    directives: {
        ClickOutside,
    },
    components: {
        modalEliminazione,
        cardInfo,
        //cardAnalytics,
        modalUpload,
        modalHIT,
        modalRevert,
        modalLayout,
        modalInstructions,
        modalLaunch,
        loader,
        progressBar,
    },
    data() {
        return {
            id: '',
            dropdownOpen: false,
            modalElim: false,
            modalStd: false,
            modalGld: false,
            modalRevert: false,
            modalHIT: false,
            modalLayout: false,
            modalInstructions: false,
            modalLaunch: false,
            loading: true,
            project: [],
            hitsSubmitted: 0,
            hitsTotal: 0,
            priceData: {},
            qualifications: {},
            progressData: {},
            approved: 0,
            rejected: 0,
            totalProjected: 0,
        }
    },
    created() {
        this.getDatiPrj()

        console.log(this.priceData)
    },
    mounted() {
        this.popupItem = this.$el
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.$router.go(-1)
            }
        },
        getDatiPrj() {
            this.id = this.$route.params.projectId
            axios
                .get(this.APIURL + '?action=getProjectInfo&id=' + this.id)
                .then(res => {
                    this.project = res.data.values
                    this.hitsSubmitted = res.data.hits_submitted
                    this.hitsTotal = res.data.hits_total
                    this.project.hits_submitted = res.data.hits_submitted
                    this.project.hits_total = res.data.hits_total
                    this.project.numGold = res.data.numGold
                    this.project.numData = res.data.numData
                    this.qualifications.master = res.data.values.master
                    this.project.hits_inserted = res.data.hits_inserted
                    this.project.summary = res.data.summary
                    console.log(res.data)
                    if ((this.project.status == 2 || this.project.status == 3) && this.hitsTotal > this.hitsSubmitted) {
                        this.priceData.reward = parseFloat(this.project.reward)
                        this.priceData.assignment = parseInt(this.project.workers)
                    }
                    if (this.project.status == 3) {
                        this.progressData.hits_inserted = res.data.hits_inserted
                        this.progressData.hits_total = res.data.hits_total
                        this.progressData.hits_submitted = res.data.hits_submitted
                    }
                    for (let i = 0; i < this.project.summary.length; i++) {
                        this.rejected =
                            this.rejected +
                            parseInt(this.project.summary[i].assignments_rejected) * this.project.summary[i].count
                        this.approved =
                            this.approved +
                            parseInt(this.project.summary[i].assignments_approved) * this.project.summary[i].count
                    }
                    if (res.data.summary.length > 0) {
                        if (res.data.summary[0].max_assignments != this.project.workers) {
                            this.totalProjected =
                                (this.project.hits_total - this.project.hits_inserted) * this.project.workers +
                                ' - ' +
                                (this.project.hits_total - this.project.hits_inserted) *
                                    parseInt(res.data.summary[0].max_assignments)
                        } else {
                            this.totalProjected =
                                (this.project.hits_total - this.project.hits_inserted) * this.project.workers
                        }
                    } else {
                        this.totalProjected =
                            (this.project.hits_total - this.project.hits_inserted) * this.project.workers
                    }

                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        //metodo per aprire il link dei vari button
        open(mode) {
            this.$router.push({
                name: mode,
                params: { projectId: this.projectid },
            })
        },

        toggleModal(mode) {
            if (mode == 'delete') {
                this.modalElim = !this.modalElim
            } else if (mode == 'std') {
                this.modalStd = !this.modalStd
            } else if (mode == 'gld') {
                if (this.project.numData == 0) {
                    this.$emit('snackbar', 'Warning. To upload the gold CSV, you first have to uplaod the standard.')
                } else {
                    this.modalGld = !this.modalGld
                }
            } else if (mode == 'revert') {
                this.modalRevert = !this.modalRevert
            } else if (mode == 'hit') {
                if (this.project.numData == 0) {
                    this.$emit('snackbar', 'Warning. To create the HITs, you first have to upload CSV data.')
                } else {
                    this.modalHIT = !this.modalHIT
                }
            } else if (mode == 'layout') {
                this.modalLayout = !this.modalLayout
            } else if (mode == 'instructions') {
                this.modalInstructions = !this.modalInstructions
            } else if (mode == 'launch') {
                if (this.hitsSubmitted == this.hitsTotal) {
                    this.$emit('snackbar', 'All HITs have already been submitted', '')
                } else {
                    this.modalLaunch = !this.modalLaunch
                }
            }
            this.hide()
        },
        //for when an action gets completed (success or error)
        deleted(msg) {
            this.$router.push('/')
            this.$emit('snackbar', msg)
        },
        //for when an action gets completed (success or error)
        uploaded(msg) {
            if (Array.isArray(msg)) {
                if (msg[2] == 'Upload completed') {
                    if (msg[0] == 'std') {
                        this.toggleModal('std')
                    } else {
                        this.toggleModal('gld')
                    }
                    this.$emit('snackbar', msg[2])
                } else {
                    this.$emit('snackbar', msg[2])
                }
            } else {
                this.$emit('snackbar', msg)
            }
            this.loading = true
            this.getDatiPrj()
        },

        //nasconde il dropdown
        hide() {
            if (this.dropdownOpen) {
                this.dropdownOpen = false
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
/* .tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 2px 6px;
    z-index: 100;
    left: 0;
    transition: opacity 0.3s ease-out;
    opacity: 0;
    transition-delay: 0.15s;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 85%;
} */
</style>
