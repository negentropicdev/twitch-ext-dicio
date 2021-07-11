<template>
  <input type="text" ref="input" class="un-console-input" :style="inputStyle"
    @keydown.delete="bs" @keydown.up="up" @keydown.down="down"
    @keypress="keypress" @focus="captureInput" @blur="releaseInput">
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
  props: [
    'disabled'
  ],
  data() {
    return {
      focus: false
    };
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
  computed: {
    inputStyle() {
      return {
        cursor: this.disabled ? 'default' : 'pointer'
      };
    }
  },
  methods: {
    bs: function(evt) {
      if (this.focus) {
        evt.preventDefault();
        //Also captures delete key which we're ignoring
        if (evt.key == 'Backspace') {
          this.$emit('key', 'Backspace');
        }
      }
    },
    
    up: function(evt) {
      if (this.focus) {
        evt.preventDefault();
        this.$emit('key', 'UpArrow');
      }
    },
    
    down: function(evt) {
      if (this.focus) {
        evt.preventDefault();
        this.$emit('key', 'DownArrow');
      }
    },
    
    keypress: function(evt) {
      if (this.focus) {
        evt.preventDefault();
        this.$emit('key', evt.key);
      }
    },
    
    captureInput: function() {
      if (this.disabled) {
        this.$refs.input.blur();
      } else {
        this.focus = true;
        this.$emit('captureInput');
      }
    },
    
    releaseInput: function() {
      console.log('blur');
      this.focus = false;
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
