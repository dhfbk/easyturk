<template>
  <div @change="update">
    <div v-if="isGold == 0">
      <p class="text-teal-500 text-md text-center">Golden standard CSV hasn't been uploaded</p>
    </div>
    <div class="flex flex-col md:flex-row items-center md:items-end content-center text-sm">
      <div class="relative w-full block md:mt-0">
        <span class="flex flex-row items-center my-2">
          <span
            class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
            :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : ''"
          >
            <input
              type="checkbox"
              id="reject"
              class="opacity-0 absolute w-4 h-4"
              @click="reject = !reject"
              v-model="reject"
              :disabled="isGold == 0"
              :class="isGold == 0 ? 'cursor-not-allowed' : ''"
            />
            <svg
              :class="reject ? '' : 'hidden'"
              class="fill-current w-4 h-4 text-secondary pointer-events-none"
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </span>
          <label class="ml-2 font-semibold tracking-wide" for="reject">
            <div class="relative inline">
              <select
                :class="
                  !reject || isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                "
                :disabled="!reject || isGold == 0"
                class="inline-input appearance-none w-xs border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
                id="selectorExpiry"
                v-model.number="rejectPay"
              >
                <option selected value="1">Reject</option>
                <option value="0">Accept anyway</option>
              </select>
              <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            if gold is wrong and extend HIT to
            <input
              :class="
                !reject || isGold == 0
                  ? 'text-gray-800 cursor-not-allowed ' +
                    (v.thirdPartData.assignNumber.$invalid
                      ? 'border-red-400 bg-red-400 hover:border-red-500'
                      : 'bg-gray-400')
                  : 'text-gray-700 ' +
                    (v.thirdPartData.assignNumber.$invalid
                      ? 'border-red-200 bg-red-100 hover:border-red-500'
                      : 'bg-gray-100')
              "
              :disabled="!reject || isGold == 0"
              class="inline-input appearance-none w-full border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
              id="assNumber"
              name="assNumber"
              type="number"
              :min="min"
              :max="$store.state.defaults.max_reject_if_gold_wrong"
              step="1"
              @blur="v.thirdPartData.assignNumber.$touch()"
              placeholder="1"
              required
              v-model.number="assignNumber"
            />
            assignments (min {{ min }}). Reason of reject:
          </label>
        </span>
        <div class="my-2">
          <input
            type="text"
            :class="[
              !reject || isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700',
              v.thirdPartData.rejectReason.$error ? 'border-red-400' : '',
            ]"
            placeholder="Reason for rejection"
            class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 md:mt-0 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
            :disabled="!reject || isGold == 0"
            id="reject"
            v-model.trim="rejectReason"
            required
          />
        </div>
        <span class="flex flex-row items-center my-2">
          <span
            class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
            :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
          >
            <input
              type="checkbox"
              id="accept"
              class="opacity-0 absolute w-4 h-4"
              @click="accept = !accept"
              v-model="accept"
              :class="isGold == 0 ? 'cursor-not-allowed' : ''"
              :disabled="isGold == 0"
            />
            <svg
              :class="accept ? '' : 'hidden'"
              class="fill-current w-4 h-4 text-secondary pointer-events-none"
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </span>
          <label class="ml-2 font-semibold tracking-wide" for="accept">Automatically accept if gold is right</label>
        </span>

        <span class="flex flex-row items-center my-2">
          <span
            class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
            :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
          >
            <input
              type="checkbox"
              id="block_worker_fast"
              class="opacity-0 absolute w-4 h-4"
              @click="block_worker_fast = !block_worker_fast"
              v-model="block_worker_fast"
              :class="isGold == 0 ? 'cursor-not-allowed' : ''"
              :disabled="isGold == 0"
            />
            <svg
              :class="block_worker_fast ? '' : 'hidden'"
              class="fill-current w-4 h-4 text-secondary pointer-events-none"
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </span>
          <label class="ml-2 font-semibold tracking-wide" for="block_worker_fast">
            <div class="relative inline">
              <select
                :class="
                  !block_worker_fast || isGold == 0
                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                    : 'bg-gray-100 text-gray-700'
                "
                :disabled="!block_worker_fast || isGold == 0"
                class="inline-input appearance-none w-xs border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
                id="selectorExpiry"
                v-model.number="blockSeconds"
              >
                <option selected value="1">Block</option>
                <option value="0">Restrict</option>
              </select>
              <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            the worker if he/she spends less than
            <input
              :class="
                !block_worker_fast || isGold == 0
                  ? 'text-gray-800 cursor-not-allowed ' +
                    (v.thirdPartData.rejectTime.$invalid
                      ? 'border-red-400 bg-red-400 hover:border-red-500'
                      : 'bg-gray-400')
                  : 'text-gray-700 ' +
                    (v.thirdPartData.rejectTime.$invalid
                      ? 'border-red-200 bg-red-100 hover:border-red-500'
                      : 'bg-gray-100')
              "
              :disabled="!block_worker_fast || isGold == 0"
              class="inline-input appearance-none border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
              id="rejectTime"
              name="rejectTime"
              type="number"
              @blur="v.thirdPartData.rejectTime.$touch()"
              :min="$store.state.defaults.min_time_block"
              step="10"
              placeholder="1"
              required
              v-model.number="rejectTime"
            />
            seconds on a HIT
          </label>
        </span>

        <span class="flex flex-row items-center my-2">
          <span
            class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
            :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
          >
            <input
              type="checkbox"
              id="block_worker_bad"
              class="opacity-0 absolute w-4 h-4"
              @click="block_worker_bad = !block_worker_bad"
              v-model="block_worker_bad"
              :class="isGold == 0 ? 'cursor-not-allowed' : ''"
              :disabled="isGold == 0"
            />
            <svg
              :class="block_worker_bad ? '' : 'hidden'"
              class="fill-current w-4 h-4 text-secondary pointer-events-none"
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </span>
          <label class="ml-2 font-semibold tracking-wide" for="block_worker_bad">
            <div class="relative inline">
              <select
                :class="
                  !block_worker_bad || isGold == 0
                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                    : 'bg-gray-100 text-gray-700'
                "
                :disabled="!block_worker_bad || isGold == 0"
                class="inline-input appearance-none w-xs border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
                id="selectorExpiry"
                v-model.number="blockMisclass"
              >
                <option selected value="1">Block</option>
                <option value="0">Restrict</option>
              </select>
              <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            the worker if he/she misclassifies the gold
            <input
              :class="
                !block_worker_bad || isGold == 0
                  ? 'text-gray-800 cursor-not-allowed ' +
                    (v.thirdPartData.missNumber.$invalid
                      ? 'border-red-400 bg-red-400 hover:border-red-500'
                      : 'bg-gray-400')
                  : 'text-gray-700 ' +
                    (v.thirdPartData.missNumber.$invalid
                      ? 'border-red-200 bg-red-100 hover:border-red-500'
                      : 'bg-gray-100')
              "
              :disabled="!block_worker_bad || isGold == 0"
              class="inline-input appearance-none border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
              id="missNumber"
              name="missNumber"
              type="number"
              @blur="v.thirdPartData.missNumber.$touch()"
              min="1"
              :max="missNumberTotal"
              step="1"
              placeholder="1"
              required
              v-model.number="missNumber"
            />
            times on
            <input
              :class="
                !block_worker_bad || isGold == 0
                  ? 'text-gray-800 cursor-not-allowed ' +
                    (v.thirdPartData.missNumberTotal.$invalid
                      ? 'border-red-400 bg-red-400 hover:border-red-500'
                      : 'bg-gray-400')
                  : 'text-gray-700 ' +
                    (v.thirdPartData.missNumberTotal.$invalid
                      ? 'border-red-200 bg-red-100 hover:border-red-500'
                      : 'bg-gray-100')
              "
              :disabled="!block_worker_bad || isGold == 0"
              class="inline-input appearance-none border border-gray-200 rounded py-2 pr-4 pl-2 transition-colors duration-150 ease-out focus:border-blue-500 hover:border-blue-500"
              id="missNumberTotal"
              name="missNumberTotal"
              type="number"
              @blur="v.thirdPartData.missNumberTotal.$touch()"
              min="1"
              step="1"
              placeholder="1"
              required
              v-model.number="missNumberTotal"
            />
            consecutive HITs
          </label>
        </span>

        <span class="flex flex-row items-center my-2">
          <span
            class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
            :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
          >
            <input
              type="checkbox"
              id="reject_old"
              class="opacity-0 absolute w-4 h-4"
              @click="reject_old = !reject_old"
              v-model="reject_old"
              :class="isGold == 0 ? 'cursor-not-allowed' : ''"
              :disabled="isGold == 0"
            />
            <svg
              :class="reject_old ? '' : 'hidden'"
              class="fill-current w-4 h-4 text-secondary pointer-events-none"
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </span>
          <label class="ml-2 font-semibold tracking-wide" for="reject_old">
            Reject pending assignments from blocked/retricted users
          </label>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'thirdPart',
  props: {
    thirdPartData: Object,
    isGold: Number,
    started: Boolean,
    min: Number,
    v: Object,
  },
  data() {
    return {
      reject: false,
      accept: false,
      assignNumber: this.thirdPartData.assignNumber + 2,
      block_worker_fast: false,
      rejectTime: this.$store.state.defaults.min_time_block,
      block_worker_bad: false,
      missNumberTotal: this.thirdPartData.missNumberTotal,
      missNumber: this.thirdPartData.missNumber,
      reject_old: false,
      dataReturn: {},
      rejectPay: 1,
      blockSeconds: 1,
      blockMisclass: 1,
      rejectReason: this.$store.state.defaults.reject_reason,
    }
  },
  created() {
    if (this.started) {
      this.reject = this.boolToInt(this.thirdPartData.rejectIfGoldWrong)
      this.accept = this.boolToInt(this.thirdPartData.acceptIfGoldRight)
      this.rejectReason = this.thirdPartData.rejectReason
      this.assignNumber = this.thirdPartData.assignNumber

      this.block_worker_fast = this.boolToInt(this.thirdPartData.block_worker_fast)
      this.rejectTime = this.thirdPartData.rejectTime
      this.block_worker_bad = this.boolToInt(this.thirdPartData.block_worker_bad)
      this.missNumberTotal = this.thirdPartData.missNumberTotal
      this.missNumber = this.thirdPartData.missNumber

      this.reject_old = this.boolToInt(this.thirdPartData.reject_old)

      this.rejectPay = this.boolToInt(this.thirdPartData.rejectPay)
      this.blockSeconds = this.boolToInt(this.thirdPartData.blockSeconds)
      this.blockMisclass = this.boolToInt(this.thirdPartData.blockMisclass)
    }
    this.update()
  },
  methods: {
    boolToInt(i) {
      return i == false ? 0 : 1
    },
    update() {
      this.dataReturn['rejectIfGoldWrong'] = this.boolToInt(this.reject)
      this.dataReturn['acceptIfGoldRight'] = this.boolToInt(this.accept)
      this.dataReturn['rejectReason'] = this.rejectReason
      this.dataReturn['assignNumber'] = this.assignNumber

      this.dataReturn['block_worker_fast'] = this.boolToInt(this.block_worker_fast)
      this.dataReturn['rejectTime'] = this.rejectTime

      this.dataReturn['block_worker_bad'] = this.boolToInt(this.block_worker_bad)
      this.dataReturn['missNumberTotal'] = this.missNumberTotal
      this.dataReturn['missNumber'] = this.missNumber

      this.dataReturn['reject_old'] = this.boolToInt(this.reject_old)

      this.dataReturn['rejectPay'] = this.rejectPay
      this.dataReturn['blockSeconds'] = this.blockSeconds
      this.dataReturn['blockMisclass'] = this.blockMisclass

      this.$emit('updateArr', ['third', this.dataReturn])
    },
  },
  /*
    watch: {
        assignNumber() {
            if (parseInt(this.assignNumber) < this.min) {
                this.assignNumber = this.min
            }
            if (parseInt(this.assignNumber) > this.$store.state.defaults.max_reject_if_gold_wrong) {
                this.assignNumber = this.$store.state.defaults.max_reject_if_gold_wrong
            }
        },
    },
    */
}
</script>

<style scoped>
.check {
  height: 20px;
  width: 20px;
}

input.inline-input {
  width: 75px;
}
</style>
