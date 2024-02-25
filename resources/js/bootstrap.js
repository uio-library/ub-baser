import 'core-js/stable'
import 'regenerator-runtime/runtime'

import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VTooltip from "v-tooltip";

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('selectize')

require('bootstrap')
require('@openfonts/pt-sans_all')

// require('selectize');

require('datatables.net')
require('datatables.net-dt')
require('datatables.net-bs4')
require('./include/dataTables.fixedHeader')
require('datatables.net-buttons')

require('bootstrap-select')
require('bootstrap-select/dist/js/i18n/defaults-nb_NO.js')

require('selectize')

require('tooltipster')
require('tooltipster/dist/css/tooltipster.bundle.min.css')
require('tooltipster/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css')

Vue.use(VTooltip)

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

let csrfToken = document.head.querySelector('meta[name="csrf-token"]')

if (csrfToken) {
  csrfToken = csrfToken.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
  csrfToken = null
}

const http = axios.create({
  timeout: 10000,
  headers: {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": csrfToken,
  },
});

Vue.use(VueAxios, http)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

export default Vue
