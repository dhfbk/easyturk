import { createRouter, createWebHashHistory } from 'vue-router'

function lazyLoad(view) {
  return () => import(`@/views/${view}.vue`)
}

const routes = [
  // {
  //   path: '*',
  //   redirect: '/',
  // },
  {
    path: '/login',
    name: 'login',
    component: lazyLoad('login'),
  },
  {
    path: '/',
    name: 'Home',
    component: lazyLoad('Home'),
  },
  {
    path: '/settings',
    name: 'settings',
    component: lazyLoad('settings'),
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
    component: lazyLoad('HITList'),
  },
  {
    path: '/:projectId/hitlist/:hitId',
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

const router = createRouter({
  history: createWebHashHistory(),
  base: process.env.BASE_URL,
  routes,
  // eslint-disable-next-line
  scrollBehavior(to, from, savedPosition) {
    return { top: 0, left: 0 }
  },
})

export default router
