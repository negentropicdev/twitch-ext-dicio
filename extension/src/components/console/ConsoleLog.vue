<template>
  <div class="un-console-log">
    <div class="un-console-entry" v-for="item in entries" :key="item.id" v-html="item.entry"></div>
  </div>
</template>

<style scoped>
.un-console-log {
  width: 100%;
}

.un-console-entry {
  width: 100%;
}
</style>

<script>


export default {
  name: 'ConsoleLog',
  components: {},
  props: [
    'length',
    'defaultColor',
  ],
  data() {
    return {
      id: 0,
      entries: [],
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
    add(text, color) {
      this.entries.push({id: this.id, entry: this.makeSpan(text, color)});
      this.id = this.id + 1;
      
      var max = this.length;
      if (!max) {
        max = 100;
      }
      
      if (this.entries.length > max) {
        this.entries.shift();
      }
    },

    addToLast(text, color) {
      var last = this.entries[this.entries.length - 1];
      last.entry += this.makeSpan(text, color);
    },

    makeSpan(text, color) {
      if (typeof color == 'undefined') {
        color = this.defaultColor;
      }

      if (typeof text == 'undefined') {
        text = '';
      }

      return '<span style="color:' + color + '">' + text + '</span>';
    }
  }
}
</script>
