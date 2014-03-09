<?php

namespace macx\GooglePlus;

/**
 *
 */
class GooglePlusPhotoAlbum {
  protected $userId = false;

  protected $albumId = false;

  protected $imageSchema = 'http://a9.com/-/spec/opensearchrss/1.0/';

  protected $photoSchema = 'http://schemas.google.com/photos/2007';

  protected $photoMediaSchema = 'http://search.yahoo.com/mrss/';

  /**
   * [__construct description]
   */
  public function __construct() {
  }

  /**
   * [setUserId description]
   * @param boolean $userId [description]
   */
  public function setUserId($userId = false) {
    if(($userId !== false) && is_string($userId)) {
      $this->userId = $userId;
    }
  }

  /**
   * [setAlbumId description]
   * @param boolean $albumId [description]
   */
  public function setAlbumId($albumId = false) {
    if(($albumId !== false) && is_string($albumId)) {
      $this->albumId = $albumId;
    }
  }

  /**
   * Validate given User and Album Id
   * @return boolean Valid User and Album Id returns true
   */
  private function validateIdentifiers() {
    // validate user id
    if(($this->userId === false) || !preg_match('/^([a-z0-9]+)$/', $this->userId)) {
      return false;
    }

    // validate album id
    if(($this->albumId === false) || !preg_match('/^([0-9]+)$/', $this->albumId)) {
      return false;
    }

    return true;
  }

  /**
   * [getAlbum description]
   * @return [type] [description]
   */
  public function getAlbum() {
    if(!$this->validateIdentifiers()) {
      $errorMsg = 'Please provide a valid User and Album ID';
      trigger_error($errorMsg, E_USER_ERROR);
    }

    // build album url
    $feedUrl = sprintf('http://picasaweb.google.com/data/feed/api/user/%s/albumid/%s?kind=photo&access=public', $this->userId, $this->albumId);

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

    return $album;
  }

  /**
   * [getMediaUrls description]
   * @param  boolean $url [description]
   * @return [type]       [description]
   */
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
}
