import lodash from 'lodash'
import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window._ = lodash

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = Pusher

// Supported real-time drivers: 'reverb' (self-hosted, Pusher protocol compatible) and 'pusher'
if (['pusher', 'reverb'].includes(config.broadcasting)) {
    const isReverb = config.broadcasting === 'reverb'

    window.Echo = new Echo({
        broadcaster: isReverb ? 'reverb' : 'pusher',
        cluster: config.broadcastingCluster || undefined,
        key: config.broadcastingKey,
        wsHost: config.broadcastingHost || undefined,
        wsPort: config.broadcastingPort || undefined,
        wssPort: config.broadcastingPort || undefined,
        forceTLS: isReverb ? !!config.broadcastingTls : false,
        enabledTransports: ['ws', 'wss'],
    })
}
