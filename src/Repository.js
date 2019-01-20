module.exports = class Repository {
  constructor (id, data) {
    this.id = id
    this.data = data

    this.lang = this.data.lang || {}
  }
}
