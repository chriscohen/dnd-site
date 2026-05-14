import { createRouter, createWebHistory } from 'vue-router';
import LoginView from "../views/LoginView.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import DashboardView from "@/views/DashboardView.vue";
import {useAuthStore} from "@/stores/auth.ts";
import CompaniesView from "../views/companies/CompaniesView.vue";
import EditCompanyView from "../views/companies/EditCompanyView.vue";
import SourcesView from "@/views/sources/SourcesView.vue";
import EditSourceView from "@/views/sources/EditSourceView.vue";
import CampaignSettingsView from "../views/campaignSettings/CampaignSettingsView.vue";
import EditCampaignSettingView from "../views/campaignSettings/EditCampaignSettingView.vue";
import PeopleView from "@/views/people/PeopleView.vue";
import EditPersonView from "@/views/people/EditPersonView.vue";
import SpellsView from "@/views/spells/SpellsView.vue";
import EditSpellView from "@/views/spells/EditSpellView.vue";
import SpellEditionsView from "@/views/spells/SpellEditionsView.vue";
import EditSpellEditionView from "@/views/spells/EditSpellEditionView.vue";

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
                },
                {
                    path: '/campaign-settings',
                    name: 'campaign-settings',
                    component: CampaignSettingsView,
                },
                {
                    path: 'campaign-setting/:slug/edit',
                    name: 'campaign-setting.edit',
                    component: EditCampaignSettingView,
                },
                {
                    path: '/companies',
                    name: 'companies',
                    component: CompaniesView,
                },
                {
                    path: '/company/:slug/edit',
                    name: 'company.edit',
                    component: EditCompanyView,
                },
                {
                    path: '/people',
                    name: 'people',
                    component: PeopleView,
                },
                {
                    path: '/person/:slug/edit',
                    name: 'person.edit',
                    component: EditPersonView,
                },
                {
                    path: '/sources',
                    name: 'sources',
                    component: SourcesView,
                },
                {
                    path: '/source/:slug/edit',
                    name: 'source.edit',
                    component: EditSourceView,
                },
                {
                    path: '/spells',
                    name: 'spells',
                    component: SpellsView
                },
                {
                    path: '/spell/:slug/edit',
                    name: 'spell.edit',
                    component: EditSpellView
                },
                {
                    path: '/spell-editions',
                    name: 'spell-editions',
                    component: SpellEditionsView
                },
                {
                    path: '/spell-edition/:id/edit',
                    name: 'spell-edition.edit',
                    component: EditSpellEditionView
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
