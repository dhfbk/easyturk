import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import creazioneProgetto from '../views/creazioneProgetto.vue'
import visualizzaProgetto from '../views/visualizzaProgetto.vue'
import reviewResults from '../views/reviewResults.vue'
import HITList from "../views/HITList.vue"
import viewHIT from "../views/viewHIT.vue"
import workerList from '../views/workerList.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/create',
    name: 'nuovoProgetto',
    component: creazioneProgetto,
  },
  {
    path: '/edit',
    name: 'modificaProgetto',
    component: creazioneProgetto,
  },
  {
    path: '/view',
    name: 'visualizzaProgetto',
    component: visualizzaProgetto,
  },
  {
    path: "/hitlist",
    name: "listaHIT",
    component: HITList
  },
  {
    path: '/results',
    name: 'reviewResults',
    component: reviewResults,
  },
  {
    path: '/hit',
    name: 'viewHIT',
    component: viewHIT,
  },
  {
    path: '/workers',
    name: 'workerList',
    component: workerList,
  },
]

const router = new VueRouter({
  routes,
})

export default router
