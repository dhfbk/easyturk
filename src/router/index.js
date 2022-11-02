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
    name: 'LoginView',
    component: lazyLoad('LoginView'),
  },
  {
    path: '/',
    name: 'HomeView',
    component: lazyLoad('HomeView'),
  },
  {
    path: '/settings',
    name: 'UserSettingsView',
    component: lazyLoad('UserSettingsView'),
  },
  {
    path: '/create',
    name: 'NewProjectView',
    component: lazyLoad('ProjectCreationView'),
  },
  {
    path: '/:projectId/edit',
    name: 'EditProjectView',
    component: lazyLoad('ProjectCreationView'),
  },
  {
    path: '/:projectId',
    name: 'ProjectView',
    component: lazyLoad('ProjectView'),
  },
  {
    path: '/:projectId/hitlist',
    name: 'HITListView',
    component: lazyLoad('HITListView'),
  },
  {
    path: '/:projectId/hitlist/:hitId',
    name: 'SingleHITView',
    component: lazyLoad('SingleHITView'),
  },
  {
    path: '/:projectId/:type',
    name: 'CSVDataView',
    component: lazyLoad('CSVDataView'),
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
