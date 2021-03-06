
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
window.Mark = require('mark.js');

let VueMaterial = require('vue-material');

window.events = new Vue();

Vue.use(VueMaterial);

Vue.material.registerTheme({
    default: {
        primary: 'indigo',
        warn: 'red',
        background: 'white'
    },
    button: {
        primary: 'lime',
        warning: 'red',
        accent: 'teal'
    },
    nav: {
        primary: 'white'
    },
    comment_card: {
        background: 'white'
    }
});

let VueQuillEditor = require('vue-quill-editor');


// mount with global
Vue.use(VueQuillEditor);



window.flash = function(message, type = 'primary', title = 'Important message') {
    //window.ui.showNotification(message, type)

    window.events.$emit('flash', message)
}; // flash new message


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('vmenu', require('./components/Menu.vue'));
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('list', require('./components/list/List.vue'));
Vue.component('vlink', require('./components/Link.vue'));
Vue.component('grid', require('./components/Grid.vue'));
Vue.component('vdialog', require('./components/Dialog.vue'));

Vue.component('list-el-default', require('./components/list/ListElement_Default.vue'));
Vue.component('list-form', require('./components/forms/ListElementForm.vue'));
Vue.component('list-files', require('./components/list/ListFile.vue'));
Vue.component('share', require('./components/list/Share.vue'));

Vue.component('comment-form', require('./components/forms/CommentForm.vue'));

Vue.component('diff-history', require('./components/diffs/ListElementDiff.vue'));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app',
    mounted: function () {
        this.routes = JSON.parse(this.$el.attributes.routes.value);
    },

    data: function() {
        return {
            routes: {}
        };
    }
});

