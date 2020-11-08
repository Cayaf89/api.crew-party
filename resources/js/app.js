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
import CKEditor from '@ckeditor/ckeditor5-vue';
import VueLazyload from 'vue-lazyload';

Vue.use(VueLazyload);
Vue.use(CKEditor);
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
        modal: {
            createUpdateCrew: { show: false, crew_id: null },
            user: { show: false, userId: null }
        },
        updateSideBarCrews: null,
        updateNavBarUser: null,
    },
    mutations: {
        setUser: function (state, data) {
            state.user = data;
        },
        setModal: function (state, data) {
            state.modal[data.type] = data.value;
        },
        setUpdateSideBarCrews: function (state, data) {
            state.updateSideBarCrews = data;
        },
        updateSideBarCrews: function (state) {
            state.updateSideBarCrews();
        },
        setUpdateNavBarUser: function (state, data) {
            state.updateNavBarUser = data;
        },
        updateNavBarUser: function (state) {
            state.updateNavBarUser();
        }
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
const VueUploadComponent = require('vue-upload-component')
Vue.component('file-upload', VueUploadComponent)

Vue.component('loading-button', require('./components/ButtonLoaderComponent.vue').default);

Vue.component(
    'modal-create-update-crew',
    function (resolve) {
        require(['./components/Modals/ModalCreateUpdateCrew.vue'], resolve)
    });

Vue.component(
    'modal-user',
    function (resolve) {
        require(['./components/Modals/ModalUser.vue'], resolve)
    });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components.vue.js');
