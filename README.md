To enable the extesion you need two lines 

```
wfLoadExtension( 'IsraelsettlementsAutocomplete' );
$wgPageFormsAutocompletionURLs['israelsettlements'] = 'URL_TO_API_ENDPOINT?action=getisraelsettlements&term=<substr>&format=json';
```
Then, in semantic forms input use it like this: 
```
|input type=tokens|values from url=israelsettlements
```