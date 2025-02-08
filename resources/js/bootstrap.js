import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import '../scss/app.scss'

import 'aos/dist/aos.css'
import AOS from 'aos'
AOS.init()

import axios from 'axios'
window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
