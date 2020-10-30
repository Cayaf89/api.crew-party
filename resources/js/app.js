/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.toastr = require('toastr');
toastr.options.toastClass = 'toastr';
toastr.options.positionClass = 'toast-bottom-right';
require('./bootstrap');

window.Vue = require('vue');
import VuejsDialog from 'vuejs-dialog';
import Vuex from "vuex";
import 'vuejs-dialog/dist/vuejs-dialog.min.css';

Vue.use(Vuex);
Vue.use(VuejsDialog, {
    html: true,
    loader: false,
    okText: 'Valider',
    cancelText: 'Annuler',
    animation: 'bounce',
});

window.Store = new Vuex.Store({
    state: {
        user: {},
        modal: {}
    },
    mutations: {
        setUser: function (state, data) {
            state.user = data;
        },
        setModal: function (state, data) {
            state.modal[data.type] = data.value;
        },
    }
})

if (window.user) {
    Store.commit('setUser', window.user);
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue ->
 * <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0],
// files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
