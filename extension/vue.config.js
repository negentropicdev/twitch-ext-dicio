const { _ } = require("core-js");

module.exports = {
  pages: {
    overlay: {
      title: 'Dicio Overlay',
      entry: 'src/overlay.js',
      template: 'public/overlay.html',
      filename: 'overlay.html'
    },
    config: {
      title: 'Dicio Configuration',
      entry: 'src/config.js',
      template: 'public/config.html',
      filename: 'config.html'
    },
    dashboard: {
      title: 'Dicio Live Dashboard',
      entry: 'src/dashboard.js',
      template: 'public/dashboard.html',
      filename: 'dashboard.html'
    }
  }
}