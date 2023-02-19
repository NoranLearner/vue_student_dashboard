import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";

import "normalize.css";

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// Call Fontawesome
import { library } from '@fortawesome/fontawesome-svg-core';
/* import specific icons */
/* Regular Icons */
import { far } from '@fortawesome/free-regular-svg-icons';
/* Solid Icons */
import { fas } from '@fortawesome/free-solid-svg-icons';
/* Add Icons To The Library*/
library.add(far, fas);

createApp(App)
    // Add Fontawesome Icon Component
    .component("font-awesome-icon", FontAwesomeIcon)
    .use(store)
    .use(router)
    .mount('#app')
