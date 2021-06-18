<template>
  <div class="un-console-scanlines" ref="scanlines"></div>
</template>

<style scoped>
.un-console-scanlines {
  position: absolute;
  top: 0;
  left: 0;

  height: 100%;
  width: 100%;

  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH4wgYEhgf6WgVjwAAABl0RVh0Q29tbWVudABDcmVhdGVkIHdpdGggR0lNUFeBDhcAAAAMSURBVAjXY2CAgn8AAQgA/yEMS1sAAAAASUVORK5CYII=);
  background-repeat: repeat;

  opacity: 0.2;

  pointer-events: none;

  z-index: 1;
}
</style>

<script>
export default {
  name: "ConsoleScanlines",
  components: {},
  props: [],
  data() {
    return {};
  },
  beforeCreate() {},
  created() {},
  beforeMount() {},
  mounted() {
    window.addEventListener("resize", this.resize.bind(this));
  },
  beforeUpdate() {},
  updated() {},
  beforeDestroy() {},
  destroyed() {},
  computed: {},
  methods: {
    resize() {
      var i = this.$refs.scanlines;
      var p = i.parentNode;
      i.style.width = p.clientWidth;
    },
  },
};
</script>
