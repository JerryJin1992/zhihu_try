
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// 引入表单验证插件
import zh from 'vee-validate/dist/locale/zh_CN';
import VeeValidate,{ Validator } from 'vee-validate';
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize('zh', zh);
Vue.use(VeeValidate);

// 引入vue-rsource
let VueResource = require('vue-resource');
Vue.use(VueResource);

let token = document.head.querySelector('meta[name="csrf-token"]');
let api_token = document.head.querySelector('meta[name="api-token"]');
if (token) {
    Vue.http.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

if (api_token) {
    Vue.http.headers.common['Authorization'] = api_token.content;
} else {
    console.error('api token not found');
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('question-follow-button', require('./components/QuestionFollowButton'));
Vue.component('user-follow-button', require('./components/UserFollowButton'));
Vue.component('user-vote-button', require('./components/UserVoteButton'));
Vue.component('tooltip', require('./components/Title'));
Vue.component('send-message-button', require('./components/SendMessage'));

const app = new Vue({
    el: '#app',
    data : {
      id : 'what happened'
    },
    // mounted: function () {
    //     console.log('Vue Instance');
    // },
    created: function () {
        // console.log('怎么没有相应');
    }
});
