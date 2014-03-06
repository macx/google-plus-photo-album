# Google+ Photo Album

PHP Class to fetch and cache a specific Google+ Photo Album.

This Class is in development. Feel free to fork and help!

## Installation

Try [Composer](https://getcomposer.org/) to use the Class in your project:

```sh
$ composer require "macx/google-plus-photo-album"
```

## Usage

```php
$images = new GooglePlusPhotoAlbum();
$images->setUserId('102458928073783517690');
$images->setAlbumId('5857194229946030081');
$album = $images->getAlbum();

echo '<pre>' . print_r($album, true) . '</pre>';
```

ToDo: Tests, better Documentation, Caching ;-)
