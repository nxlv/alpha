import { createApp } from 'vue';
import { createPinia } from 'pinia';
import mitt from 'mitt';
import moment from 'moment';
import ToastPlugin from 'vue-toast-notification';

import globalUtils from './utilities/global.js';
import financeUtils from './utilities/financials.js';
import clientUtils from './utilities/client.js';
import inventoryUtils from './utilities/inventory.js';
import productUtils from './utilities/products.js';

import 'vue-toast-notification/dist/theme-sugar.css';
import '@vueform/multiselect/themes/default.css';
import 'simple-syntax-highlighter/dist/sshpre.css';

import App from './App.vue'
import router from './router'

const app = createApp( App )
const emitter = mitt();
const pinia = createPinia();

app.config.globalProperties.$productUtils = productUtils;
app.config.globalProperties.$globalUtils = globalUtils;
app.config.globalProperties.$financeUtils = financeUtils;
app.config.globalProperties.$clientUtils = clientUtils;
app.config.globalProperties.$inventoryUtils = inventoryUtils;
app.config.globalProperties.$emitter = emitter;
app.config.globalProperties.$moment = moment;

app.use( router )
app.use( pinia );
app.use( ToastPlugin );

app.mount( '#app' )

