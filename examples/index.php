<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>google plus photo album</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php

// Loading the Class, wherever you have saved it
require '../GooglePlusPhotoAlbum.php';

// Get Your Album by providing your Google+ or
// Picasa User ID (it's the same) and the Album ID
// you want to display
$album = GooglePlusPhotoAlbum\AlbumFactory::getAlbum(array(
  'id'     => '5857194229946030081',
  'userId' => '102458928073783517690'
));

?>

<?php if($album && ($album->getImageCount() > 0)): ?>
  <section class="m-gallery">
    <h1><?php echo $album->getTitle(); ?></h1>

    <?php

      $images = $album->getImages();

      foreach($images as $image) {
        echo 'A';
      }
      echo '<pre style="background: orange; color: white;">' . print_r($images, true) . '</pre>';
      // foreach($images->images as $image) {
      //   echo 'X';
      // }
      #oreach($images->images as $image) {
       # echo $image->getTitle();
      #}
    ?>
    <ul>
    <?php foreach($album->getImages() as $image): ?>
      <li>
          <div class="m-gallery__box">
          <figure>
            <?php
             /*
            <img src="<?php echo $image['thumbnails']['s200-c']; ?>" srcset="<?php echo $image['thumbnails']['s200-c']; ?> 1x, <?php echo $image['thumbnails']['s400-c']; ?> 2x" alt="<?php echo $image['title']; ?>">
            */ ?>
            <figcaption><?php echo $album->getSummary(); ?></figcaption>
          </figure>

          <div>
            Dimensions: <?php echo $image->width; ?> x <?php echo $image->height; ?> Pixel<br>
            Size: <?php echo $image->size; ?> bytes<br>
            Comments: 0<br>
            Viewed: 0
          </div>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>

<!-- Do not include the following line in your code, it's for developing and testing purposes of this class. -->
<script src="http://localhost:35729/livereload.js"></script>

</body>
</html>
