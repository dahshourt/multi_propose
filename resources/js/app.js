
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'

Vue.use(VueRouter);
import Vue from 'vue';
import VueGates from 'vue-gates';

Vue.use(VueGates);
import VueProgressBar from 'vue-progressbar'
import Swal from 'sweetalert2'
import Gate from './Gate';
Vue.prototype.$gate= new Gate(window.user)
window.Swal=Swal
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.Toast=Toast
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '9px'
})
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
//Vue.component('dahshbord', require('./components/DahshbordComponent.vue').default);
//Vue.component('profile', require('./components/ProfileComponent.vue'));

Vue.component(
    'NotFound',
    require('./components/Notfound').default
);

import dahshboard from './components/DahshbordComponent';
import profile from './components/ProfileComponent';
import users from './components/UsersComponent';
import passport from './components/PassportComponent';
import  notfound from './components/Notfound';
const routes = [
    { path: '/dahshbord', component: dahshboard },
    { path: '/profile', component: profile },
    { path: '/users', component: users },
    { path: '/passport', component: passport },
    { path: '*', component: notfound },

]
window.Fire=new Vue();
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})
const app = new Vue({
    el: '#app',
    router,
    data:{
        search: ''

    },
    methods:{
        searchit:_.debounce(()=>{
            Fire.$emit('searchuser');
        },1000)

    }
});
