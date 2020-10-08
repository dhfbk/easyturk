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

Vue.mixin({
    data: function() {
        return {
            get APIURL() {
                return 'https://dh-server.fbk.eu/mturk_frontend/api/'
            },
        }
    },
})

Vue.config.productionTip = false

const store = new Vuex.Store({
    state: {
        defaults: [],
        isSandbox: true,
    },
})

new Vue({
    router,
    store: store,
    render: h => h(App),
}).$mount('#app')
