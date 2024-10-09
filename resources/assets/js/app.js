import { createApp } from 'vue/dist/vue.esm-bundler.js';
import FileManager from './components/FileManager.vue';


const app = createApp({});
app.component('file-manager', FileManager);
app.mount('#app');
