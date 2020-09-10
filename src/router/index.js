import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import creazioneProgetto from '../views/creazioneProgetto.vue'
import visualizzaProgetto from '../views/visualizzaProgetto.vue'
import reviewResults from '../views/reviewResults.vue'
import HITList from "../views/HITList.vue";

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
]

const router = new VueRouter({
  routes,
})

export default router
