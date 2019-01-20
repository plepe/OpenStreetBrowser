const getPathFromJSON = require('../src/getPathFromJSON')
const assert = require('assert')

describe('getPathFromJSON', function () {
  it('const', function () {
    assert.deepEqual(
      getPathFromJSON('const', { const: { 'foo': 'foo', 'bar': 'bar' } }),
      { 'foo': 'foo', 'bar': 'bar' }
    )
  })

  it('const.x', function () {
    assert.deepEqual(
      getPathFromJSON('const.x', { const: { x: { 'foo': 'foo', 'bar': 'bar' } } }),
      { 'foo': 'foo', 'bar': 'bar' }
    )
  })

  it('const.y (not exist)', function () {
    assert.deepEqual(
      getPathFromJSON('const.y', { const: { x: { 'foo': 'foo', 'bar': 'bar' } } }),
      undefined
    )
  })
})
