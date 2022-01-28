require('./bootstrap')

import { createApp } from 'vue'
import { createStore } from 'vuex'

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

import CommentsApp from './CommentsApp.vue'

const store = createStore({
    state () {
      return {
        editing: null,
        replying: null
      }
    },
    mutations: {
        setEditing (state, editing) {
            state.editing = editing
        },
        setReplying (state, replying) {
            state.replying = replying
        },
    }
})

createApp({
    components: {
        CommentsApp
    }
})
.use(store)
.use(VueSweetalert2)
.mount('#app')
