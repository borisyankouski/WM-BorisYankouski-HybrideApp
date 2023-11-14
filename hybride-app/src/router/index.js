import { createRouter, createWebHistory } from '@ionic/vue-router';
import TabsPage from '../views/TabsPage.vue'

const routes = [
  {
    path: '/',
    redirect: '/tabs/tab1'
  },
  {
    path: '/tabs/',
    component: TabsPage,
    children: [
      {
        path: '',
        redirect: '/tabs/tab1'
      },
      {
        path: 'tab1',
        component: () => import('@/views/Medewerkers.vue')
      },
      {
        path: 'tab2',
        component: () => import('@/views/Projecten.vue')
      },
      {
        path: 'tab3',
        component: () => import('@/views/About.vue')
      },
      {
        path: 'tab4',
        component: () => import('@/views/ProjectInfo.vue')
      },
      {
        path: 'tab5',
        component: () => import('@/views/MedewerkerInfo.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
