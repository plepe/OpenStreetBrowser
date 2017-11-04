function isStringArray (arr) {
  if (!Array.isArray(arr)) {
    return false
  }

  var nonStringElements = arr.filter(function (x) {
    return typeof x !== 'string'
  })

  if (nonStringElements.length) {
    return false
  }

  return true
}

function join (data) {
  for (var k in data) {
    if (isStringArray(data[k])) {
      data[k] = data[k].join('\n')
    } else if (typeof data[k] === 'object') {
      data[k] = join(data[k])
    }
  }

  return data
}

module.exports = {
  join: join
}
