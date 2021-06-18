<template>
  
</template>

<style scoped>

</style>

<script>
import Joypad from './roverlay/Joypad';

export default {
  name: 'Roverlay',
  components: {
    Joypad
  },
  props: [
    'bg-url',
    'buttons',
    'pads',
    'mapping'
  ],
  data() {
    return {
      controls: {
        pads: [],
        buttons: []
      },
      values: {
        axes: [],
        buttons: []
      }
    };
  },
  beforeCreate() {},
  created() {},
  beforeMount() {},
  mounted() {},
  beforeUpdate() {},
  updated() {},
  beforeDestroy() {},
  destroyed() {},
  computed: {},
  methods: {
    enable() {

    },
    disable() {

    }
  }
}
</script>
