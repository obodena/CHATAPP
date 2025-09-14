import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configure Echo to use reverb
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.MIX_REVERB_APP_KEY || process.env.VITE_REVERB_APP_KEY,
    host: process.env.MIX_REVERB_HOST || process.env.VITE_REVERB_HOST,
    port: process.env.MIX_REVERB_PORT || process.env.VITE_REVERB_PORT,
    forceTLS: false,
    wsHost: process.env.MIX_REVERB_HOST || window.location.hostname,
    wsPort: process.env.MIX_REVERB_PORT || 8080,
    wssPort: process.env.MIX_REVERB_PORT || 8080,
    encrypted: false,
    enabledTransports: ['ws', 'wss'],
});
