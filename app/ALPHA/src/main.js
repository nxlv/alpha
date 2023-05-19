import { createApp } from 'vue'
import { createPinia } from 'pinia'
import mitt from 'mitt'

import globalUtils from './utilities/global.js';
import financeUtils from './utilities/financials.js';

import App from './App.vue'
import router from './router'

const app = createApp( App )
const emitter = mitt();

app.config.globalProperties.$globalUtils = globalUtils;
app.config.globalProperties.$financeUtils = financeUtils;
app.config.globalProperties.$emitter = emitter;

app.use( createPinia() )
app.use( router )

app.mount( '#app' )

