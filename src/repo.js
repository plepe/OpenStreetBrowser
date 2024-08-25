module.exports = {
  id: 'repo',
  appInit (app) {
    if ('repo' in app.state.current) {
      global.mainRepo = app.state.current.repo
    }

    app.state.on('get', state => {
      if (global.mainRepo) {
        state.repo = global.mainRepo
      }
    })
  }
}
