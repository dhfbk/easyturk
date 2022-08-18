<template>
  <transition name="fade" mode="out-in" appear>
    <div
      class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
      @click="close"
    >
      <div tabindex="-1" id="modal" class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
        <div class="flex flex-col p-4">
          <div class="flex w-full mb-2">
            <div class="font-bold text-lg text-primary">Edit behavior data</div>
            <span class="ml-auto rounded hover:bg-gray-300 p-1" @click="close()">
              <svg
                class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 18 18"
              >
                <path
                  d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                />
              </svg>
              <span class="sr-only">Close</span>
            </span>
          </div>
          <thirdPart
            :thirdPartData="thirdPartData"
            :min="min.assignNumber"
            :started="true"
            @updateArr="updateArr"
            :isGold="parseInt(project.count_gold)"
            :v="v$"
          />
          <div class="ml-auto flex flex-col xs2:flex-row mt-2">
            <button
              class="ripple flex flex-row transition-colors duration-100 ease-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded"
              @click="submit()"
            >
              <svg
                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                style="width: 24px; height: 24px"
                viewBox="0 0 24 24"
              >
                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
              >Proceed
            </button>
            <button
              class="ripple transition-colors duration-100 ease-out hover:bg-gray-300 mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
              @click="close()"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import thirdPart from '../components/thirdPartLayoutModal.vue'
import { required, minValue, maxValue } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'modalEditBehavior',
  props: { project: Object },
  components: {
    thirdPart,
  },
  data() {
    return {
      loading: false,
      thirdPartData: {
        //prendere dati dal project
        assignNumber: this.project.hit_details.assignNumber
          ? this.project.hit_details.assignNumber
          : parseInt(this.project.workers),
        rejectReason: this.project.hit_details.rejectReason
          ? this.project.hit_details.rejectReason
          : this.$store.state.defaults.reject_reason,
        rejectTime: this.project.hit_details.rejectTime
          ? this.project.hit_details.rejectTime
          : this.$store.state.defaults.min_time_block,
        missNumberTotal: this.project.hit_details.missNumberTotal ? this.project.hit_details.missNumberTotal : 10,
        missNumber: this.project.hit_details.missNumber ? this.project.hit_details.missNumber : 3,
        acceptIfGoldRight: this.project.hit_details.acceptIfGoldRight ? this.project.hit_details.acceptIfGoldRight : 0,
        blockMisclass: this.project.hit_details.blockMisclass ? this.project.hit_details.blockMisclass : 1,
        blockSeconds: this.project.hit_details.blockSeconds ? this.project.hit_details.blockSeconds : 1,
        block_worker_bad: this.project.hit_details.block_worker_bad ? this.project.hit_details.block_worker_bad : 0,
        block_worker_fast: this.project.hit_details.block_worker_fast ? this.project.hit_details.block_worker_fast : 0,
        rejectIfGoldWrong: this.project.hit_details.rejectIfGoldWrong ? this.project.hit_details.rejectIfGoldWrong : 0,
        rejectPay: this.project.hit_details.rejectPay ? this.project.hit_details.rejectPay : 1,
        reject_old: this.project.hit_details.reject_old ? this.project.hit_details.reject_old : 0,
      },
      min: {
        assignNumber: parseInt(this.project.workers),
      },
    }
  },
  validations() {
    return {
      thirdPartData: {
        assignNumber: {
          required,
          minValue: minValue(this.min.assignNumber),
          maxValue: maxValue(this.$store.state.defaults.max_reject_if_gold_wrong),
        },
        rejectTime: {
          required,
          minValue: minValue(this.$store.state.defaults.min_time_block),
        },
        missNumberTotal: {
          required,
          minValue: minValue(1),
        },
        missNumber: {
          required,
          minValue: minValue(1),
          maxValue: maxValue(this.thirdPartData.missNumberTotal),
        },
        rejectReason: {
          required,
        },
      },
    }
  },
  setup: () => ({ v$: useVuelidate() }),
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
    document.getElementById('modal').focus()
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
  methods: {
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.close()
      } else if (event.code == 'Enter') {
        this.submit()
      }
    },
    submit() {
      this.v$.$touch()
      if (!this.v$.$invalid || (this.rejectIfGoldWrong == 0 && this.v$.rejectReason.$invalid)) {
        this.loading = true
        var url = '?action=updateBehavior'
        var dataToSend = {
          id: this.project.id,
        }
        dataToSend = { ...dataToSend, ...this.thirdPartData }
        this.API()
          .post(url, dataToSend, {
            'Content-Type': 'application/x-www-form-urlencoded',
          })
          .then((response) => {
            this.loading = false
            if (response.data.result == 'ERR') {
              response.data.error.includes('User')
                ? this.$emit('snackbar', 'Error: ' + response.data.error + '.')
                : this.$emit('snackbar', 'Error: ' + response.data.error)
            } else {
              this.close()
              this.$emit('snackbar', 'Layout edit succesful.')
            }
          })
          .catch((err) => {
            this.$emit('snackbar', 'Error: server unreacheable')
            console.error(err)
            this.loading = false
          })
      } else {
        this.$emit('snackbarErr', 'Error: check inserted values')
      }
    },
    close() {
      this.$emit('close')
    },
    updateArr(arr) {
      if (arr != undefined) {
        this.thirdPartData = { ...arr[1] }
      }
    },
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
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.customZ {
  z-index: 990;
}
</style>
