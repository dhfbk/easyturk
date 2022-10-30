<template>
  <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
    <div v-if="loading">
      <loader :type="'hitList'" />
    </div>
    <div v-else>
      <div class="flex justify-between flex-wrap items-center mb-2">
        <button
          @click="
            $router.push({
              name: 'projectView',
              params: { projectId: $route.params.projectId },
            })
          "
          :content="'Back'"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          class="rounded ripple bg-transparent hover:bg-gray-300 p-2"
        >
          <svg class="inline" style="width: 24px; height: 24px" viewBox="0 0 24 24">
            <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
          </svg>
          <span class="sr-only">Back to project</span>
        </button>
        <span class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">
          HITs results (project ID: {{ $route.params.projectId }})
        </span>
        <div class="w-auto flex relative justify-end content-center items-center">
          <p class="text-md my-auto mr-1">View:</p>
          <div class="relative">
            <select
              class="appearance-none h-full rounded border block bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:bg-white focus:border-blue-500"
              name="sortBy"
              id="sortBy"
              @change="view(viewType)"
              v-model="viewType"
            >
              <option value="dots">Dot Matrix</option>
              <option value="table">Table</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      <textBarLarge
        :v="v$"
        :status="1"
        :componentText="{
          id: 'searchText',
          label: 'Search HIT',
          useLabel: false,
          statusClass: 2,
          searchComponent: true,
          bg: 'white',
        }"
        :searchError="searchError"
        @search="searchHIT"
      />
      <tableHIT :sortedData="sortedData" v-if="viewType == 'table'" />
      <dotMatrixHIT :sortedData="sortedData" :key="sortedData.length" v-else />
    </div>
  </div>
</template>
<script>
import textBarLarge from '../components/textBarLarge'
import tableHIT from '../components/tableHIT.vue'
import dotMatrixHIT from '../components/dotMatrixHIT.vue'
import loader from '../components/loader.vue'
import globalMixin from '../globalMixin.js'
import { maxLength } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
  mixins: [globalMixin],
  name: 'HITList',
  components: {
    tableHIT,
    dotMatrixHIT,
    loader,
    textBarLarge,
  },
  setup: () => ({ v$: useVuelidate() }),
  data() {
    return {
      id: '',
      loading: true,
      progressData: [],
      viewType: 'dots',
      sortedData: [],
      sortedDataBackup: [],
      searchText: '',
      searchError: false,
    }
  },
  validations() {
    return {
      searchText: {
        maxLength: maxLength(this.$store.state.defaults.max_length_title),
      },
    }
  },
  created() {
    this.getData()
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  methods: {
    searchHIT() {
      this.v$.$touch()
      if (!this.v$.$invalid) {
        this.loading = true
        let found = false
        if (this.searchText.length == 0 && this.sortedData.length == 1) {
          this.sortedData = this.sortedDataBackup
        } else if (this.searchText.length > 0) {
          for (let i = 0; i < this.sortedData.length; i++) {
            for (let j = 0; j < this.sortedData[i].hits.length; j++) {
              if (this.sortedData[i].hits[j] == this.searchText) {
                this.sortedData = [this.sortedData[i]]
                this.sortedData.hits = ['3YGYP13654XDWV2DQNA7ION0ET1RN3']
                found = true
                this.searchError = false
                break
              }
            }
            if (found) {
              break
            }
          }
          if (!found) {
            this.searchError = true
          }
        }
        this.loading = false
      }
    },
    sortReceivedData() {
      //data sorting: first completed hits, then incomplete hits (each category ordered by most approved hits  to least approved)

      var arrComp = []
      var arrNotComp = []
      var arrNotTou = []

      for (let i = 0; i < this.progressData.length; i++) {
        if (this.progressData[i].assignments_completed == 0) {
          arrNotTou.push(this.progressData[i])
        } else if (this.progressData[i].assignments_available > 0) {
          arrNotComp.push(this.progressData[i])
        } else {
          arrComp.push(this.progressData[i])
        }
      }

      arrComp = arrComp.sort(function (a, b) {
        return (
          a.assignments_rejected - b.assignments_rejected ||
          b.assignments_approved - a.assignments_approved ||
          b.assignments_available - a.assignments_available
        )
      })

      arrNotComp = arrNotComp.sort(function (a, b) {
        return (
          a.assignments_rejected - b.assignments_rejected ||
          b.assignments_approved - a.assignments_approved ||
          b.assignments_available - a.assignments_available
        )
      })

      this.sortedData = arrComp.concat(arrNotComp).concat(arrNotTou)
      this.sortedDataBackup = this.sortedData
    },
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.$router.push({
          name: 'projectView',
          params: { projectId: this.$route.params.projectId },
        })
      }
    },
    getData() {
      this.id = parseInt(this.$route.params.projectId)
      if (isNaN(this.id)) {
        this.$router.replace({ name: 'Home' })
      } else {
        this.API()
          .get('?action=getProjectInfo&id=' + this.id)
          .then((res) => {
            if (res.data.result == 'ERR') {
              res.data.error.includes('User')
                ? this.$emit('snackbar', 'Error. ' + res.data.error + '. Refresh to log in.')
                : this.$emit('snackbar', 'Error. ' + res.data.error)
            } else {
              this.progressData = res.data.summary
              //count to set defualt data view
              //this code can be used to set the default view to dots only if the results number is bigger than a threshold
              /*
                            var cout = 0
                            for (let i = 0; i < this.progressData.length; i++) {
                                cout += this.progressData[i].count
                                if (cout >= 20) {
                                    this.viewType = 'dots'
                                    break
                                }
                            }
                            */

              //

              this.sortReceivedData()
              this.loading = false
            }
          })
          .catch((err) => {
            console.error(err)
          })
      }
    },
    view(type) {
      if (type != this.viewType) {
        this.viewType = type
      }
    },
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>

<style scoped></style>
