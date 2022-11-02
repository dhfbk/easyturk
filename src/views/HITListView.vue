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
              name: 'ProjectView',
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
      <div class="mb-6" @keydown.enter.prevent.self>
        <label class="block tracking-wide text-gray-900 text-xl font-bold pb-2" for="hit"> Search by HIT ID </label>
        <input
          @keyup.up.prevent="scroll('up')"
          @keyup.down.prevent="scroll('down')"
          @keyup.enter.stop.prevent.self="search(tmp[active])"
          v-model="input"
          placeholder="Search HIT ID"
          autocomplete="off"
          id="hit"
          type="text"
          class="appearance-none w-full border border-gray-200 rounded py-2 px-4 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
        />
        <div class="relative">
          <ul
            class="appearance-none max-h-48 w-full overflow-y-scroll absolute top-0 border border-black z-50"
            v-if="tmp.length > 0"
          >
            <li
              v-for="i in tmp"
              :key="i"
              class="bg-white cursor-pointer"
              @click="search(i)"
              :class="active == tmp.indexOf(i) ? 'bg-primary text-gray-100' : ''"
              @mouseover="active = tmp.indexOf(i)"
              :id="parseInt(tmp.indexOf(i))"
            >
              <span class="mx-2"> {{ i }}</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="block tracking-wide text-gray-900 text-xl font-bold pb-4">Navigate all HITs</div>
      <tableHIT :sortedData="sortedData" v-if="viewType == 'table'" />
      <dotMatrixHIT :sortedData="sortedData" :key="sortedData.length" v-else />
    </div>
  </div>
</template>
<script>
import tableHIT from '../components/tableHIT.vue'
import dotMatrixHIT from '../components/dotMatrixHIT.vue'
import loader from '../components/loader.vue'
import globalMixin from '../globalMixin.js'
import { maxLength } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
  mixins: [globalMixin],
  name: 'HITListView',
  components: {
    tableHIT,
    dotMatrixHIT,
    loader,
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
      HITids: [],
      tmp: [],
      active: 0,
      input: '',
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
    /**
     * Changes route to HIT view of specifified id.
     * @param  {String} id id of HIT
     */
    search(id) {
      if (this.tmp.length > 0) {
        this.$router.push({ name: 'SingleHITView', params: { hitId: id } })
      }
    },
    /**
     * Changes active element of dropdown depending of *where*
     * @param  {String} where direction of scroll (up or down)
     */
    scroll(where) {
      if (document.getElementById(this.active)) {
        if (where == 'up' && this.active > 0) {
          this.active--
        } else if (where == 'down' && this.active < this.tmp.length - 1) {
          this.active++
        }
        document.getElementById(this.active).scrollIntoView()
      }
    },

    // searchHIT() {
    //   this.v$.$touch()
    //   if (!this.v$.$invalid) {
    //     this.loading = true
    //     let found = false
    //     if (this.searchText.length == 0 && this.sortedData.length == 1) {
    //       this.sortedData = this.sortedDataBackup
    //     } else if (this.searchText.length > 0) {
    //       for (let i = 0; i < this.sortedData.length; i++) {
    //         for (let j = 0; j < this.sortedData[i].hits.length; j++) {
    //           if (this.sortedData[i].hits[j] == this.searchText) {
    //             this.sortedData = [this.sortedData[i]]
    //             this.sortedData.hits = ['3YGYP13654XDWV2DQNA7ION0ET1RN3']
    //             found = true
    //             this.searchError = false
    //             break
    //           }
    //         }
    //         if (found) {
    //           break
    //         }
    //       }
    //       if (!found) {
    //         this.searchError = true
    //       }
    //     }
    //     this.loading = false
    //   }
    // },

    /**
     * Performs a sorting of the response HIT data: first completed hits, then incomplete hits (each category ordered by most approved hits to least approved).
     * Result is a list of objects of type of HITs in the aforementioned order.
     */
    sortReceivedData() {
      var arrComp = []
      var arrNotComp = []
      var arrNotTou = []

      for (let i = 0; i < this.progressData.length; i++) {
        this.HITids.push(...this.progressData[i].hits)
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

    /**
     * Goes back to project view when user pushes escape button
     * @param  {Object} event key event
     */
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.$router.push({
          name: 'ProjectView',
          params: { projectId: this.$route.params.projectId },
        })
      }
    },
    /**
     * Calls API to retrieve HITs data.
     */
    getData() {
      this.id = parseInt(this.$route.params.projectId)
      if (isNaN(this.id)) {
        this.$router.replace({ name: 'HomeView' })
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
  watch: {
    /**
     * Watches input variable to constantly update list of HITs the user is searching.
     * @param  {String} newValue new value of variable *input*
     * @param  {String} oldValue value of variable *input* before mutating
     * @return {Array}           list of ids in the total HITs matching the user input
     */
    input(newValue, oldValue) {
      this.active = 0
      if (newValue.length == 0) {
        this.tmp = []
      } else if (newValue.length > oldValue.length && this.tmp.length > 0) {
        this.tmp = this.tmp.filter((id) => {
          return id.startsWith(this.input.toUpperCase())
        })
      } else {
        this.tmp = this.HITids.filter((id) => {
          return id.startsWith(this.input.toUpperCase())
        })
      }
    },
  },
}
</script>

<style scoped></style>
