import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme:{
         themes: {
            light: {
                primary: '#FF5200',
                secondary: '#08F7EB',
                tertiary: '#00a9a0',
                admin: '#06001e'
            },
        },
    }
}

export default new Vuetify(opts)
