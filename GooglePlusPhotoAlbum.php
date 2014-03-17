<?php

namespace GooglePlusPhotoAlbum;

class Album {
  protected $id;

  protected $userId;

  protected $albumData;

  protected $title;

  protected $images;

  protected $imageSchema = 'http://a9.com/-/spec/opensearchrss/1.0/';

  protected $photoSchema = 'http://schemas.google.com/photos/2007';

  protected $photoMediaSchema = 'http://search.yahoo.com/mrss/';

  /**
   * [__construct description]
   */
  public function __construct($config) {
    // Prepare user inputs
    if($this->setIdAndUser($config) === false) {
      $errorMsg = 'Please provide a valid Album and User ID';
      trigger_error($errorMsg, E_USER_ERROR);
    }

    try {
      $this->albumData = $this->getAlbumData();
    } catch (Exception $e) {
      throw new Exception('Can\'t get Album', 0, $e);
    }
  }

  // public function __call($property, $args) {
  //   // if(array_key_exists($key, $this->albumData)) {
  //   //   echo 'FOOBAR';
  //   // }
  //   // echo '<pre>' . print_r($args, true) . '</pre>';
  //   echo "Calling method {$property} / {$args}";
  // }

  // public function __get($property) {
  //   if(property_exists($this, $property)) {
  //     return $this->$property;
  //   }

  //   return false;
  // }

  private function setIdAndUser($config) {
    // Filter user inputs, strip HTML
    $config = filter_var_array($config, array(
      'id'     => FILTER_SANITIZE_STRING,
      'userId' => FILTER_SANITIZE_STRING
    ));

    if(!isset($config['id']) || empty($config['id'])) {
      return false;
    }

    if(!isset($config['userId']) || empty($config['userId'])) {
      return false;
    }

    // Passed the validation? Assign them.
    $this->id     = $config['id'];
    $this->userId = $config['userId'];

    return true;
  }

  private function getAlbumData() {
    // build album url
    $feedUrl = sprintf('http://picasaweb.google.com/data/feed/api/user/%s/albumid/%s?kind=photo&access=public', $this->userId, $this->id);

    // read feed data into SimpleXML object
    $sxml = simplexml_load_file($feedUrl);

    // get image counts
    $imageCount = $sxml->children($this->imageSchema);

    $album = array(
      'title'     => (string) $sxml->title,
      'thumbnail' => (string) $sxml->icon,
      'images'    => array(
        'total' => (string) $imageCount->totalResults,
        'media' => array()
      )
    );

    foreach($sxml->entry as $entry) {
      $photoData  = $entry->children($this->photoSchema);
      $photoMedia = $entry->children($this->photoMediaSchema);
      $thumbnail  = $photoMedia->group->thumbnail[1]->attributes()->{'url'};
      $thumbnails = $this->getMediaUrls($thumbnail);

      $album['images']['media'][] = array(
        'title'        => (string) $entry->title,
        'summary'      => (string) $entry->summary,
        'description'  => (string) $entry->description,
        'commentCount' => (string) $photoData->commentCount,
        'width'        => (string) $photoData->width,
        'height'       => (string) $photoData->height,
        'size'         => (string) $photoData->size,
        'published'    => (string) $photoData->timestamp,
        'thumbnails'   => $thumbnails
      );
    }

    // Convert Array to Object for better data handling
    #$albumObject = json_decode(json_encode($album), false);

    #return (object) $albumObject;
    return $album;
  }

  private function getMediaUrls($url = false) {
    $thumbnails = array(
      'origin' => (string) $url
    );

    $mediaSizes = array(
      's200-c',
      's400-c',
      'w200',
      'w400'
    );
    $subject = $url;
    $pattern = '/^(.*)(\/s144\/)(.*)/';
    preg_match($pattern, $subject, $matches);

    if(isset($matches) && (count($matches) === 4)) {
      foreach($mediaSizes as $media) {
        $thumbnails[$media] = $matches[1] . '/' . $media . '/' . $matches[3];
      }
    }

    return $thumbnails;
  }

  public function getTitle() {
    return $this->albumData['title'];
  }

  public function getImageCount() {
    return $this->albumData['images']['total'];
  }

  public function getImages() {
    $images = new Images($this->albumData['images']);

    return $images->images;
  }

  public function getImage($index) {
    #return new Image($this->albumData->images->media[$index]);
    // return $this->albumData->images->media[$index];
  }


  #echo '<pre style="border: 1px solid red;">' . print_r($album, true) . '</pre>';
}

// class Image {
//   private $image;
//   public functiuon __construct ($image) {
//     $this->image = $image;
//   }

//   public function getTitle() {
//     return $this->image->title;
//   }
// }
//

class Images {
  private $images;

  public function __construct($images) {
    $this->images = $images['media'];
  }

  public function getImage() {
    echo 'fooobar';
  }
}

class Thumbnails {

}

class AlbumFactory {
  public static function getAlbum($config = false) {
    return new Album($config);
  }
}

