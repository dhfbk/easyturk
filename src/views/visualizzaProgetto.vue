<template>
  <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
    <transition name="fade" mode="out-in">
      <modalEliminazione v-if="modal.elim" @deleteModal="toggleModal('delete')" @deleted="deleted" :id="id" />
    </transition>
    <transition name="fade" mode="out-in">
      <modalUpload
        v-if="modal.std"
        :type="'std'"
        :id="id"
        @uploadModal="toggleModal('std')"
        @uploaded="uploaded"
        @snackbar="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalUpload
        v-if="modal.gld"
        :type="'gld'"
        :id="id"
        @uploadModal="toggleModal('gld')"
        @uploaded="uploaded"
        @snackbar="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalEditBehavior
        v-if="modal.behavior"
        :project="project"
        @close="toggleModal('behavior')"
        @snackbar="uploaded"
        @snackbarErr="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalHIT
        v-if="modal.HIT"
        :id="id"
        :baseDataStatus="project.numData"
        :goldDataStatus="project.numGold"
        :params="project.params"
        @hitModal="toggleModal('HIT')"
        @hitCreated="uploaded"
        @snackbar="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalRevert
        v-if="modal.revert"
        :id="id"
        :toStatus="project.status - 1"
        @revertModal="toggleModal('revert')"
        @reverted="uploaded"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalLayout
        v-if="modal.layout"
        :project="project"
        @layoutSet="uploaded"
        @layoutModal="toggleModal('layout')"
        @snackbar="uploaded"
        @snackbarErr="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalInstructions v-if="modal.instructions" :status="project.status" @modal="toggleModal('instructions')" />
    </transition>
    <transition name="fade" mode="out-in">
      <modalLaunch
        v-if="modal.launch"
        :id="id"
        :hitsSubmitted="hitsSubmitted"
        :hitsTotal="hitsTotal"
        @launchModal="toggleModal('launch')"
        @launched="uploaded"
        :priceData="priceData"
        :qualifications="qualifications"
        @snackbar="snack"
      />
    </transition>
    <transition name="fade" mode="out-in">
      <modalCsvEliminazione
        :isGold="isGold"
        :id="id"
        @close="uploaded"
        v-if="modal.csvElim"
        @deleteModal="toggleModal"
        :goldUploaded="goldUploaded"
      />
    </transition>
    <div class="flex justify-between flex-wrap items-center">
      <button
        @click="$router.push({ name: 'Home' })"
        :content="'Back'"
        v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
        class="rounded ripple bg-transparent hover:bg-gray-300 p-2"
      >
        <svg class="inline" style="width: 24px; height: 24px" viewBox="0 0 24 24">
          <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
        </svg>
        <span class="sr-only">Back to project list</span>
      </button>
      <p v-if="!loading" class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">
        {{ project.name }}
      </p>
      <div class="xs2:w-auto w-full flex relative justify-end content-center items-center float-right gap-x-1">
        <button
          v-if="project.status == 3 && !loading"
          @click="toggleModal('launch')"
          :content="'Launch HITs'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          :class="{ 'cursor-not-allowed': hitsSubmitted == hitsTotal }"
          class="md:block ripple transition-colors ease-out duration-100 bg-primary hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M18,14H20V17H23V19H20V22H18V19H15V17H18V14M12,3C16.42,3 20,4.79 20,7C20,9.21 16.42,11 12,11C7.58,11 4,9.21 4,7C4,4.79 7.58,3 12,3M4,9C4,11.21 7.58,13 12,13C16.42,13 20,11.21 20,9V9L20,12.08L19,12C16.41,12 14.2,13.64 13.36,15.94L12,16C7.58,16 4,14.21 4,12V9M4,14C4,16.21 7.58,18 12,18H13C13,19.05 13.27,20.04 13.75,20.9L12,21C7.58,21 4,19.21 4,17V14Z"
            />
          </svg>
          <span class="sr-only">Launch HITs</span>
        </button>
        <button
          v-if="project.status == 2 && !loading"
          @click="toggleModal('launch')"
          :content="'Publish project'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple bg-primary transition-colors duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M13.13 22.19L11.5 18.36C13.07 17.78 14.54 17 15.9 16.09L13.13 22.19M5.64 12.5L1.81 10.87L7.91 8.1C7 9.46 6.22 10.93 5.64 12.5M21.61 2.39C21.61 2.39 16.66 .269 11 5.93C8.81 8.12 7.5 10.53 6.65 12.64C6.37 13.39 6.56 14.21 7.11 14.77L9.24 16.89C9.79 17.45 10.61 17.63 11.36 17.35C13.5 16.53 15.88 15.19 18.07 13C23.73 7.34 21.61 2.39 21.61 2.39M14.54 9.46C13.76 8.68 13.76 7.41 14.54 6.63S16.59 5.85 17.37 6.63C18.14 7.41 18.15 8.68 17.37 9.46C16.59 10.24 15.32 10.24 14.54 9.46M8.88 16.53L7.47 15.12L8.88 16.53M6.24 22L9.88 18.36C9.54 18.27 9.21 18.12 8.91 17.91L4.83 22H6.24M2 22H3.41L8.18 17.24L6.76 15.83L2 20.59V22M2 19.17L6.09 15.09C5.88 14.79 5.73 14.47 5.64 14.12L2 17.76V19.17Z"
            />
          </svg>
          <span class="sr-only">Publish project</span>
        </button>
        <button
          v-if="project.status == 1 && !loading"
          @click="toggleModal('layout')"
          :content="'Set layout'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple bg-primary transition-colors duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M12.89,3L14.85,3.4L11.11,21L9.15,20.6L12.89,3M19.59,12L16,8.41V5.58L22.42,12L16,18.41V15.58L19.59,12M1.58,12L8,5.58V8.41L4.41,12L8,15.58V18.41L1.58,12Z"
            />
          </svg>
          <span class="sr-only">Set layout</span>
        </button>
        <button
          v-if="project.status == 0 && !loading"
          :class="{ 'cursor-not-allowed': project.numData == 0 }"
          @click="toggleModal('hit')"
          :content="'Create HITs'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple bg-primary transition-colors duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M10,2H14A2,2 0 0,1 16,4V6H20A2,2 0 0,1 22,8V13.53C20.94,12.58 19.54,12 18,12A6,6 0 0,0 12,18C12,19.09 12.29,20.12 12.8,21H4C2.89,21 2,20.1 2,19V8C2,6.89 2.89,6 4,6H8V4C8,2.89 8.89,2 10,2M14,6V4H10V6H14Z"
            />
          </svg>
          <span class="sr-only">Create HITs</span>
        </button>
        <button
          v-if="
            (project.status >= 1 && project.status != 3 && !loading) ||
            (project.status == 3 && $store.state.isSandbox == true && !loading)
          "
          @click="toggleModal('revert')"
          :content="'Revert HIT settings'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple transition-colors duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded border-2 border-solid border-primary hover:text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"
            />
          </svg>
          <span class="sr-only">Revert HIT settings</span>
        </button>
        <button
          v-if="project.status != 3 && !loading"
          @click="$router.push({ name: 'edit', params: { projectId: id } })"
          :content="'Edit'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple transition-colors duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded border-2 border-solid border-primary hover:text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path
              d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
            />
          </svg>
          <span class="sr-only">Edit</span>
        </button>
        <button
          v-if="project.status != 3 && !loading"
          @click="toggleModal('delete')"
          :content="'Delete'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          type="submit"
          class="md:block ripple transition-colors duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded border-2 border-solid border-primary hover:text-white"
        >
          <svg style="width: 24px" class="fill-current" viewBox="0 0 24 24">
            <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
          </svg>
          <span class="sr-only">Delete</span>
        </button>
        <div class="relative">
          <button
            @click="dropdownOpen = !dropdownOpen"
            v-click-outside="hide"
            class="md:hidden ripple hover:bg-primary flex flex-row items-center py-2 px-2 bg-transparent rounded transition-colors duration-100 ease-out border-2 border-solid border-primary hover:text-white"
          >
            <svg
              class="transition-transform duration-100 ease-out fill-current"
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
            <div v-show="dropdownOpen" class="absolute bottom-1 right-0 mt-2 w-56 bg-white rounded-md shadow-xl z-20">
              <a
                v-if="project.status >= 2 && hitsSubmitted == 0"
                @click="toggleModal('launch')"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm text-gray-800 transition-colors duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                >Launch project</a
              >
              <a
                v-else
                @click="toggleModal('launch')"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm text-gray-800 transition-colors duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                >Launch other HITs</a
              >
              <a
                @click="toggleModal('hit')"
                v-if="project.status == 0"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm capitalize text-gray-800 transition-colors duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                >Set HITs</a
              >
              <a
                @click="toggleModal('layout')"
                v-if="project.status == 1"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm text-gray-800 transition-colors duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                >Set layout</a
              >
              <a
                v-if="project.status >= 1 && project.status != 3"
                @click="toggleModal('revert')"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm text-gray-800 transition-colors duration-100 ease-out hover:bg-primary hover:text-gray-100"
                >Revert HIT settings</a
              >
              <a
                v-if="project.status != 3"
                class="cursor-pointer block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition-colors duration-100 ease-out hover:bg-primary hover:text-gray-100"
                @click="toggleModal('delete')"
                >Delete</a
              >
              <router-link
                :to="{
                  name: 'edit',
                  params: { projectId: id },
                }"
                :class="project.status >= 0 && project.status != 3 ? '' : 'rounded-b-md'"
                class="block md:hidden cursor-pointer px-4 py-2 text-sm capitalize text-gray-800 transition-colors duration-100 ease-out hover:bg-primary hover:text-gray-100"
                >Edit</router-link
              >
            </div>
          </transition>
        </div>
      </div>
    </div>
    <loader :type="'progressVisualizza'" v-if="loading" />
    <div class="col-span-2 mt-2 bg-white rounded shadow-md p-4 mb-4" v-else-if="!loading && project.status == 3">
      <span class="font-bold">HITs progress:</span>
      <progressBar :progressData="progressData" />
      <div class="flex lg:flex-row content-center flex-col width-full mx-auto lg:w-11/12 mt-2">
        <div class="w-full flex flex-col customFlex xs2:flex-row justify-around lg:mr-1">
          <span class="flex flex-row mb-2 w-full xs2:w-auto xs2:mr-4">
            <div class="w-8 h-4 rounded-md bg-green-500 my-1 mr-2"></div>
            <span>Submitted ({{ progressData.hits_submitted }})</span>
          </span>
          <span class="flex flex-row mb-2 w-full xs2:w-auto xs2:mr-4">
            <div class="w-8 h-4 rounded-md bg-blue-500 my-1 mr-2"></div>
            <span>To submit ({{ progressData.hits_inserted - progressData.hits_submitted }})</span>
          </span>
          <span class="flex flex-row mb-2 w-full xs2:w-auto">
            <div class="w-8 h-4 rounded-md bg-gray-400 my-1 mr-2"></div>
            <span>Not published ({{ progressData.hits_total - progressData.hits_inserted }})</span></span
          >
        </div>
        <span class="w-full lg:ml-1">
          <div>
            <span class="font-bold">Assignments approved: </span>
            <span> {{ approved }}</span>
          </div>
          <hr />
          <div>
            <span class="font-bold">Assignments rejected: </span><span> {{ rejected }}</span>
          </div>
        </span>
      </div>
      <button
        @click="
          $router.push({
            name: 'HITlist',
            params: { projectId: id },
          })
        "
        class="ripple px-2 py-1 mt-2 bg-gray-200 hover:bg-gray-300 rounded transition-colors duration-100 ease-out float-right"
      >
        View details
      </button>
      <div class="clear-both"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2" :class="!loading && project.status == 3 ? '' : 'mt-2'">
      <div class="mr-0 xs2:mr-1">
        <div class="w-full flex flex-col justify-center" v-if="loading">
          <loader :type="'cardInfoVisualizza'" v-for="n in 4" :key="n" />
        </div>
        <div v-else>
          <cardInfo :projectData="project" :mode="'general'" />
          <cardInfo :projectData="project" :mode="'projectTable'" v-if="project.status == 3" />
          <cardInfo :projectData="project" :mode="'csv'" @modal="toggleModal" />
          <cardInfo :projectData="project" :mode="'layout'" />
          <cardInfo :projectData="project" :mode="'behavior'" v-if="project.status > 1" @modal="toggleModal" />
        </div>
      </div>
      <div class="ml-0 xs2:ml-1">
        <div class="w-full flex flex-col justify-center" v-if="loading">
          <loader :type="'cardInfoVisualizza'" v-for="n in 4" :key="n" />
        </div>
        <div v-else>
          <cardInfo :projectData="project" :mode="'status'" @modal="toggleModal" />
          <cardInfo v-if="project.status > 0" :projectData="project" :mode="'hits'" />
          <cardInfo :projectData="project" :mode="'workersTable'" v-if="project.status == 3" />
          <cardInfo :projectData="project" :mode="'payment'" />
          <cardInfo :projectData="project" :mode="'qualifications'" />
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
import { directive } from 'vue3-click-away'
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
//import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import modalUpload from '../components/modalUpload.vue'
import modalHIT from '../components/modalHIT.vue'
import modalRevert from '../components/modalRevert.vue'
import modalLayout from '../components/modalLayout.vue'
import modalLaunch from '../components/modalLaunch.vue'
import modalEditBehavior from '../components/modalEditBehavior.vue'
import loader from '../components/loader.vue'
import modalInstructions from '../components/modalInstructions.vue'
import modalCsvEliminazione from '../components/modalCsvEliminazione.vue'
import progressBar from '../components/progressBar'

