require('./bootstrap')

import { createApp } from 'vue'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import CommentsApp from './CommentsApp.vue'

createApp({
    components: {
        CommentsApp
    }
})
.use(VueSweetalert2)
.mount('#app')
