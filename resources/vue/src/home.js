'use strict';

import Vue from 'vue';
import VueRouter from 'vue-router';
import HomeApp from '../HomeApp.vue';
import AdsList from '../AdsList.vue';
import AdsForm from '../AdsForm.vue';

Vue.use(VueRouter);

const routes = [
    {path: '/', redirect: '/ads-list'},
    {path: '/ads-list', name: 'Все объявления', component: AdsList},
    {path: '/ads-form', name: 'Добавить новые', component: AdsForm}
]

const router = new VueRouter({
    routes
})

new Vue({
    router,
    el: '#app',
    components: {
        HomeApp
    }
});