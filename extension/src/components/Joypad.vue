<template>
  <div ref="joypad" :style="padStyle" @mousedown="mousedown" :title="this.padTitle"></div>
</template>

<style scoped>
div {
  position: absolute;
  width: 50px;
  height: 50px;
  margin-left: -25px;
  margin-top: -25px;
  background: radial-gradient(#666, #111);
  border-radius: 50%;
  border: 4px solid;
  box-sizing: border-box;
}

div:hover {
  cursor: pointer;
}
</style>

<script>
import Vector from '../common/vector';

export default {
  id: "Joypad",
  data() {
    return {
      pos: {
        cur: {
          x: 0,
          y: 0,
        },
        start: {
          x: 0,
          y: 0,
        },
        diff: {
          x: 0,
          y: 0,
        },
      },
      mouse: {
        down: {
          x: 0,
          y: 0,
        },
        cur: {
          x: 0,
          y: 0,
        },
        diff: {
          x: 0,
          y: 0,
        },
        isDown: false,
      },
      scale: {
        x: 1,
        y: 1
      },
    };
  },
  props: {
    autocenter: Boolean,
    offset: Object,
    knobRadius: Number,
    outerRadius: Number,
    scaleX: {
      type: Number,
      default: 1
    },
    scaleY: {
      type: Number,
      default: 1
    }
  },
  computed: {
    padStyle() {
      var v = Vector.add(this.curPadOffset, this.offset);
      return {
        left: v.x + "px",
        top: v.y + "px",
        "border-color": this.mouse.isDown ? "red" : "gold",
      };
    },
    padTitle() {
      return "Drag to move. " + (this.autocenter ? "Returns to center when released." : "Click to return to center.");
    },
    maxOffset() {
      return this.outerRadius - this.knobRadius;
    },
    curPadOffset() {
      var p = {
        x: this.pos.cur.x,
        y: -this.pos.cur.y,
      };

      return Vector.scale(p, this.maxOffset);
    },
    invScale() {
      return {
        x: 1 / this.scaleX,
        y: 1 / this.scaleY
      };
    }
  },
  mounted() {
  },
  methods: {
    mousedown(evt) {
      //console.log("mouse down", evt);
      this.mouse.down.x = evt.screenX;
      this.mouse.down.y = evt.screenY;
      this.mouse.isDown = true;

      if (!this.autocenter) {
        this.pos.start = this.pos.cur;
        this.pos.diff = {x: 0, y: 0};
      }
    },
    mousemove(evt) {
      if (!this.mouse.isDown) {
        return;
      }

      //console.log("mouse move", evt);

      this.mouse.cur.x = evt.screenX;
      this.mouse.cur.y = evt.screenY;

      //This also inverts the scaling to get screen pixels to map correctly when the overlay is resized
      this.mouse.diff = Vector.scale2(Vector.sub(this.mouse.cur, this.mouse.down), this.invScale);

      this.pos.diff = Vector.scale({x: this.mouse.diff.x, y: -this.mouse.diff.y}, 1 / this.maxOffset);
      this.pos.cur = Vector.add(this.pos.start, this.pos.diff);

      var length = Vector.length(this.pos.cur);
      if (length > 1) {
        this.pos.cur = Vector.scale(this.pos.cur, 1 / length);
      }

      this.$emit('change', this.pos.cur);
    },
    release() {
      if (!this.mouse.isDown) {
        return;
      }

      //console.log("mouse up", evt);

      this.mouse.isDown = false;

      if (this.autocenter) {
        this.pos.cur = {
          x: 0,
          y: 0
        };
      } else {
        //check if there was no movement (a regular click) and reset to center
        if (this.pos.diff.x == 0 && this.pos.diff.y == 0) {
          //reset to center
          this.pos.cur = {x: 0, y: 0};
        }
      }

      this.$emit('change', this.pos.cur);
    },
    setPos(pos) {
      this.pos.cur.x = pos.x;
      this.pos.cur.y = pos.y;
    }
  },
};
</script>
