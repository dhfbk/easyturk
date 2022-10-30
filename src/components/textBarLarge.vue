<template>
  <div class="w-full mb-4 rounded">
    <label
      v-if="componentText.useLabel"
      class="block tracking-wide text-gray-900 text-md font-bold pb-2"
      :for="componentText.id"
      >{{ componentText.label }}</label
    >
    <input
      @keydown.enter="search"
      :class="[
        componentText.bg ? 'bg-' + componentText.bg : 'bg-gray-100',
        status > componentText.statusClass
          ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
          : v$[componentText.id].$error || searchError
          ? 'border-red-400 bg-gray-100 text-gray-700'
          : 'text-gray-700',
        componentText.description ? 'mb-2' : '',
      ]"
      class="appearance-none block w-full border border-gray-200 rounded py-2 px-4 transition-colors duration-100 ease-out focus:border-blue-500 hover:border-blue-500"
      :id="componentText.id"
      type="text"
      :placeholder="componentText.label"
      :maxlength="$store.state.defaults.max_length_name"
      v-model.trim="v$[componentText.id].$model"
      :disabled="status > 2"
      required
    />
    <p v-if="componentText.description" class="text-gray-700 text-xs italic">{{ componentText.description }}</p>
  </div>
</template>

<script>
export default {
  name: 'textBarLarge',
  props: {
    v: Object,
    status: Number,
    componentText: Object,
    searchError: Boolean,
  },
  data() {
    return {
      v$: {},
    }
  },
  created() {
    this.v$ = this.v
  },
  methods: {
    search() {
      if (this.componentText.searchComponent) {
        this.$emit('search')
      }
    },
  },
}
</script>
