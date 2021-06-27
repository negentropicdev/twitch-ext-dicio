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
      this.tokenString = auth.token;
      this.token = JWT.decode(auth.token);
      this.$emit('auth', this.token, this.tokenString);
    },
    onContext(context, changed) {

      if (changed.includes("videoResolution")) {
        console.log("videoResolution");
        var videoSize = context.videoResolution.split('x');
        this.videoResolution = Vector.create(parseInt(videoSize[0]), parseInt(videoSize[1]));

        if (this.emitResize) {
          this.emitResize = false;
          this.$emit('resize', Vector.clone(this.displayResolution), Vector.clone(this.videoResolution));
        }
      }

      if (changed.includes("displayResolution")) {
        console.log("displayResolution");
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

    },
    onPositionChanged(position) {

    },
    onVisibilityChanged(isVisible, context) {

    },
    onFollow(didFollow, channelName) {

    },
    send(target, contentType, message) {
      twitch.send(target, contentType, message);
    },
    listen(target, callback) {
      twitch.listen(target, callback);
    },
    unlisted(target, callback) {
      twitch.unlisten(target, callback);
    },
    follow(channelName) {
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
