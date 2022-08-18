<template>
  <div class="bg-white shadow-md rounded py-4 flex flex-col px-8 mt-4 mx-2 xs2:mx-4 lg:mx-auto lg:w-5/6 pt-2">
    <p class="text-2xl mb-4 mt-0 text-primary">Account settings</p>
  </div>
</template>

<script>
//import axios from 'axios'
//import { required, maxLength, maxValue, minValue } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

import globalMixin from '../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'creazioneProgetto',
  data() {
    return {}
  },
  setup: () => ({ v$: useVuelidate() }),
  validations() {
    return {}
  },
  created() {},
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
  },
  methods: {},
}
</script>

<style scoped></style>
