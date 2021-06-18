const { _ } = require("core-js");

module.exports = {
  pages: {
    index: {
      title: 'Roverlay Extension',
      entry: 'src/overlay.js',
      template: 'public/video_overlay.html',
      filename: 'video_overlay.html'
    }
  }
}