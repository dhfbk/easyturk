<template>
  <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
    <modalEliminazione v-if="modal" @hideModal="toggleModal" />
    <div class="flex justify-between flex-wrap items-center">
      <button
        @click="
          $router.push({
            name: 'HITlist',
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
        <span class="sr-only">Back to HIT list</span>
      </button>
      <p v-if="!loading" class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">
        HIT {{ prjData.values.id_hit }}
      </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 mt-2">
      <div class="mr-0 md:mr-1">
        <div class="w-full flex flex-col justify-center" v-if="loading">
          <loader :type="'cardInfoVisualizza'" v-for="n in 2" :key="n" />
        </div>
        <div v-else>
          <cardInfo :projectData="project" :mode="'general'" />
          <!-- <cardInfo :projectData="prjData" :mode="'hitTable'" /> -->
          <cardTable :tableData="assignmentData" :emptyText="'No data to display'" />
          <cardTable :tableData="tableData" :emptyText="'No data to display'" />
        </div>
      </div>
      <div class="ml-0 md:ml-1">
        <div class="w-full flex flex-col justify-center" v-if="loading">
          <loader :type="'cardInfoVisualizza'" />
          <loader :type="'cardAnalyticsVisualizza'" :num="3" />
        </div>
        <div v-else>
          <cardInfo :projectData="project" :mode="'payment'" />
          <cardAnalytics :dati="analytics.cardHIT" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import loader from '../components/loader.vue'
import cardTable from '../components/cardTable.vue'

import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'viewHIT',
  components: {
    modalEliminazione,
    cardInfo,
    cardAnalytics,
    cardTable,
    loader,
  },
  data() {
    return {
      prjData: {},
      project: {},
      progressData: {
        completed: 0,
        available: 0,
        pending: 0,
      },
      analytics: {},
      dropdownOpen: false,
      modal: false,
      loading: true,
    }
  },
  created() {
    this.getData()
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  computed: {
    assignmentData: function () {
      var ret = {}
      var assignments = { ...this.prjData.assignments }

      if (!(Object.keys(assignments).length === 0 && assignments.constructor === Object)) {
        ret['head'] = ['Worker ID', 'Status', 'Assignment ID']
        ret['body'] = []
        var thisLine = []
        for (var assIndex in assignments) {
          thisLine = []
          thisLine.push(assignments[assIndex]['worker_id']) // eslint-disable-line vue/no-side-effects-in-computed-properties
          thisLine.push(assignments[assIndex]['status']) // eslint-disable-line vue/no-side-effects-in-computed-properties
          thisLine.push(assignments[assIndex]['assignment_id']) // eslint-disable-line vue/no-side-effects-in-computed-properties
          ret['body'].push(thisLine)
        }
      }

      return ret
    },
    tableData: function () {
      var ret = {}
      var fields = this.prjData.fields.slice()
      var lines = { ...this.prjData.lines }

      if (fields) {
        ret['head'] = fields
        ret['body'] = []
        if (lines) {
          for (var line in lines) {
            var thisLine = []
            for (var field in fields) {
              if (lines[line][fields[field]]) {
                thisLine.push(this.truncateString(lines[line][fields[field]], 50)) // eslint-disable-line vue/no-side-effects-in-computed-properties
                // thisLine.push(lines[line][fields[field]])// eslint-disable-line vue/no-side-effects-in-computed-properties
              } else {
                thisLine.push('') // eslint-disable-line vue/no-side-effects-in-computed-properties
              }
            }
            ret['body'].push(thisLine)
          }
        }
      }
      return ret
    },
  },
  methods: {
    truncateString(str, num) {
      if (str.length > num) {
        return str.slice(0, num) + '...'
      } else {
        return str
      }
    },
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.$router.push({
          name: 'HITlist',
          params: { projectId: this.$route.params.projectId },
        })
      }
    },
    getData() {
      this.API()
        .get('?action=getHitInfo&hitID=' + this.$route.params.hitId)
        .then((res) => {
          if (res.data.result == 'ERR') {
            res.data.error.includes('User')
              ? this.$emit('snackbar', 'Error. ' + res.data.error + '. Refresh to log in.')
              : this.$emit('snackbar', 'Error. ' + res.data.error)
          } else {
            this.prjData = res.data
            var tmpDate = new Date(res.data.values.hit_info.Expiration)
            var expiration = this.timeConverter(tmpDate.getTime() / 1000)
            this.project = {
              description: res.data.values.hit_info.Description,
              title: res.data.values.hit_info.Title,
              keywords: res.data.values.hit_info.Keywords,
              created_at: res.data.values.hit_info.CreationTime,
              layout_id: res.data.values.hit_info.HITLayoutId,
              params: res.data.values.hit_info.MaxAssignments, //mettere il params giusto
              reward: res.data.values.hit_info.Reward,
              hit_group_id: res.data.values.hit_info.HITGroupId,
              workers: res.data.values.hit_info.MaxAssignments,
              max_time: res.data.values.hit_info.AssignmentDurationInSeconds / 60, //converti in X
              expiry: expiration, //convertire da data a X
              auto_approve: res.data.values.hit_info.AutoApprovalDelayInSeconds / 60, //convertire da secondi a x
            }
            this.convertProgress()
            this.setAnalyticsCard()
            this.loading = false
          }
        })
        .catch((err) => {
          console.error(err.message)
          this.$emit('snackbar', 'Error. ' + err.message)
          this.$router.push({
            name: 'HITlist',
            params: { projectId: this.$route.params.projectId },
          })
        })
    },
    timeConverter(tmp) {
      var a = new Date(tmp * 1000)
      var year = a.getFullYear()
      var month = a.getMonth() + 1 < 10 ? '0' + (a.getMonth() + 1) : a.getMonth() + 1
      var date = a.getDate() < 10 ? '0' + a.getDate() : a.getDate()
      var time =
        year + '-' + month + '-' + date + ' ' + (a.getHours() - a.getTimezoneOffset() / 60) + ':' + a.getMinutes()
      return time
    },
    //metodo che imposta i titoli e i dati da inserire nelle card della pagina
    setAnalyticsCard() {
      this.analytics = {
        cardHIT: {
          titolo: 'Assignments',
          totale: parseInt(this.prjData.values.hit_info.MaxAssignments),
          type: 'assignment',
          ellipse_progress: {
            progress1: {
              progress: this.progressData.completed,
              legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsCompleted),
              color: '#f6ad55',
              half: true,
              angle: 0,
              caption: 'Completed',
            },
            progress2: {
              progress: this.progressData.available,
              legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsAvailable),
              color: '#f6ad55',
              half: true,
              angle: 0,
              caption: 'Available',
            },
            progress3: {
              progress: this.progressData.pending,
              legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsPending),
              color: '#f6ad55',
              half: true,
              angle: 0,
              caption: 'Pending',
            },
          },
        },
      }
    },
    //metodo che mostra o nasconde il dialog
    toggleModal() {
      this.modal = !this.modal
      this.hide()
    },
    //nasconde il dropdown
    hide() {
      this.dropdownOpen = false
    },
    //calcola il numero da utilizzare nei grafici delle analytics
    convertProgress() {
      this.progressData.completed =
        (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsCompleted)) /
        parseInt(this.prjData.values.hit_info.MaxAssignments)
      this.progressData.available =
        (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsAvailable)) /
        parseInt(this.prjData.values.hit_info.MaxAssignments)
      this.progressData.pending =
        (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsPending)) /
        parseInt(this.prjData.values.hit_info.MaxAssignments)
    },
  },
  watch: {
    modal() {
      if (this.modal) {
        window.removeEventListener('keydown', this.keyboardEvent)
      } else {
        window.addEventListener('keydown', this.keyboardEvent)
      }
    },
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>
