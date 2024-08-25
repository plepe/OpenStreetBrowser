module.exports = {
  id: 'config',
  appInit: (app) => {
    app.config = global.config
  }
}
