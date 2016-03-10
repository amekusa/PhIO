# Philes: Object-oriented filesystem library for PHP
+ Consistent & clear API
+ Encapsulates bothersome
+ Less typing, but also readable
+ Well tested & documented

## Install
Install via Composer.

```sh
composer require amekusa/philes
```

## Examples
Assumed directory structure:

```sh
/
└── srv
    └── http
        ├── favicon.svg
        ├── index.html
        ├── script.js
        └── style.css
```

### Iterate over files in a directory

```php
use amekusa\philes\Directory;

$dir = new Directory('/srv/http');
foreach ($dir as $file) {
	echo $file->getPath() . "\n";
}
```

This code results:

```
/srv/http/favicon.svg
/srv/http/index.html
/srv/http/script.js
/srv/http/style.css
```

### Filter files

```php
use amekusa\philes\Directory;
use amekusa\philes\Filter;

$dir = new Directory('/srv/http');
$dir->addFilter(new Filter('s*.*s'));

foreach ($dir as $file) {
	echo $file->getPath() . "\n";
}
```

This code results:

```
/srv/http/script.js
/srv/http/style.css
```

You can also use regular expression like this:

```php
use amekusa\philes\Directory;
use amekusa\philes\RegexFilter;

$dir = new Directory('/srv/http');
$dir->addFilter(new RegexFilter('/\.[a-z]{3}$/'));

foreach ($dir as $file) {
	echo $file->getPath() . "\n";
}
```

This code results:

```
/srv/http/favicon.svg
/srv/http/style.css
```
