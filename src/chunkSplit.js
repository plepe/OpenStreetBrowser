module.exports = function chunkSplit (data, size=1000) {
  let result = []

  for (let i = 0; i < data.length; i += size) {
    result.push(data.slice(i, i + size))
  }

  return result
}
