<template>
  <div  ref="app" id="app" :style="appStyle">
    <Twitch ref="twitch" @resize="resize"></Twitch>
    <!--<Console
      ref="console"
      width="640"
      height="480"
      @command="command"
    ></Console>-->
    <div ref="container" id="container" :style="overlayStyle">
      <Joypad
        ref="movepad"
        :offset="{ x: 82, y: 90 }"
        :autocenter="true"
        :knobRadius="25"
        :outerRadius="75"
        :scaleX="size.scale.x"
        :scaleY="size.scale.y"
        @change="changeMove"
      ></Joypad>
      <Joypad
        ref="lookpad"
        :offset="{ x: 728, y: 90 }"
        :autocenter="false"
        :knobRadius="25"
        :outerRadius="75"
        :scaleX="size.scale.x"
        :scaleY="size.scale.y"
        @change="changeLook"
      ></Joypad>
    </div>
  </div>
</template>

<style>
#app {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
}

#container {
  position: absolute;
  /*background-image: url("assets/overlay.png");*/
}
</style>

<script>

import Joypad from './components/Joypad';
import Twitch from './components/Twitch';
//import Console from './components/Console';

var gpTimeout = 50;
var colorRE = new RegExp('^#[0-9a-fA-F]{3}$');

export default {
  name: 'App',
  components: {
    Twitch,
    Joypad,
    //Console,
  },
  data: function() {
    return {
      pads: [],
      size: {
        stream: { //vector
          x: 1920,
          y: 1080
        },
        overlay: { //vector
          x: 640,
          y: 480
        },
        offset: { //vector
          x: 0,
          y: -15
        },
        scale: {
          x: 1,
          y: 1
        }
      },
      move: {
        x: 0,
        y: 0
      },
      look: {
        x: 0,
        y: 0
      },
      last: {
        mx: 0,
        my: 0,
        lx: 0,
        ly: 0
      },
      gamepad: null,
      handler: {
        connected: null,
        disconnected: null,
        mousemove: null,
        mouseup: null
      },
      update: null
    }
  },
  mounted() {
    this.pads = [
      this.$refs.movepad,
      this.$refs.lookpad
    ];

    //this.$refs.console.enable();

    this.handler.connected = this.gamepadconnected.bind(this);
    this.handler.disconnected = this.gamepaddisconnected.bind(this);
    this.handler.mousemove = this.mousemove.bind(this);
    this.handler.mouseup = this.mouseup.bind(this);

    addEventListener("gamepadconnected", this.handler.connected);
    addEventListener("gamepaddisconnected", this.handler.disconnected);

    document.documentElement.addEventListener("mousemove", this.handler.mousemove, true);
    document.documentElement.addEventListener("mouseup", this.handler.mouseup, true);
    document.documentElement.addEventListener("mouseexit", this.handler.mouseup, true);
  },
  beforeDestroy() {
    removeEventListener("gamepadconnected", this.handler.connected);
    removeEventListener("gamepaddisconnected", this.handler.disconnected);

    document.documentElement.removeEventListener("mousemove", this.handler.mousemove);
    document.documentElement.removeEventListener("mouseup", this.handler.mouseup);
    document.documentElement.removeEventListener("mouseexit", this.handler.mouseup);

    if (this.update != null) {
      clearTimeout(this.update);
    }
  },
  computed: {
    appStyle() {
      return {
        "transform-origin": "top left",
        transform: "scale(" + this.size.scale.x + ", " + this.size.scale.y + ")",
      };
    },
    overlayStyle() {
      return {
        width: this.size.overlay.x + "px",
        height: this.size.overlay.y + "px",
        left: this.size.offset.x + "px",
        top: this.size.offset.y + "px"
      };
    }
  },
  methods: {
    command(str) {
      var cmds = str.split(';');
      var i;
      for (i = cmds.length - 1; i >= 0; --i) {
        if (cmds[i] == '') {
          cmds.splice(i, 1);
        } else {
          cmds[i] = cmds[i].trim().split(' ');
        }
      }

      console.log(cmds);

      var handlers = [];

      for (i = 0; i< cmds.length; ++i) {
        var handler = this.findAndVerifyHandler(cmds[i]);
        if (handler.valid == false) {
          this.handlerError(handler.status);
          return;
        } else {
          handlers.push(handler);
        }
      }

      for (i = 0; i < handlers.length; ++i) {
        handlers[i].func();
      }

      this.$refs.console.addLine('OKAY');
    },
    findAndVerifyHandler(cmd) {
      switch (cmd[0]) {
        case 'console':
          return this.consoleHandler(cmd);
        
        case 'status':
          return this.statusHandler(cmd);

        default:
          return {
            valid: false,
            status: cmd[0] + ' command unrecognized.'
          };
      }
    },
    consoleHandler(cmd) {
      switch (cmd[1]) {
        case 'color':
          if (colorRE.test(cmd[2])) {
            return {
              valid: true,
              func: this.$refs.console.setEchoColor.bind(this.$refs.console, cmd[2])
            };
          } else {
            return {
              valid: false,
              status: 'Invalid color param ' + cmd[2]
            };
          }
        
        default:
          return {
            valid: false,
            status: 'Unrecognized parameter ' + cmd[1]
          };
      }
    },
    statusHandler(cmd) {
      switch (cmd[1]) {
        case 'color':
          if (colorRE.test(cmd[2])) {
            return {
              valid: true,
              func: this.$refs.console.setDefaultColor.bind(this.$refs.console, cmd[2])
            };
          } else {
            return {
              valid: false,
              status: 'Invalid color param ' + cmd[2]
            };
          }

        default:
          return {
            valid: false,
            status: 'Unrecognized parameter ' + cmd[1]
          };
      }
    },
    handlerError(status) {
      this.$refs.console.addLine('Unknown input: ' + status, '#F40');
    },
    mousemove (evt) {
      this.pads.forEach(function(pad) {
        pad.mousemove(evt);
      });

      evt.preventDefault();
    },
    mouseup() {
      this.pads.forEach(function(pad) {
        pad.release()
      });
    },
    changeMove(pos) {
      this.move.x = pos.x;
      this.move.y = pos.y;
    },
    changeLook(pos) {
      this.look.x = pos.x;
      this.look.y = -pos.y;
    },
    gamepadconnected(e) {
      console.log("Connected", e);
      this.gamepad = e.gamepad;
      this.update = setTimeout(this.updateGamepad.bind(this), gpTimeout);
    },
    gamepaddisconnected(e) {
      console.log("Disconnected", e);
      this.gamepad = null;
      clearTimeout(this.update);
    },
    updateGamepad() {
      if (this.gamepad == null) {
        return;
      }

      this.gamepad = navigator.getGamepads()[0];

      var mx = this.gamepad.axes[0];
      var my = -this.gamepad.axes[1];
      var lx = this.gamepad.axes[2];
      var ly = -this.gamepad.axes[3];

      var dmx = mx - this.last.mx;
      var dmy = my - this.last.my;
      var dlx = lx - this.last.lx;
      var dly = ly - this.last.ly;

      var changed = Math.abs(dmx) > 0.01 || Math.abs(dmy) > 0.01 || Math.abs(dlx) > 0.01 || Math.abs(dly) > 0.01;

      //console.log({x: this.move.x, y: this.move.y}, this);

      if (changed) {
        //this.$forceUpdate();
        this.$refs.movepad.setPos({x: mx, y: my});
        this.$refs.lookpad.setPos({x: lx, y: ly});

        this.move.x = mx;
        this.move.y = my;
        this.look.x = lx;
        this.look.y = -ly;

        this.last.mx = this.move.x;
        this.last.my = this.move.y;
        this.last.lx = this.look.x;
        this.last.ly = this.look.y;
      }

      this.update = setTimeout(this.updateGamepad.bind(this), gpTimeout);
    },
    resize(width, height) {
      this.size.scale.x = width / this.size.stream.x;
      this.size.scale.y = height / this.size.stream.y;
    }
  }
}
</script>

