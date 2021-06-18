<template>
  <div class="outer-wrapper" ref="outer" :style="outerStyle">
    <ConsoleScanlines
      v-if="!hideScanlines"
    ></ConsoleScanlines>

    <div class="un-console" ref="console" :style="consoleStyle">
      <ConsoleLog
        ref="log"
        :defaultColor="defaultColor"
        :length="historyLength"
      ></ConsoleLog>

      <ConsoleEcho
        ref="echo"
        :captured="captured"
        :disabled="disabled"
        :text="currentInput"
        :echoColor="echoColor"
      ></ConsoleEcho>

      <ConsoleInput
        @captureInput="captureInput"
        @releaseInput="releaseInput"
        @key="key"
      ></ConsoleInput>
    </div>
  </div>
</template>

<style scoped>
.outer-wrapper {
  position: relative;
}

.un-console {
  overflow-x: hidden;
  overflow-y: hidden;
  position: relative;

  overflow-wrap: break-word;
  
  font-family: "Lucida Console", Monaco, monospace;
  box-sizing: border-box;

  background-color: #111;
  opacity: 0.9;
  padding-top: 2px;
}
</style>

<script>

import ConsoleLog from './console/ConsoleLog';
import ConsoleEcho from './console/ConsoleEcho';
import ConsoleScanlines from './console/ConsoleScanlines';
import ConsoleInput from './console/ConsoleInput';

export default {
  name: 'Console',
  components: {
    ConsoleLog,
    ConsoleEcho,
    ConsoleScanlines,
    ConsoleInput,
  },

  props: [
    'width',
    'height',
    'hideScanlines',
    'scrollable',
    'history'
  ],
  
  data: function() {
    return {
      currentInput: '',
      commands: [],
      currentHistory: 0,
      historyLength: 100,
      captured: false,
      disabled: true,
      echoColor: '#2E4',
      defaultColor: '#FFF',
    };
  },

  created() {
    if (this.history != undefined) {
      this.historyLength = this.history;
    }
  },

  mounted() {
    window.terminal = this;
  },
  
  computed: {
    consoleStyle() {
      var style = {
        width: this.width + 'px',
        height: this.height + 'px'
      };

      if (this.scrollable) {
        style['overflow-y'] = 'scroll';
      } else {
        style['overflow-y'] = 'hidden';
      }

      return style;
    },
    outerStyle() {
      return {
        width: this.width + 'px',
        height: this.height + 'px'
      };
    }
  },
  
  methods: {
    key: function(val) {
      this.scroll();
      
      if (this.disabled) {
        return;
      }
      
      switch (val) {
      case 'Backspace':
        this.currentInput = this.currentInput.slice(0, this.currentInput.length - 1);
        break;
      
      case 'UpArrow':
        this.currentHistory = this.currentHistory - 1;
        if (this.currentHistory < -this.commands.length) {
          this.currentHistory = -this.commands.length;
        } else {
          this.currentInput = this.commands[this.commands.length + this.currentHistory];
        }
        break;
        
      case 'DownArrow':
        this.currentHistory = this.currentHistory + 1;
        if (this.currentHistory > 0) {
          this.currentHistory = 0;
        } else if (this.currentHistory == 0) {
          this.currentInput = '';
        } else {
          this.currentInput = this.commands[this.commands.length + this.currentHistory];
        }
        break;
        
      case 'Enter':
        this.$refs.log.add(this.$refs.echo.prompt + this.currentInput, this.echoColor);
        
        this.commands.push(this.currentInput);
        this.$emit('command', this.currentInput);
        
        if (this.commands.length > this.historyLength) {
          this.commands.shift();
        }
        
        this.currentHistory = 0;
        
        this.currentInput = '';
        break;
        
      default:
        this.currentInput += val;
        break;
      }
    },
    
    captureInput() {
      this.captured = true;
    },
    
    releaseInput() {
      this.captured = false;
    },
    
    scroll() {
      setTimeout(function() {this.$refs.console.scrollTop = this.$refs.console.scrollHeight;}.bind(this), 0);
    },
    
    addLine(text, color) {
      this.$refs.log.add(text, color);
      
      this.scroll();
    },
    
    disable() {
      this.disabled = true;
    },
    
    enable() {
      this.disabled = false;
    },
    
    setEchoColor(color) {
      this.echoColor = color;
    },
    
    setDefaultColor(color) {
      this.defaultColor = color;
    },
  },   
}
</script>