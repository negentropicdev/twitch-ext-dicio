import Vue from 'vue'
import Dashboard from './main/Dashboard.vue'

Vue.config.productionTip = false;

new Vue({
  el: '#dashboard',
  render: h => h(Dashboard),
});
