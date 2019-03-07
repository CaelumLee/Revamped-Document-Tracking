window.Vue = require('vue');
window.axios = require('axios');

Vue.component('data-counter', require('./components/DataCounter.vue').default);
Vue.component('bar-chart', require('./components/BarChart.vue').default);

new Vue({
    el: '#main',
});