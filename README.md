# Import

Import data from different sources nested. Every level of nesting is a new source only with the data from all previous levels.

Basic json configuration:

```json
{
  "id": "import_1",
  "halt-on-errors": false,
  "source": {},
  "mapping": {},
  "validate": {}
}
```

* **id** - id don't know, but maybe handy when imports can have children.
* **halt-on-errors** - if there is a validation error or server error the system halts. When set to false the system continues to import the rest of the data.
* **source** - the source of the external data. See next section.
* **mapping** - the mapping of the data. See the section after section Source :P.
* **validate** - the validation of the data. See section Validate:P.

## source
**__All data is going to be converted in a tree xml structure. This meant there is a 'root' element and arrays are converted in items.__**

```json
{
  "_type": "guzzle",
  "id": "ds1",
  "host": "http://localhost:8080/data/data-root.csv",
  "contentType": "text/csv",
  "selector": "//root/*", // select all items
  "validate": {},
  "mapping": {},
  "children": []
}
```
Generate a source, in this example, that is using guzzle to reach the remote location (localhost in this example) and the data-root.csv file is the content type.

You have several source types:
* Ftp
* Http
* Guzzle
* File
* Sql

and you have several content types:
* text/csv
* text/xml
* application/json
* application/xml

The data-root.csv file is a csv file with the following structure:
```csv
id,name,last name,active
123123,olaf,true
```
**The csv data is also going to be converted to xml. Then you can select the desirable elements with Xpath. To select this item you can use //item[name='olaf'], or //root/* and get all items. With this you can also use mustache templates.*

```xml 
<root>
  <item>
    <id>123123</id>
    <name>olaf</name>
    <lastName>mudde</lastName>
    <active>true</active>
  </item>
</root>
```
And this source can also have child sources. The child sources are the same as every source but the data gathered from the parent source is going to be used to generate the child source.

```json
{
  "_type": "guzzle",
  "id": "ds1",
  ...
  "children": [
    {
      "_type": "guzzle",
      "id": "ds2",
      ...
      "mapping": {},
      "children": [
        {...}
      ]
    }, 
    {...}, 
  ]
}
```
## mapping
The selected items are going to be mapped to the data structure. Mappings are used in sources and in the importer.
```json
"mapping": {
  "id": "{{ import_1.id }}",
  "name": "{{ import_1.name | lower }} {{ import_1.lastName | lower }}",
  "active": "{{ import_1.active }}"
}
```
Create your own export from the source file(s).

* basic structure:`"<key>": "<template>"`
* key - whatever you want. What's in the name, well it's very important how we name things cause else the world is going to be a mess. Not that is now so pretty but is is better than 50 years ago. Even with all te wars that are going on in the world. Somebody is making money . . . . i digressed.
* template -`{{ <source.id>.<data.fieldName> | <command:optional> | <command:optional>  }}`
* source.id - your chosen id
* data.fieldName - the given name by the external source
* commands - mustache commandos:
  * lower - to lowercase
  * upper - yo uppercase
  * more to come :)

## validate

Validate the mapping created.
```json
"active": [{
  "_type": "isTrue"
}],
```
If there is a validation error the system halts. When set to false the system continues to import the rest of the data.
A validation error is a error that is thrown by the validation engine. The validation engine is a part of the system. It - must eventually - be matching the validation structure of [Symfony Validator](https://symfony.com/doc/current/validation.html).