module.exports = function wikipediaGetImageProperties (img) { 
  let m = img.src.match(/^https?:\/\/upload.wikimedia.org\/wikipedia\/commons\/thumb\/\w+\/\w+\/([^/]+)/)
  if (m) {
    let file = decodeURIComponent(m[1]).replace(/_/g, ' ')

    return {
      id: file,
      type: 'wikimedia',
      width: img.getAttribute('data-file-width'),
      height: img.getAttribute('data-file-height')
    }
  }
}
