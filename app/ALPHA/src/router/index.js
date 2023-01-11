import { createRouter, createWebHistory } from 'vue-router';
import View_Dashboard from '../views/Dashboard.vue';
import View_Income_GuaranteedRider from '../views/income/GuaranteedRider.vue';
import View_Products_All from '../views/products/AllProducts.vue';

const router = createRouter( {
                                 history: createWebHistory( import.meta.env.BASE_URL ),
                                 routes: [
                                     {
                                         path: '/',
                                         name: 'dashboard',
                                         component: View_Dashboard
                                     },
                                     {
                                         path: '/income/solver',
                                         name: 'income__guaranteed-rider',
                                         component: View_Income_GuaranteedRider
                                     },
                                     {
                                         path: '/products/all',
                                         name: 'products__all',
                                         component: View_Products_All
                                     }
                                 ]
                             } )

export default router
