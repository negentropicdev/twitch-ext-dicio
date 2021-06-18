<template>
  <input type="text" ref="input" class="un-console-input" @keydown.delete="bs" @keydown.up="up" @keydown.down="down" @keypress="keypress" @focus="captureInput" @blur="releaseInput">
</template>

<style scoped>
  .un-console-input {
    position: absolute;
    top: 0;
    left: 0;
    
    border: none;
    caret-color: transparent;
    background: transparent;
    
    height: 100%;
    width: 100%;
    z-index: 3;
  }

  .un-console-input:focus {
    outline: none;
  }
</style>

<script>
export default {
  name: 'ConsoleInput',
  components: {},
  props: [],
  data() {
    return {};
  },
  beforeCreate() {},
  created() {},
  beforeMount() {},
  mounted() {
    window.addEventListener('resize', this.resize.bind(this));
  },
  beforeUpdate() {},
  updated() {},
  beforeDestroy() {},
  destroyed() {},
  computed: {},
  methods: {
    bs: function(evt) {
      evt.preventDefault();
      //Also captures delete key which we're ignoring
      if (evt.key == 'Backspace') {
        this.$emit('key', 'Backspace');
      }
    },
    
    up: function(evt) {
      evt.preventDefault();
      this.$emit('key', 'UpArrow');
    },
    
    down: function(evt) {
      evt.preventDefault();
      this.$emit('key', 'DownArrow');
    },
    
    keypress: function(evt) {
      evt.preventDefault();
      this.$emit('key', evt.key);
    },
    
    captureInput: function() {
      this.$emit('captureInput');
    },
    
    releaseInput: function() {
      this.$emit('releaseInput');
    },
    
    resize: function() {
      var i = this.$refs.input;
      var p = i.parentNode;
      i.style.width = p.clientWidth;
    },
  }
}
</script>
