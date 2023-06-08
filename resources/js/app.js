import './bootstrap';
import Alpine from 'alpinejs';
import Pusher from 'pusher-js';
import Echo from "laravel-echo"

window.Alpine = Alpine;
Alpine.start();
var chat = document.getElementById('chat');
chat.scrollTop = chat.scrollHeight - chat.clientHeight;






