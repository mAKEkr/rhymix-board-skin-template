import { createApp } from 'vue'
import { createStore } from 'vuex'
import App from './App.vue'

const B2 = createApp(App)
  .use(createStore({

  }))

B2.mount('#app')