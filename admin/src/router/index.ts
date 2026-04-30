import { createRouter, createWebHistory } from 'vue-router';
import LoginView from "../views/LoginView.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import DashboardView from "@/views/DashboardView.vue";
import {useAuthStore} from "@/stores/auth.ts";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      {
          path: '/login',
          name: 'login',
          component: LoginView,
          meta: {
              guestOnly: true,
          }
      },
      {
          path: '/',
          component: AdminLayout,
          meta: {
              requiresAuth: true,
          },
          children: [
              {
                  path: '',
                  name: 'dashboard',
                  component: DashboardView
              }
          ]
      }
  ],
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();

    if (!auth.hasLoadedUser) {
        await auth.loadUser();
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return {
            name: 'login',
            query: {
                redirect: to.fullPath,
            }
        };
    }

    if (to.meta.guestOnly && auth.isAuthenticated) {
        return {
            name: 'dashboard',
        };
    }

    return true;
});

export default router;
