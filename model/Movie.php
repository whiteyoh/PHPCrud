<?php

class MovieException extends Exception{}

  class Movie{
    private $_id;
    private $_title;
    private $_description;
    private $_date;
    private $_duration;
    private $_genre;
    private $_favourite;

    public function __construct($id, $title, $description, $date, $duration, $genre, $favourite){
      $this->setId($id);
      $this->setTitle($title);
      $this->setDescription($description);
      $this->setDate($date);
      $this->setDuration($duration);
      $this->setGenre($genre);
      $this->setFavourite($favourite);

    }
    public function getId(){
      return $this->_id;
    }
    public function getTitle(){
      return $this->_title;
    }
    public function getDescription(){
      return $this->_description;
    }
    public function getDate(){
      return $this->_date;
    }
    public function getDuration(){
      return $this->_duration;
    }
    public function getGenre(){
      return $this->_genre;
    }
    public function getFavourite(){
      return $this->_favourite;
    }
    public function isValidDate($date, $format = 'Y-m-d'){
      if($date == null || $date == ""){
        return true;
      }
      $dateObj = DateTime::createFromFormat($format, $date);
      return $dateObj && $dateObj->format($format) == $date;
    }
    public function setId($id){
      if (($id !== null) && (!is_numeric($id) || $this->_id !== null)) {
        throw new MovieException("Error: movie ID issue");
      }
      $this->_id = $id;
    }
    public function setTitle($title){
      if(strlen($title) <=0 || strlen($title) >=255 ){
        throw new MovieException("Error: Title issue");
      }
      $this->_title = $title;
    }
    public function setDescription($description){
      $this->_description = $description;
    }
    public function setDate($date){
      if(($date != null) && !$this->isValidDate($date, 'd-m-Y')){
        throw new MovieException ("Error: movie Date issue");
      }
      $this->_date = $date;
    }
    public function setDuration($duration){
      if(!$this->isValidDate($duration, 'H:i:s')){
        throw new MovieException("Error: Duration issue");
      }
      $this->_duration = $duration;
    }
    public function setGenre($genre){
      $this->_genre = $genre;
    }
    public function setFavourite($favourite){
      if(strtoupper($favourite)!== 'Y' && strtoupper($favourite)!== 'N'){
        throw new MovieException("Error: favourite issue");
      }
    $this->_favourite = $favourite;
    }
    public function getMovieAsArray(){
      $movie = array();
      $movie['id']= $this->getId();
      $movie['title']= $this->getTitle();
      $movie['description']= $this->getDescription();
      $movie['date']= $this->getDate();
      $movie['duration']= $this->getDuration();
      $movie['genre']= $this->getGenre();
      $movie['favourite']= $this->getFavourite();
      return $movie;
    }

}
 ?>
