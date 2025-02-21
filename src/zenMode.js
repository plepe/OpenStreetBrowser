const zenmodeTimeoutPeriod = 2000
let zenmodeTimeout
let zenmodeListener

register_hook('fullscreen-activate', activateZenMode)
register_hook('fullscreen-deactivate', deactivateZenMode)

const showEvents = ['mousemove', 'touchstart']

function activateZenMode () {
  showEvents.forEach(ev =>
    document.querySelector('#map').addEventListener(ev, startZenTimeout)
  )
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
  showEvents.forEach(ev =>
    document.querySelector('#map').removeEventListener(ev, startZenTimeout)
  )
  document.body.classList.remove('zenMode')
}

function startZenMode () {
  document.body.classList.add('zenMode')
}
