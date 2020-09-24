import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import creazioneProgetto from '../views/creazioneProgetto.vue'
import visualizzaProgetto from '../views/visualizzaProgetto.vue'
import reviewResults from '../views/reviewResults.vue'
import HITList from '../views/HITList.vue'
import viewHIT from '../views/viewHIT.vue'
import workerList from '../views/workerList.vue'
import csvView from '../views/csvView.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/create',
        name: 'new',
        component: creazioneProgetto,
    },
    {
        path: '/:projectId/edit',
        name: 'edit',
        component: creazioneProgetto,
    },
    {
        path: '/:projectId',
        name: 'projectView',
        component: visualizzaProgetto,
    },
    {
        path: '/:projectId/hitlist',
        name: 'HITlist',
        component: HITList,
    },
    {
        path: '/:projectId/results',
        name: 'results',
        component: reviewResults,
    },
    {
        path: '/:projectId/hitlist/hit',
        name: 'viewHIT',
        component: viewHIT,
    },
    {
        path: '/workers',
        name: 'workerList',
        component: workerList,
    },
    {
        path: '/:projectId/data/:type',
        name: 'csv',
        component: csvView,
    },
]

const router = new VueRouter({
    routes,
})

export default router
