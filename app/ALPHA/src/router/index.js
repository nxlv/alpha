import { createRouter, createWebHistory } from 'vue-router';
import View_Dashboard from '../views/Dashboard.vue';
import View_Income_Fixed from '../views/income/Fixed.vue';
import View_Income_Immediate from '../views/income/Immediate.vue';
import View_Indexes_Tracker from '../views/indexes/IndexTracker.vue';
import View_Performance_Analyzer from '../views/performance/PerformanceAnalyzer.vue';
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
                                         path: '/income/solver/fixed',
                                         name: 'income__fixed',
                                         component: View_Income_Fixed
                                     },
                                     {
                                         path: '/income/solver/immediate',
                                         name: 'income__immediate',
                                         component: View_Income_Immediate
                                     },
                                     {
                                         path: '/performance/analyzer',
                                         name: 'performance__analyzer',
                                         component: View_Performance_Analyzer
                                     },
                                     {
                                         path: '/indexes/tracker',
                                         name: 'indexes__tracker',
                                         component: View_Indexes_Tracker
                                     },
                                     {
                                         path: '/products/all',
                                         name: 'products__all',
                                         component: View_Products_All
                                     }
                                 ]
                             } )

export default router
