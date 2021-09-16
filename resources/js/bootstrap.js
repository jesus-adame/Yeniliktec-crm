import { default as Swal } from 'sweetalert2';

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.moment = require('moment');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.numeral = require('numeral');

window.numeral.defaultFormat('0,0.00');

window.dragula = require('dragula');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

window.__alert_notification = (message) => {
    return Swal.fire({
        icon: 'success',
        title: message,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    })
}

window.__alert_axios_errors = (response) => {
    let text = '';
    let message = 'Ocurrió un error';

    if (response.status == 422) {
        let errors = Object.values(response.data.errors);

        message = response.data.message;
        errors.forEach(error => text += `<p>${error[0]}</p>`);
    }

    Swal.fire({
        icon: 'error',
        title: message,
        html: text,
    });
}