import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'visualizzaProgetto',
  components: {
    modalEliminazione,
    modalEditBehavior,
    cardInfo,
    //cardAnalytics,
    modalUpload,
    modalHIT,
    modalRevert,
    modalLayout,
    modalInstructions,
    modalCsvEliminazione,
    modalLaunch,
    loader,
    progressBar,
  },
  directives: {
    ClickOutside: directive,
  },
  data() {
    return {
      id: '',
      dropdownOpen: false,
      modal: {
        elim: false,
        std: false,
        gld: false,
        revert: false,
        HIT: false,
        layout: false,
        behavior: false,
        instructions: false,
        launch: false,
        csvElim: false,
      },
      loading: true,
      project: [],
      hitsSubmitted: 0,
      hitsTotal: 0,
      priceData: {},
      qualifications: {},
      progressData: {},
      layoutData: undefined,
      approved: 0,
      rejected: 0,
      isGold: null,
      goldUploaded: false,
    }
  },
  created() {
    this.getDatiPrj()
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  methods: {
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.$router.push({ name: 'Home' })
      }
    },
    getDatiPrj() {
      this.id = parseInt(this.$route.params.projectId)
      if (isNaN(this.id)) {
        this.$router.replace({ name: 'Home' })
        this.$emit('snackbar', "Error. Project doesn't exist.")
      } else {
        this.API()
          .get('?action=getProjectInfo&id=' + this.id)
          .then((res) => {
            if (res.data.result == 'ERR') {
              res.data.error.includes('User')
                ? this.$emit('snackbar', 'Error. ' + res.data.error + '. Refresh to log in.')
                : this.$emit('snackbar', 'Error. ' + res.data.error)
            } else {
              this.id = this.id + ''
              this.project = res.data.values
              this.hitsSubmitted = res.data.hits_submitted
              this.hitsTotal = res.data.hits_total
              this.project.hits_submitted = res.data.hits_submitted
              this.project.workersInfo = res.data.workersInfo

              this.project.hits_total = res.data.hits_total
              this.project.numGold = res.data.numGold
              this.project.numData = res.data.numData
              this.qualifications.master = res.data.values.master
              this.project.hits_inserted = res.data.hits_inserted
              this.project.summary = res.data.summary
              this.goldUploaded = res.data.numGold > 0
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
                  this.rejected + parseInt(this.project.summary[i].assignments_rejected) * this.project.summary[i].count
                this.approved =
                  this.approved + parseInt(this.project.summary[i].assignments_approved) * this.project.summary[i].count
              }
              if (res.data.summary.length > 0) {
                if (res.data.summary[0].max_assignments != this.project.workers) {
                  this.totalProjected =
                    (this.project.hits_total - this.project.hits_inserted) * this.project.workers +
                    ' - ' +
                    (this.project.hits_total - this.project.hits_inserted) *
                      parseInt(res.data.summary[0].max_assignments)
                } else {
                  this.totalProjected = (this.project.hits_total - this.project.hits_inserted) * this.project.workers
                }
              } else {
                this.totalProjected = (this.project.hits_total - this.project.hits_inserted) * this.project.workers
              }
              if (this.project.status > 1) {
                this.layoutData = res.data.values.hit_details
              }
              this.loading = false
            }
          })
          .catch((err) => {
            console.error(err)
          })
      }
    },
    open(mode) {
      this.$router.push({
        name: mode,
        params: { projectId: this.projectid },
      })
    },
    toggleModal(mode) {
      if (mode == 'gld') {
        if (this.project.numData == 0) {
          this.$emit('snackbar', 'Warning. To upload the gold CSV, you first have to uplaod the standard.')
          return
        }
      } else if (mode == 'hit') {
        if (this.project.numData == 0) {
          this.$emit('snackbar', 'Warning. To create the HITs, you first have to upload CSV data.')
          return
        }
      } else if (mode == 'deleteGld') {
        this.isGold = 1
        mode = 'csvElim'
      } else if (mode == 'deleteStd') {
        this.isGold = 0
        mode = 'csvElim'
      } else if (mode == 'launch') {
        if (this.hitsSubmitted == this.hitsTotal) {
          this.$emit('snackbar', 'All HITs have already been submitted', '')
          return
        }
      }
      this.modal[mode] = !this.modal[mode]
      this.modal[mode]
        ? window.removeEventListener('keydown', this.keyboardEvent)
        : window.addEventListener('keydown', this.keyboardEvent)
      this.hide()
    },
    //for when an action gets completed (success or error)
    deleted(msg) {
      this.$router.push('/')
      this.$emit('snackbar', msg)
    },
    //for when an action gets completed (success or error)
    snack(msg) {
      this.$emit('snackbar', msg)
    },
    uploaded(msg) {
      if (Array.isArray(msg)) {
        if (msg[2] == 'Upload completed') {
          if (msg[0] == 'std') {
            this.toggleModal('std')
          } else {
            this.toggleModal('gld')
          }
          this.snack(msg[2])
        } else {
          this.snack(msg[2])
        }
      } else {
        this.snack(msg)
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
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>

<style scoped>
.fade-enter-active {
  transition: opacity 0.25s ease-out !important;
}
.fade-leave-active {
  transition: opacity 0.2s ease-out !important;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
@media (max-width: 476px) {
  .customFlex {
    flex-direction: column;
  }
}
</style>
