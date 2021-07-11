import Vue from 'vue'
import Config from './main/Config.vue'

Vue.config.productionTip = false;

new Vue({
  el: '#config',
  render: h => h(Config),
});
