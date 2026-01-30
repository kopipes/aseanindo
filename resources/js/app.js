import './bootstrap';
import Alpine from 'alpinejs'
import { createApp } from 'vue'
import router from './Utils/routes';

import App from './Pages/App.vue'

window.page = JSON.parse(document.getElementById('app').getAttribute('data-page'))

Alpine.start()
createApp(App, {
     page: window.page
})
     .use(router)
     .mount("#app")