var mapcss_category_cache = {};
function get_mapcss_category(repo, id, branch) {
  if(!branch)
    branch = "master";

  if(!(repo in mapcss_category_cache))
    mapcss_category_cache[repo] = {};

  if(!(id in mapcss_category_cache[repo]))
    mapcss_category_cache[repo][id] = {}

  if(!(branch in mapcss_category_cache[repo][id]))
    mapcss_category_cache[repo][id][branch] = new mapcss_Category(repo, id, branch);

  return mapcss_category_cache[repo][id][branch];
}

function mapcss_Category(repo, id, branch) {
  this.repo = get_category_repository(repo, branch);
  this.id = id;

  ajax_json("mapcss_category_load", {
    repo: this.repo.id,
    id: this.id,
    branch: this.repo.branch
  }, this.load.bind(this));
}

mapcss_Category.prototype.load = function(data) {
  this._data = data;
  alert(JSON.stringify(this._data));
}
