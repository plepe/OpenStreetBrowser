Each category can define a list of filters. This is an additional JSON value with the key "filter", e.g.:

```json
{
    "query": {
        "13": "nwr[amenity]"
    },
    "filter": {
        "type": {
            "name": "{{ keyTrans('amenity') }}",
            "type": "select",
            "values": {
                "bar": {
                    "nwr[
                },
        }
    }
}
```
This defines a filter with the ID 'type' and the translated name of the key 'amenity'. It's of type 'select' and has several possible values.

Each filter can define the following values:
* name: Name of the filter. String, which can make use of twig functions, e.g. `keyTrans` as in the above example.
* type: A form type, e.g. 'text', 'select', 'radio', 'checkbox'
* values: Possible values. Can either be an array, an object or a html string with several `<option>` tags (even for radio and checkbox). See below for more information.
* valueName: if the values do not have names (resp. an `<option>` without text content), use this twig template to create a each name. Use `{{ value }}` for the current value.
* query: A twig template which builds a query from the selected value (if the value has not a query defined), e.g. `nwr[amenity={{ value }}]`. If not defined the query will be built from `key` (or the filter ID), `op` and the selected value.
* key: If not overridden by query, use this key for searching. If not defined, use the filter's ID. Can also be an array with a list of keys. You can use wildcards too, e.g. "name:*" to query all localized name tags.
* op: operator to use (if not overridden by query):
  * '=' exact match (default)
  * '!=' any value but this
  * '~' regular expression, case sensitive
  * '~i' regular expression, case insensitive
  * '!~', '!~i' regular expression, negated
  * 'has' query in semicolon-separated lists like `cuisine=kebap;noodles`
  * 'has_key_value' query object with a tag with this key
  * 'strsearch' query string parts (e.g. "kai keb" would match "Kaiser Kebap") and query character variants (e.g. "cafe" would match "café").
* show_default: if true, this filter will be shown by default, others need to be added via the select box.
* placeholder: a text which is shown as placeholder (Twig enabled)
* emptyQuery: A Overpass filter query which is added, when no value is selected.

### Values
#### Array
Values can either be an array, e.g. 
```json
[ "bar", "restaurant", ... ]
```

#### Object
Values can be an object, e.g. 
```json
{ "bar":
    {
        "name": "Bar",
        "query": "nwr[amenity=bar]"
    }
}
```
* Name is optional and can be created via `valueName`.
* Query is optional, it can be created from key (or filter id), op and the value.

#### Twig template
Values can be a twig template (string). It has access to the `const` part of the category. It can create a list of options:

```html
{% for k in const %}
<option value="{{ k }}"></option>
{% endfor %}
<option value="restaurant" query="nwr[amenity=restaurant]">Restaurant</option>
```

* Name is generated from text content. If it is empty, it can be created via `valueName`.
* Query is optional, it can be created from key (or filter id), op and the value.
