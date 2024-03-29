
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');


import router from './routes.js';
import Auth from './auth.js';
import Api from './api.js';
import Echo from 'laravel-echo';

import VueRouter from 'vue-router';

window.Vue = require('vue');
window.api = new Api;
window.auth = new Auth;

Vue.use(VueRouter);

window.Event = new Vue;


window.io = require('socket.io-client');

window.Echo = new Echo({
    auth: {
        headers: {
          Authorization: `Bearer ` + auth.token
        }
      },
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
}); 


// var options = {
//     auth: {
//       headers: {'Authorization': 'Bearer ' + auth.token}
//     }
//   }
//   var socket = io(window.location.hostname + ':6001', options);
  
//   socket.emit('subscribe', {
//     channel: 'chat',
//     auth: options.auth
//   }).on('App\Events\MessagePushed', function(e, channel, data) {
//     this.messages.push({
//         message: e.message.message,
//         user: e.user
//       });
//   });
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('vue-layout', require('./views/Layout.vue').default);
Vue.component('chat-messages', require('./views/ChatForm.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});

