import axios from 'axios'

const globalMixin = {
  methods: {
    API() {
      return axios.create({
        baseURL: process.env.VUE_APP_SERVER_INSTANCE,
        headers: {
          'Session-Id': localStorage.getItem('session_id'),
        },
        responseType: 'json',
        transitional: {
          silentJSONParsing: false,
        },
      })
    },
  },
}

export default globalMixin
