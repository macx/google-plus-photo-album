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

require '../GooglePlusPhotoAlbum.php';

$album = new macx\GooglePlus\GooglePlusPhotoAlbum();

// Set your numeric Google+ or Picasa user id (it's the same):
$album->setUserId('102458928073783517690');

// Set your numeric Album ID:
$album->setAlbumId('5857194229946030081');

$newZealand = $album->getAlbum();

?>

<?php if($newZealand && ($newZealand['images']['total'] > 0)): ?>
  <section class="m-gallery">
    <h1><?php echo $newZealand['title']; ?></h1>

    <ul>
    <?php foreach($newZealand['images']['media'] as $image): ?>
      <li>
          <div class="m-gallery__box">
          <figure>
            <img src="<?php echo $image['thumbnails']['s200-c']; ?>" srcset="<?php echo $image['thumbnails']['s200-c']; ?> 1x, <?php echo $image['thumbnails']['s400-c']; ?> 2x" alt="<?php echo $image['title']; ?>">
            <figcaption><?php echo $image['summary']; ?></figcaption>
          </figure>

          <div>
            Dimensions: <?php echo $image['width']; ?> x <?php echo $image['height']; ?> Pixel<br>
            Size: <?php echo $image['size']; ?> bytes<br>
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
