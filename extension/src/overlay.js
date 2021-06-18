import Vue from 'vue'
import Overlay from './Overlay.vue'

Vue.config.productionTip = false

new Vue({
  el: '#overlay',
  render: h => h(Overlay),
});
