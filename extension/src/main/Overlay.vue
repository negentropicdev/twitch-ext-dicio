<template>
  <div ref="app" id="app" :style="appStyle">
    <Twitch ref="twitch" @resize="resize" @auth="auth" @hover="twitchHover"></Twitch>

    <div ref="container" id="container" :style="overlayStyle">

      <Console
        ref="console"
        width="480"
        height="270"
        style="position: absolute; top: 15px; left: 165px;"
        @command="command"
        v-show="consoleVisible"
        :autohide="autohide"
        :slideIn="slideIn"
        :ignoreHover="controlActive"
      ></Console>
      
      <Joypad
        ref="movepad"
        :offset="{ x: 82, y: 90 }"
        :autocenter="true"
        :knobRadius="25"
        :outerRadius="75"
        :scale="scale"
        @change="changeMove"
        @active="doControlActive"
      ></Joypad>
      <Joypad
        ref="lookpad"
        :offset="{ x: 728, y: 90 }"
        :autocenter="false"
        :knobRadius="25"
        :outerRadius="75"
        :scale="scale"
        @change="changeLook"
        @active="doControlActive"
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
}
</style>

<script>

const extName = 'Dicio';

import Joypad from '../components/Joypad';
import Twitch from '../components/Twitch';
import Console from '../components/Console';
import axios from 'axios';

/*eslint no-unused-vars: ["off", {"args": "none"}]*/

var gpTimeout = 50;
var colorRE = new RegExp('^#[0-9a-fA-F]{3}$');

var roverlayURL = 'https://roverlay.dbommarito.com/api/';

//Change Roverlay API base URL to test environment if appropriate
if (location.hostname == 'localhost' || location.hostname == '127.0.0.1') {
  roverlayURL = 'http://localhost:8082/api/';
}

function logFocus() {
  console.log(document.activeElement);
}

