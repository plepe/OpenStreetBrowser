const zenmodeTimeoutPeriod = 2000
let zenmodeTimeout
let zenmodeListener

register_hook('fullscreen-activate', activateZenMode)
register_hook('fullscreen-deactivate', deactivateZenMode)

function activateZenMode () {
  document.querySelector('#map').addEventListener('mousemove', startZenTimeout)
  startZenTimeout()
}

function startZenTimeout () {
  document.body.classList.remove('zenMode')
  if (zenmodeTimeout) {
    global.clearTimeout(zenmodeTimeout)
  }

  zenmodeTimeout = global.setTimeout(startZenMode, zenmodeTimeoutPeriod)
}

function deactivateZenMode () {
  global.clearTimeout(zenmodeTimeout)
  document.querySelector('#map').removeEventListener('mousemove', startZenTimeout)
  document.body.classList.remove('zenMode')
}

function startZenMode () {
  document.body.classList.add('zenMode')
}
