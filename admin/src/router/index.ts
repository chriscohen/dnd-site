import { createRouter, createWebHistory } from 'vue-router';
import LoginVue from "@/views/LoginVue.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import DashboardView from "@/views/DashboardView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      {
          path: '/login',
          name: 'login',
          component: LoginVue
      },
      {
          path: '/',
          component: AdminLayout,
          children: [
              {
                  path: '',
                  name: 'dashboard',
                  component: DashboardView
              }
          ]
      }
  ],
})

export default router;
