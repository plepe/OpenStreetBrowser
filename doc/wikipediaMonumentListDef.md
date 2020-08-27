This file documents the format of /src/wikipediaMonumentListDef.json

In Wikipedia there are lists of monuments in certain regions, which hold additional information about objects.

For each tag which references the ID of a monument, there's an entry in this file with the reference tag as key. See this list for available tags:

```json
    "ref:at:bda": {
        "de": {
            "wikipedia": "de",
            "searchTemplate": "Denkmalliste Österreich Tabellenzeile",
            "searchIdField": "ObjektID",
            "searchTitle": "Liste der denkmalgeschützten Objekte in",
            "tableColumnDescription": 4,
            "tableColumnImage": 1,
            "tableIdPrefix": "objektid-"
        }
    },
```

* "ref:at:bda": Objects in OpenStreetMap use this tag
* "de": for the language "de", there are lists of monuments in wikipedia
* wikipedia: Which Wikipedia to query for the monument (e.g. "de" for the German wikipedia)
* searchTemplate: A regular expression which matches the Mediawiki Template which is used to create an entry of the list
* searchIdField: Name of field which holds the reference value
* searchTitle: regular expression which matches the titles of the pages where the monument lists are kept
* tableColumnDescription: the number of the column, where the description is shown
* tableColumnImage: the number of the column, where the image is shown
* tableIdPrefix: the table row has an ID, which contains the reference value. Often, this ID is prefixed by a string (e.g. 'object-'). (Inspect the rendered HTML source code)
