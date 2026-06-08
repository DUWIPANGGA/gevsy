import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const rc = window._REVERB_CONFIG || {};

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: rc.key || import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: rc.host || import.meta.env.VITE_REVERB_HOST,
    wsPort: rc.wsPort || (import.meta.env.VITE_REVERB_PORT ?? 80),
    wssPort: rc.wssPort || (import.meta.env.VITE_REVERB_PORT ?? 443),
    forceTLS: rc.scheme ? rc.scheme === 'https' : (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: rc.authEndpoint || '/broadcasting/auth',
    auth: {
        withCredentials: true,
        headers: csrfToken ? {
            'X-CSRF-TOKEN': csrfToken,
        } : {},
    },
});
