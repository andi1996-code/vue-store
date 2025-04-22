import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import '../css/app.css';
import '@fontsource/poppins/300.css';  // Light
import '@fontsource/poppins/400.css';  // Regular
import '@fontsource/poppins/500.css';  // Medium
import '@fontsource/poppins/600.css';  // Semi-bold
import '@fontsource/poppins/700.css';  // Bold

const app = createApp(App);
app.use(router);
app.mount('#app');