export default {
  name: 'App',
  components: {
    Twitch,
    Joypad,
    Console,
  },
  data: function() {
    return {
      showConsole: true,
      pads: [],
      source: {
        size: {
          x: 1920,
          y: 1080
        }
      },
      overlay: { // From OBS etc
        size: { //vector
          x: 810,
          y: 300
        },
        position: {
          x: 0,
          y: 400
        }
      },
      scale: 1,
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
      update: null,
      user: null,
      userUnlinked: false,
      logAuthResult: true,
      autohide: false,
      slideIn: false,
      curControl: null,
      controlActive: false,
      token: null,
      tokenStr: null,
    }
  },
  mounted() {
    this.$refs.console.add('Initializing ' + extName + ' extension...');

    this.pads = [
      this.$refs.movepad,
      this.$refs.lookpad
    ];

    this.handler.connected = this.gamepadconnected.bind(this);
    this.handler.disconnected = this.gamepaddisconnected.bind(this);
    this.handler.mousemove = this.mousemove.bind(this);
    this.handler.mouseup = this.mouseup.bind(this);

    addEventListener("gamepadconnected", this.handler.connected);
    addEventListener("gamepaddisconnected", this.handler.disconnected);

    document.documentElement.addEventListener("mousemove", this.handler.mousemove, true);
    document.documentElement.addEventListener("mouseup", this.handler.mouseup, true);
    document.documentElement.addEventListener("mouseexit", this.handler.mouseup, true);

    this.$refs.console.addLn(' Done.');
    this.$refs.console.add('Waiting for authorization...');

    //setInterval(logFocus, 1000);
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
        transform: "scale(" + this.scale + ")",
      };
    },
    overlayStyle() {
      return {
        width: this.overlay.size.x + "px",
        height: this.overlay.size.y + "px",
        left: this.overlay.position.x + "px",
        top: this.overlay.position.y + "px"
      };
    },
    consoleVisible() {
      return this.showConsole;
    }
  },
  methods: {

    /** Twitch functionality */
    auth(token, tokenStr) {
      if (this.user == null) {
        axios({
          method: 'get',
          url: roverlayURL + 'ext/auth',
          headers: {
            'Authorization': 'Bearer ' + tokenStr
          }
        })
        .then(this.userAuthResponse.bind(this, token, tokenStr))
        .catch(this.userAuthError.bind(this));
      }
    },

    userAuthError(err) {
      //console.log(err);
      if (err.response) {
        if (err.response.status == 403) {
          this.$refs.console.addLn(' FAILED!', '#E82');
          this.$refs.console.addLn('Twitch ID sharing required.');
          this.userUnlinked = true;
          this.$refs.console.enable();
          this.$refs.console.addLn();
          this.$refs.console.addLn('Type "auth" to begin grant.');
          this.$refs.twitch.requestIdShare();
          setTimeout(this.hideConsole.bind(this), 3000);
        }
      }
    },

    userAuthResponse(token, tokenStr, response) {
      var data = response.data;

      if (data.status != 'OK' || data.user == null) {
        //console.log(data.err);
        if (this.logAuthResult) {
          this.$refs.console.addLn(' FAILED!', '#E82');
        }
      } else {
        this.user = data.user;
        if (this.logAuthResult) {
          this.$refs.console.addLn(' Success.', '#3D6');
        }

        this.token = token;
        this.tokenStr = tokenStr;

        setTimeout(this.checkUser.bind(this), 0);
      }

      this.logAuthResult = false;
    },

    checkUser() {
      if (this.user.display_name == null) {
        if (this.user.user_id == null) {
          this.$refs.console.add('Requesting access to Twitch credentials...');
          this.logAuthResult = true;
          this.$refs.twitch.requestIdShare();
        } else {
          this.$refs.console.add('Waiting to retrieve user info...');
        }
      } else {
        this.$refs.console.addLn();
        this.$refs.console.add('Welcome, ');
        this.$refs.console.add(this.user.display_name, '#35D');
        this.$refs.console.addLn('!');
        this.$refs.console.addLn();
        this.$refs.console.addLn('You can now mouse in and out of this area to view the console.');
        this.$refs.console.addLn('If you interact with the console you can click outside of it to hide the console again.');
        this.$refs.console.enable();
        this.autohide = true;

        setTimeout(this.hideConsole.bind(this), 3000);
      }
    },

    resize(display, video) {
      this.scale = Math.min(
        display.x / this.source.size.x,
        display.y / this.source.size.y
      );
    },

    /** Virtual console functionality */

    hideConsole() {
      this.autohide = true;
      this.$refs.console.hover = false;
    },

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

      //console.log(cmds);

      var handlers = [];

      for (i = 0; i< cmds.length; ++i) {
        var handler = this.findAndVerifyHandler(cmds[i]);
        if (handler == null) {
          continue;
        }

        if (handler.valid == false) {
          this.handlerError(handler.status);
          return;
        } else {
          handlers.push(handler);
        }
      }

      var supressOkay = false;

      for (i = 0; i < handlers.length; ++i) {
        handlers[i].func();
      }
    },
    findAndVerifyHandler(cmd) {
      switch (cmd[0]) {
        case 'console':
          return this.consoleHandler(cmd);
        
        case 'status':
          return this.statusHandler(cmd);

        case 'auth':
          return this.authHandler(cmd);

        case 'look':
          return this.simpleFunc(this.lookCmd);

        case 'hide':
          return this.simpleFunc(this.hideCmd);

        default:
          this.backendCmd(cmd);
          return null;
      }
    },
    backendCmd(cmd) {
      axios({
          method: 'post',
          url: roverlayURL + 'console/command',
          data: {
            cmd: cmd
          },
          headers: {
            'Authorization': 'Bearer ' + this.tokenStr
          }
        })

        .then(function(ret) {
          if (ret.data.status == 'OK') {
            this.doAsyncActions(ret.data.console.action);
          } else {
            this.$refs.console.addLn('Error handling command: ' + ret.data.msg, '#ED6');
          }
        }.bind(this))

        .catch(function(err) {
          return {
            valid: false,
            status: 'Invalid command'
          };
        }.bind(this));
    },
    doAsyncActions(actions) {
      if (actions[0].delay > 0) {
        var delay = actions[0].delay;
        actions[0].delay = 0;
        setTimeout(this.doAsyncActions.bind(this, actions), delay);
      } else {
        switch (actions[0].type) {
          case 'text':
            this.$refs.console.add(actions[0].text, actions[0].color);
            if (actions[0].eol) {
              this.$refs.consolee.addLn();
            }
            break;
        }

        actions.shift();
        if (actions.length > 0) {
          this.doAsyncActions(actions);
        }
      }
    },
    authHandler(cmd) {
      return {
        valid:true,
        func: this.doAuthGrant.bind(this)
      };
    },
    doAuthGrant() {
      if (!this.userUnlinked) {
        this.$refs.console.addLn('You\'re already granted, you silly goose!');
        return;
      }

      this.$refs.console.addLn('Requesting ID grant for ' + extName + '.');
      this.$refs.console.addLn('Click Grant in the prompt to use ' + extName);
      this.$refs.twitch.requestIdShare();
    },
    consoleHandler(cmd) {
      switch (cmd[1]) {
        case 'color':
          if (colorRE.test(cmd[2])) {
            return {
              valid: true,
              func: this.$refs.console.setEchoColor.bind(this.$refs.consolee, cmd[2])
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
              func: this.$refs.console.setDefaultColor.bind(this.$refs.consolee, cmd[2])
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
      this.$refs.console.addLn('Unknown input: ' + status, '#F40');
    },
    simpleFunc(func) {
      return {
        valid: true,
        func: func.bind(this)
      };
    },

    lookCmd() {
      this.$refs.console.addLn('YOU ARE IN A MAZE OF TWISTY PASSAGES, ALL ALIKE.');
    },

    hideCmd() {
      this.$refs.console.hide();
    },

    /** Controls functionality */
    doControlActive(el) {
      this.controlActive = true;
      this.curControl = el;
    },

    mousemove (evt) {
      if (this.curControl != null) {
        this.curControl.mousemove(evt);
      }

      evt.preventDefault();
    },
    mouseup() {
      if (this.curControl != null) {
        this.curControl.release();
      }

      this.controlActive = false;
      this.curControl = null;
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
    }
  }
}
</script>

