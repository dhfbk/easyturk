import Vue from 'vue'
import App from './App.vue'
import router from './router'
import Vuex from 'vuex'
Vue.use(Vuex)

import './assets/styles/index.css'

import VueEllipseProgress from 'vue-ellipse-progress'
Vue.use(VueEllipseProgress)

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import VueTippy, { TippyComponent } from 'vue-tippy'
import 'tippy.js/themes/google.css'
Vue.use(VueTippy)
Vue.component('tippy', TippyComponent)

import axios from 'axios'

Vue.mixin({
    data: function () {
        return {
            get API() {
                return axios.create({
                    baseURL: 'https://dh-server.fbk.eu/mturk_frontend/api/',
                    headers: { 'Session-Id': (localStorage.getItem('session_id') ? localStorage.getItem('session_id') : '') }
                })
            },
        }
    },
})

Vue.config.productionTip = false

const store = new Vuex.Store({
    state: {
        defaults: [],
        isSandbox: true,
        userInfo: [],
        currentRoute: '',
    },
})

new Vue({
    router,
    store: store,
    render: h => h(App),
}).$mount('#app')
