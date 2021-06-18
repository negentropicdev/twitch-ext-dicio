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
      displayResolution: null
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
      console.log(auth.token);
      this.tokenString = auth.token;
      this.token = JWT.decode(auth.token);
      console.log(this.token);
    },
    onContext(context, changed) {
      console.log(changed);
      console.log(context);

      if (changed.includes("displayResolution") && context.displayResolution != this.displayResolution) {
        this.displayResolution = context.displayResolution;
        var size = this.displayResolution.split('x');
        var width = parseInt(size[0]);
        var height = parseInt(size[1]);

        this.$emit('resize', width, height);
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
