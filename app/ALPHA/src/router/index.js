import { createRouter, createWebHistory } from 'vue-router'
import ViewDashboard from '../views/Dashboard.vue'
import ViewIncomeGuaranteedRider from '../views/IncomeGuaranteedRider.vue';

const router = createRouter( {
                                 history: createWebHistory( import.meta.env.BASE_URL ),
                                 routes: [
                                     {
                                         path: '/',
                                         name: 'dashboard',
                                         component: ViewDashboard
                                     },
                                     {
                                         path: '/income-solver',
                                         name: 'income-guaranteed-rider',
                                         component: ViewIncomeGuaranteedRider
                                     }
                                 ]
                             } )

export default router
