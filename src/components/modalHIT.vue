<template>
  <transition name="fade" mode="out-in" appear>
    <div
      class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
      @click="toggleModal('close')"
    >
      <div tabindex="-1" id="modal" class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
        <div class="flex flex-col p-4">
          <div class="flex w-full">
            <h2 class="font-bold text-lg text-primary">HIT settings</h2>
            <span class="ml-auto rounded hover:bg-gray-300 p-1" @click="toggleModal('close')">
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
          <div v-if="baseDataStatus == 0">
            <p class="text-red-500 text-md py-2">Base CSV hasn't been uploaded</p>
          </div>
          <div v-if="goldDataStatus == 0">
            <p class="text-teal-500 text-md py-2">Golden standard CSV hasn't been uploaded</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
            <h2 class="block tracking-wide text-gray-900 text-md font-bold mr-2">Shuffle base CSV data:</h2>
            <label class="inline-flex content-center items-center mr-0 sm:mr-2">
              <input
                type="radio"
                id="auto"
                :value="1"
                class="form-radio"
                checked
                :disabled="baseDataStatus == 0"
                :class="{ 'cursor-not-allowed': baseDataStatus == 0 }"
                v-model="shuffleBase"
              />
              <span class="ml-2 text-gray-700">Yes</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                id="custom"
                :value="0"
                class="form-radio"
                :disabled="baseDataStatus == 0"
                :class="{ 'cursor-not-allowed': baseDataStatus == 0 }"
                v-model="shuffleBase"
              />
              <span class="ml-2 text-gray-700">No</span>
            </label>
          </div>
          <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
            <label class="block tracking-wide text-gray-900 text-md font-bold mr-2" for="gold"
              >Golden data per HIT:</label
            >
            <input
              :class="[
                goldDataStatus == 0 || baseDataStatus == 0
                  ? 'cursor-not-allowed bg-gray-400 text-gray-800 '
                  : ' bg-gray-100 text-gray-700 transition-colors duration-150 ease-out  focus:border-blue-500 hover:border-blue-500',
                v$.goldPerHit.$error ? 'border-red-400' : '',
              ]"
              class="appearance-none block w-full sm:max-w-xs border border-gray-200 rounded py-2 px-4"
              id="gold"
              type="number"
              min="1"
              step="1"
              placeholder="1"
              :disabled="goldDataStatus == 0 || baseDataStatus == 0"
              v-model.trim="v$.goldPerHit.$model"
              required
            />
          </div>
          <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
            <h2 class="block tracking-wide text-gray-900 text-md font-bold mr-2">Shuffle gold CSV data:</h2>
            <label class="inline-flex content-center items-center mr-0 sm:mr-2">
              <input
                type="radio"
                id="auto"
                :value="1"
                class="form-radio"
                checked
                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                :class="{
                  'cursor-not-allowed': goldDataStatus == 0 || baseDataStatus == 0,
                }"
                v-model="shuffleGold"
              />
              <span class="ml-2 text-gray-700">Yes</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                id="custom"
                :value="0"
                class="form-radio"
                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                :class="{
                  'cursor-not-allowed': goldDataStatus == 0 || baseDataStatus == 0,
                }"
                v-model="shuffleGold"
              />
              <span class="ml-2 text-gray-700">No</span>
            </label>
          </div>
          <div class="flex flex-col sm:flex-row items-center content-center w-full py-2 mb-2">
            <label class="block tracking-wide text-gray-900 text-md font-bold mr-2" for="action"
              >Action for the leftover records:</label
            >
            <div class="relative mt-1 sm:mt-0 sm:ml-2">
              <select
                :class="[
                  goldDataStatus == 0 || baseDataStatus == 0
                    ? 'cursor-not-allowed bg-gray-400 text-gray-800'
                    : 'bg-gray-100 text-gray-700 transition-colors duration-150 ease-out  focus:border-blue-500 hover:border-blue-500',
                  v$.leftover.$error ? 'border-red-400' : '',
                ]"
                class="block border border-gray-200 appearance-none w-full py-2 pl-2 pr-8 rounded"
                id="action"
                name="action"
                v-model.trim="v$.leftover.$model"
                required
                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
              >
                <option value="" disabled selected hidden>Choose action...</option>
                <option value="no_use">Don't use</option>
                <option value="reuse">Fill last HIT with previous training</option>
              </select>
              <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <p>With these settings you'll have {{ hitInfo }} HITs</p>
          <div class="pt-2 pb-1">
            <p class="font-light italic text-center">You'll be able to revert these changes and re-upload your files</p>
          </div>
          <div class="ml-auto flex flex-col xs2:flex-row justify-end flex-wrap">
            <button
              class="ripple flex flex-row transition-colors duration-100 ease-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded"
              @click="confirm()"
              :disabled="baseDataStatus == 0"
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
              class="ripple transition-colors duration-100 ease-out mt-2 xs2:mt-0 xs2:ml-2 hover:bg-gray-300 bg-transparent text-gray-800 py-2 px-4 rounded"
              @click="toggleModal('close')"
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
import { required, minValue } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
const notEmpty = (value) => value != ''
import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'modalHIT',
  props: {
    id: String,
    baseDataStatus: Number,
    goldDataStatus: Number,
    params: Number,
  },
  setup: () => ({ v$: useVuelidate() }),
  data() {
    return {
      leftover: '',
      shuffleBase: this.$store.state.defaults.shuffle_base_data,
      shuffleGold: this.$store.state.defaults.shuffle_gold_data,
      loading: false,
      hitInfo: 0,
      goldPerHit: 0,
    }
  },
  validations() {
    return {
      goldPerHit: {
        required,
        minValue: minValue(1),
      },
      leftover: {
        notEmpty,
      },
    }
  },
  created() {
    if (this.goldDataStatus != 0) {
      this.goldPerHit = this.$store.state.defaults.gold_data_per_hit
      this.hitInfo = Math.floor(this.baseDataStatus / (this.params - this.goldPerHit))
    } else {
      this.hitInfo = Math.floor(this.baseDataStatus / this.params)
    }
    if (this.$store.state.defaults.delete_exceeding_values == 0) {
      this.leftover = 'reuse'
    } else {
      this.leftover = 'no_use'
    }
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
    document.getElementById('modal').focus()
  },
  methods: {
    keyboardEvent(e) {
      if (e.code == 'Escape') {
        this.toggleModal('close')
      } else if (e.code == 'Enter') {
        this.confirm()
      }
    },
    toggleModal(mode) {
      //cambiare per inserimento id nella posizione 1 dell'array
      if (mode == 'close') {
        this.$emit('hitModal', [])
      } else {
        this.$emit('hitCreated', 'Error: API call failed')
      }
    },
    confirm() {
      this.v$.$touch()
      if (!this.v$.$invalid) {
        this.loading = true
        var url = '?action=updateProjectStatus&id=' + this.id
        var deleteExceedingValues = 0
        if (this.leftover == 'no_use') {
          deleteExceedingValues = 1
        }
        this.API()
          .post(
            url,
            {
              toStatus: 1,
              goldSize: this.goldPerHit,
              deleteExceedingValues: deleteExceedingValues,
              shuffleData: this.shuffleBase,
              shuffleGold: this.shuffleGold,
            },
            { 'Content-Type': 'application/x-www-form-urlencoded' }
          )
          .then((response) => {
            if (response.data.result == 'ERR') {
              response.data.error.includes('User')
                ? this.$emit('hitCreated', 'Error: ' + response.data.error + '.')
                : this.$emit('hitCreated', 'Error: ' + response.data.error)
            } else {
              this.$emit('hitCreated', 'HIT setup completed')
              this.toggleModal('close')
            }
            this.loading = false
          })
          .catch(() => {
            this.$emit('hitCreated', 'Error: server unreachable')
            this.loading = false
          })
      } else {
        this.$emit('snackbar', 'Error: check inserted values')
      }
    },
  },
  watch: {
    leftover() {
      if (this.leftover == 'no_use') {
        this.hitInfo = Math.floor(this.baseDataStatus / (this.params - this.goldPerHit))
      } else {
        this.hitInfo = Math.ceil(this.baseDataStatus / (this.params - this.goldPerHit))
      }
    },
    goldPerHit: function () {
      if (this.leftover == 'no_use') {
        this.hitInfo = Math.floor(this.baseDataStatus / (this.params - this.goldPerHit))
      } else {
        this.hitInfo = Math.ceil(this.baseDataStatus / (this.params - this.goldPerHit))
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
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.form-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
  display: inline-block;
  vertical-align: middle;
  background-origin: border-box;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  flex-shrink: 0;
  border-radius: 100%;
  height: 1em;
  width: 1em;
  color: #0068b4;
  background-color: #fff;
  border-color: #003e84;
  border-width: 1px;
}
.form-radio:checked {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
  border-color: transparent;
  background-color: currentColor;
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}
.form-radio:disabled {
  border-color: transparent;
  background-color: grey;
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}
@media not print {
  .form-radio::-ms-check {
    border-width: 1px;
    color: transparent;
    background: inherit;
    border-color: inherit;
    border-radius: inherit;
  }
}
.form-radio:focus {
  outline: none;
}
.customZ {
  z-index: 990;
}
</style>
