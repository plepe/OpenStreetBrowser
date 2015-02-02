function object_ol4pgm(feature) {
  this.inheritFrom=geo_object;
  this.inheritFrom();
  this.type="object_ol4pgm";

  this.feature = feature;
  this.name = this.feature.getProperties().results[0]['text'] || lang("unnamed");

  this.geo = function() {
    return [this.feature];
  }

  this.geo_center = function() {
    return [this.feature]; // todo
  }
}
