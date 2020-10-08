import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

function lazyLoad(view) {
    return () => import(`@/views/${view}.vue`)
}

const routes = [
    {
        path: '/',
        name: 'Home',
        component: lazyLoad('Home'),
    },
    {
        path: '/create',
        name: 'new',
        component: lazyLoad('creazioneProgetto'),
    },
    {
        path: '/:projectId/edit',
        name: 'edit',
        component: lazyLoad('creazioneProgetto'),
    },
    {
        path: '/:projectId',
        name: 'projectView',
        component: lazyLoad('visualizzaProgetto'),
    },
    {
        path: '/:projectId/hitlist',
        name: 'HITlist',
        component: lazyLoad('HITlist'),
    },
    {
        path: '/:projectId/results',
        name: 'results',
        component: lazyLoad('reviewResults'),
    },
    {
        path: '/:projectId/hitlist/hit',
        name: 'viewHIT',
        component: lazyLoad('viewHIT'),
    },
    {
        path: '/workers',
        name: 'workerList',
        component: lazyLoad('workerList'),
    },
    {
        path: '/:projectId/:type',
        name: 'csv',
        component: lazyLoad('csvView'),
    },
]

const router = new VueRouter({
    saveScrollPosition: false,
    mode: 'history',
    base: process.env.BASE_URL,
    routes: routes,
})

export default router
