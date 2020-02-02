module.exports = {
  distance: value => {
    return value.toFixed(0) + 'm'
  },

  area: value => {
    return value.toFixed(0) + 'm²'
  },

  coord: value => {
    return value.lat.toFixed(5) + ' ' + value.lng.toFixed(5)
  }
}
