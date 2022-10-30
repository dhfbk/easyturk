import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
// import globalMixin from './globalMixin.js'

import './assets/styles/index.css'
import veProgress from 'vue-ellipse-progress'

import VueTippy from 'vue-tippy'
import 'tippy.js/dist/tippy.css'

import VueClickAway from 'vue3-click-away'

// const globalMixin = {
//   methods: {
//     API() {
//       return axios.create({
//         baseURL: process.env.VUE_APP_SERVER_INSTANCE,
//         headers: {
//           'Session-Id': localStorage.getItem('session_id'),
//         },
//       })
//     },
//   },
// }

createApp(App).use(veProgress).use(VueTippy).use(VueClickAway).use(store).use(router).mount('#app')
