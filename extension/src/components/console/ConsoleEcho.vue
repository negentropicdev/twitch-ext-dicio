<template>
  <div class="un-console-echo" :style="echoStyle">{{ echoText }}</div>
</template>

<style scoped>
.un-console-echo {
  width: 100%;
}
</style>

<script>
export default {
  name: 'ConsoleEcho',
  components: {},
  props: [
    'disabled',
    'text',
    'captured',
    'echoColor',
  ],
  data() {
    return {
      prompt: '>',
      cursor: '_',
      blink: 300,
      timer: 0,
      toggle: true
    };
  },
  beforeCreate() {},
  created() {},
  beforeMount() {},
  mounted() {
    this.timer = setTimeout(this.toggleCursor.bind(this), this.blink);
  },
  beforeUpdate() {},
  updated() {},
  beforeDestroy() {
    clearTimeout(this.timer);
  },
  destroyed() {},
  computed: {
    echoStyle() {
      var s = {
        display: (this.disabled ? 'none' : 'block'),
        color: this.echoColor,
      };
      
      return s;
    },
    
    cursorVisible() {
      return (this.captured && this.toggle);
    },
    
    echoText() {
      return this.prompt + this.text + (this.cursorVisible ? this.cursor : '');
    }
  },
  methods: {
    toggleCursor() {
      this.toggle = !this.toggle;
      this.timer = setTimeout(this.toggleCursor.bind(this), this.blink);
    }
  }
}
</script>
