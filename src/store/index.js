import { createStore } from 'vuex'

export default createStore({
  state: {
    defaults: [],
    isSandbox: true,
    userInfo: [],
    currentRoute: '',
  },
})
