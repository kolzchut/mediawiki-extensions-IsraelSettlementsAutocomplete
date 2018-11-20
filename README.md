To enable the extesion you need two lines
```
wfLoadExtension( 'IsraelSettelmentsAutocomplete' );
$wgPageFormsAutocompletionURLs['israelsettelments'] = 'URL_TO_API_ENDPOINT?action=getisraelsettelments&term=<substr>&format=json';
```
Then, in semantic forms input use it like this: 
```
|input type=tokens|values from url=israelsettelments
```