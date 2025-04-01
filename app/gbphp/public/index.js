import { createApp } from 'vue'
import '@babel/polyfill'
import appMain from './scripts/main'
import './style/style.css'

const app = createApp(appMain);
app.config.debug = true;
app.config.devtools = true;

app.mount('#app');