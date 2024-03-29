<template>
  <div
    class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
    @click="toggleModal('close')"
  >
    <div tabindex="-1" id="modal" class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
      <div class="flex flex-col p-4">
        <div class="flex w-full">
          <div class="font-bold text-lg text-primary">Confirm action</div>
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
        <div class="py-2">Are you sure you want to revert your changes to the previous state?</div>

        <div class="ml-auto flex flex-col xs2:flex-row">
          <button
            class="ripple flex flex-row transition-colors duration-100 ease-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded"
            @click="revertProject()"
          >
            <svg
              :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
              style="width: 24px; height: 24px"
              viewBox="0 0 24 24"
            >
              <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
            </svg>
            Proceed
          </button>
          <button
            class="ripple transition-colors duration-100 ease-out hover:bg-gray-300 mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
            @click="toggleModal('close')"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import globalMixin from '../../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'modalRevert',
  props: { id: String, toStatus: Number },
  data() {
    return {
      loading: false,
    }
  },
  mounted() {
    window.addEventListener('keydown', this.keyboardEvent)
    document.getElementById('modal').focus()
  },
  methods: {
    keyboardEvent(event) {
      if (event.code == 'Escape') {
        this.toggleModal('close')
      } else if (event.code == 'Enter') {
        this.revertProject()
      }
    },
    toggleModal(mode) {
      if (mode == 'close') {
        this.$emit('revertModal')
      } else {
        this.$emit('revertModal', 'Error. Try again')
      }
    },
    revertProject() {
      this.loading = true
      this.API()
        .post(
          '?action=updateProjectStatus&id=' + this.id,
          {
            toStatus: this.toStatus,
          },
          { 'Content-Type': 'application/x-www-form-urlencoded' }
        )
        .then((res) => {
          this.loading = false
          if (res.data.result == 'ERR') {
            res.data.error.includes('User')
              ? this.$emit('reverted', 'Error: ' + res.data.error + '.')
              : this.$emit('reverted', 'Error: ' + res.data.error)
          } else {
            this.$emit('reverted', 'Project succesfully reverted.')
            this.toggleModal('close')
          }
        })
        .catch(() => {
          this.$emit('error', 'Error: server unreacheable')
          this.loading = false
        })
    },
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.keyboardEvent)
  },
}
</script>

<style scoped>
.customZ {
  z-index: 990;
}
</style>
