<template>
  <div></div>
</template>

<style scoped>
.hidden {
  display: none;
}
</style>

<script>

import JWT from 'jsonwebtoken';
import Vector from '../common/vector';

var twitch = window.Twitch.ext;

/*eslint no-unused-vars: ["error", {"args": "none"}]*/

export default {
  id: "Twitch",
  data() {
    return {
      token: null,
      tokenString: null,
      channelId: null,
      clientId: null,
      userId: null,
      displayResolution: null,
      videoResolution: null,
      emitResize: false
    };
  },
  props: [

  ],
  computed: {

  },
  mounted() {
    twitch.onAuthorized(this.onAuthorized.bind(this));
    twitch.onContext(this.onContext.bind(this));
    twitch.onError(this.onError.bind(this));
    twitch.onHighlightChanged(this.onHighlightChanged.bind(this));
    twitch.onPositionChanged(this.onPositionChanged.bind(this));
    twitch.onVisibilityChanged(this.onVisibilityChanged.bind(this));
    twitch.actions.onFollow(this.onFollow.bind(this));
  },
  methods: {
    onAuthorized(auth) {
      //console.log('onAuthorized');
      //console.log(arguments);

      this.tokenString = auth.token;
      this.token = JWT.decode(auth.token);
      this.$emit('auth', this.token, this.tokenString);
    },
    onContext(context, changed) {
      //console.log(arguments);

      if (changed.includes("videoResolution")) {
        //console.log("videoResolution");
        var videoSize = context.videoResolution.split('x');
        this.videoResolution = Vector.create(parseInt(videoSize[0]), parseInt(videoSize[1]));

        if (this.emitResize) {
          this.emitResize = false;
          this.$emit('resize', Vector.clone(this.displayResolution), Vector.clone(this.videoResolution));
        }
      }

      if (changed.includes("displayResolution")) {
        //console.log("displayResolution");
        var displaySize = context.displayResolution.split('x');
        this.displayResolution = Vector.create(parseInt(displaySize[0]), parseInt(displaySize[1]));

        if (this.videoResolution != null) {
          this.emitResize = false;
          this.$emit('resize', Vector.clone(this.displayResolution), Vector.clone(this.videoResolution));
        } else {
          this.emitResize = true;
        }
      }
    },
    onError(err) {

    },
    onHighlightChanged(isHighlighted) {
      //console.log('onHighlightChanged', isHighlighted);
      
    },
    onPositionChanged(position) {
      //console.log('onPositionChanged', arguments);
    },
    onVisibilityChanged(isVisible, context) {
      if (isVisible) {
        var displaySize = context.displayResolution.split('x');
        this.displayResolution = Vector.create(parseInt(displaySize[0]), parseInt(displaySize[1]));

        if (this.videoResolution != null) {
          this.emitResize = false;
          this.$emit('resize', Vector.clone(this.displayResolution), Vector.clone(this.videoResolution));
        } else {
          this.emitResize = true;
        }
      }
    },
    onFollow(didFollow, channelName) {
      //console.log(arguments);
    },
    send(target, contentType, message) {
      //console.log(arguments);

      twitch.send(target, contentType, message);
    },
    listen(target, callback) {
      //console.log(arguments);

      twitch.listen(target, callback);
    },
    unlisted(target, callback) {
      //console.log(arguments);

      twitch.unlisten(target, callback);
    },
    follow(channelName) {
      //console.log(arguments);

      twitch.actions.follow(channelName);
    },
    requestIdShare() {
      twitch.actions.requestIdShare();
    },
    minimize() {
      twitch.actions.minimize();
    }
  }
}
</script>
