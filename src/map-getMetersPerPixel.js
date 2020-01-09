function getMetersPerPixel () {
  return 40075016.686 * Math.abs(Math.cos(this.getCenter().lat / 180 * Math.PI)) / Math.pow(2, this.getZoom() + 8)
}

module.exports = getMetersPerPixel
