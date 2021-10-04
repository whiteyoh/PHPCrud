<?php

require_once('db.php');
require_once('../model/Movie.php');
require_once('../model/Response.php');
require_once('../helper/helperFunction.php');

try{
  $writeDB = DB::connectWriteDB();
  $readDB = DB::connectReadDB();

}
catch(PDOException $exception){
  error_log("Data Connection Error - ".$exception, 0);
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Database Connection Failed");
  $response->send();
  exit;
}

if(array_key_exists("movieid", $_GET)){
  $movieid = $_GET['movieid'];

  if($movieid == '' || !is_numeric($movieid)){
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Movie ID: Cannot be null and must be numeric");
    $response->send();
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET'){
try{
  $query = $readDB->prepare('select id, title, description,
   DATE_FORMAT(date, "%d-%m-%Y") as "date", duration, genre, favourite from tbl_movies where id = :movieid');
  $query->bindParam(':movieid', $movieid, PDO::PARAM_INT);
  $query->execute();

  $rowCount = $query->rowCount();
  $movieArray = array();

  if($rowCount === 0){
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Movie ID: Not Found");
    $response->send();
    exit;
  }
  while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $movie = new Movie($row['id'], $row['title'], $row['description'], $row['date'], $row['duration'], $row['genre'], $row['favourite']);
    $movieArray[] = $movie->getMovieAsArray();
  }

  $returnData = array();
  $returnData['row_returned'] = $rowCount;
  $returnData['movies'] = $movieArray;

  $response = new Response();
  $response->setHttpStatusCode(200);
  $response->setSuccess(true);
  $response->toCache(true);
  $response->setData($returnData);
  $response->send();
  exit;
}
catch(MovieException $exception){
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage($exception->getMessage());
  $response->send();
  exit;
}
catch(PDOException $exception){
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Failed to retrieve movie");
  $response->send();
  exit;
}
}
  elseif($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    try{
      $query = $writeDB->prepare('delete from tbl_movies where id=:movieid');
      $query->bindParam(':movieid', $movieid, PDO::PARAM_INT);
      $query->execute();

      $rowCount = $query->rowCount();

      if($rowCount === 0){
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Error: movie not found");
        $response->send();
        exit();
      }
      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->addMessage("movie deleted successfully");
      $response->send();
      exit();
    }
    catch(PDOException $exception){
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Failed to delete movie");
      $response->send();
      exit();
    }
  }
  elseif($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    try{
  if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Error: Invalid Content Type Header.");
    $response->send();
    exit();
  }
  $rawPOSTData = file_get_contents('php://input');

  if(!$jsonData = json_decode($rawPOSTData)){
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Error: Request Body is not Valid in JSON.");
    $response->send();
    exit();
  }
      $titleUpdated = false;
      $descriptionUpdated = false;
      $dateUpdated = false;
      $durationUpdated = false;
      $genreUpdated = false;
      $favouriteUpdated = false;

      $queryFields = "";

      if(isset($jsonData->title)){
        $titleUpdated = true;
        $queryFields .= "title = :title, ";
      }
      if(isset($jsonData->description)){
        $descriptionUpdated = true;
        $queryFields .= "description = :description, ";
      }
      if(isset($jsonData->date)){
        $dateUpdated = true;
        $queryFields .= "date = STR_TO_DATE(:date, '%d-%m-%Y'), ";
      }
      if(isset($jsonData->duration)){
        $durationUpdated = true;
        $queryFields .= "duration = :duration, ";
      }
      if(isset($jsonData->genre)){
        $genreUpdated = true;
        $queryFields .= "genre = :genre, ";
      }
      if(isset($jsonData->favourite)){
        $favouriteUpdated = true;
        $queryFields .= "favourite = :favourite, ";
      }

      $queryFields = rtrim($queryFields, ", ");

      if($queryFields === "") {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("No Data Provided");
        $response->send();
        exit();
      }
$movieid = $_GET['movieid'];
$query = $readDB->prepare('select id, title, description, DATE_FORMAT(date, "%d-%m-%Y") as "date", duration, genre, favourite from tbl_movies where id = :movieid');
$query->bindParam(':movieid', $movieid, PDO::PARAM_INT);
$query->execute();

$rowCount = $query->rowCount();

if($rowCount === 0) {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("movie ID Not Found");
  $response->send();
  exit;
}

while($row = $query->fetch(PDO::FETCH_ASSOC)) {
  $movie = new movie($row['id'], $row['title'], $row['description'], $row['date'], $row['duration'], $row['genre'], $row['favourite']);
}

$updateQueryString = "update tbl_movies set ".$queryFields." where id = :movieid";
$updateQuery = $writeDB->prepare($updateQueryString);

if($titleUpdated === true){
  $movie->setTitle($jsonData->title);
  $updatedTitle = $movie->getTitle();
  $updateQuery->bindParam(':title', $updatedTitle, PDO::PARAM_STR);
}
if($descriptionUpdated === true){
  $movie->setDescription($jsonData->description);
  $updatedDescription = $movie->getDescription();
  $updateQuery->bindParam(':description', $updatedDescription, PDO::PARAM_STR);
}
if($dateUpdated === true){
  $movie->setDate($jsonData->date);
  $updatedDate = $movie->getDate();
  $updateQuery->bindParam(':date', $updatedDate, PDO::PARAM_STR);
}
if($durationUpdated === true){
  $movie->setDuration($jsonData->duration);
  $updatedDuration = $movie->getDuration();
  $updateQuery->bindParam(':duration', $updatedDuration, PDO::PARAM_STR);
}
if($genreUpdated === true){
  $movie->setgenre($jsonData->genre);
  $updatedGenre = $movie->getgenre();
  $updateQuery->bindParam(':genre', $updatedGenre, PDO::PARAM_STR);
}
if($favouriteUpdated === true){
  $movie->setFavourite($jsonData->favourite);
  $updatedfavourite = $movie->getFavourite();
  $updateQuery->bindParam(':favourite', $updatedFavourite, PDO::PARAM_STR);
}

$updateQuery->bindParam(':movieid', $movieid, PDO::PARAM_INT);
$updateQuery->execute();

$rowCount = $updateQuery->rowCount();
$movieArray = array();

if($rowCount === 0) {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Movie Not Updated");
  $response->send();
  exit;
}

$query = $readDB->prepare('select id, title, description, DATE_FORMAT(date, "%d-%m-%Y") as "date", duration, genre, favourite from tbl_movies where id = :movieid');
$query->bindParam(':movieid', $movieid, PDO::PARAM_INT);
$query->execute();

$rowCount = $query->rowCount();
$movieArray = array();

if($rowCount === 0) {
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Movie ID Not Found");
  $response->send();
  exit;
}
while($row = $query->fetch(PDO::FETCH_ASSOC)) {
  $movie = new Movie($row['id'], $row['title'], $row['description'], $row['date'], $row['duration'], $row['genre'], $row['favourite']);
  $movieArray[] = $movie->getMovieAsArray();
}

$returnData = array();
$returnData['rows_returned'] = $rowCount;
$returnData['movies'] = $movieArray;

$response = new Response();
$response->setHttpStatusCode(200);
$response->setSuccess(true);
$response->toCache(true);
$response->setData($returnData);
$response->send();
exit;
}

catch(MovieException $exception) {
  $response = new Response();
  $response->setHttpStatusCode(400);
  $response->setSuccess(false);
  $response->addMessage($exception->getMessage());
  $response->send();
  exit();
}
catch(PDOException $exception) {
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Failed to Update movie");
  $response->send();
  exit();
}
}
else {
$response = new Response();
$response->setHttpStatusCode(405);
$response->setSuccess(false);
$response->addMessage("Request method not allowed");
$response->send();
exit;
}
  }
else if(array_key_exists("favourite", $_GET)){
  $favourite = $_GET['favourite'];

  if($favourite !== 'Y' && $favourite !== 'N'){
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Error: favourite must be Y or N");
    $response->send();
    exit();
  }

  if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
      $query = $readDB->prepare('select id, title, description,
       DATE_FORMAT(date, "%d-%m-%Y") as "date", duration,
      genre, favourite from tbl_movies where favourite = :favourite');
      $query->bindParam(':favourite', $favourite, PDO::PARAM_STR);
      $query->execute();

      $rowCount = $query->rowCount();
      $movieArray = array();

      while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $movie = new Movie($row['id'],$row['title'],$row['description'],$row['date'], $row['duration'], $row['genre'], $row['favourite']);
        $movieArray[] = $movie->getMovieAsArray();
      }
      $returnData = array();
      $returnData['rows_returned'] = $rowCount;
      $returnData['movies'] = $movieArray;

      $response = new Response();
      $response->setHttpStatusCode(200);
      $response->setSuccess(true);
      $response->toCache(true);
      $response->setData($returnData);
      $response->send();
      exit();
    }
    catch(MovieException $exception){
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage($exception->getMessage());
      $response->send();
      exit();
    }
    catch(PDOException $exception){
      $response = new Response();
      $response->setHttpStatusCode(500);
      $response->setSuccess(false);
      $response->addMessage("Error: Failed to get movie");
      $response->send();
      exit();
    }
  }
    else{
      $response = new Response();
      $response->setHttpStatusCode(405);
      $response->setSuccess(false);
      $response->addMessage("Error: Invalid Request Method");
      $response->send();
      exit();
    }
  }
  elseif(!array_key_exists("movieid", $_GET) || empty($_GET)){

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  try{
if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
  $response = new Response();
  $response->setHttpStatusCode(400);
  $response->setSuccess(false);
  $response->addMessage("Error: Invalid Content Type Header.");
  $response->send();
  exit();
}
$rawPOSTData = file_get_contents('php://input');

if(!$jsonData = json_decode($rawPOSTData)){
  $response = new Response();
  $response->setHttpStatusCode(400);
  $response->setSuccess(false);
  $response->addMessage("Error: Request Body is not Valid in JSON.");
  $response->send();
  exit();
}
if(!isset($jsonData->title) || !isset($jsonData->favourite)){
  $response = new Response();
  $response->setHttpStatusCode(400);
  $response->setSuccess(false);
  (!isset($jsonData->title) ? $response->addMessage("Error: Title is a Mandatory Field") :false);
  (!isset($jsonData->favourite) ? $response->addMessage("Error: Favourite (Y/N) is a Mandatory Field") :false);
  $response->send();
  exit();
}
$newMovie = new Movie (null,
(isset($jsonData->title) ? $jsonData->title : null),
(isset($jsonData->description) ? $jsonData->description : null),
(isset($jsonData->date) ? $jsonData->date : null),
(isset($jsonData->duration) ? $jsonData->duration : null),
(isset($jsonData->genre) ? $jsonData->genre : null),
(isset($jsonData->favourite) ? $jsonData->favourite : null));

$title = $newMovie->getTitle();
$description = $newMovie->getDescription();
$date = $newMovie->getDate();
$duration = $newMovie->getDuration();
$genre = $newMovie->getGenre();
$favourite = $newMovie->getfavourite();

$query = $writeDB->prepare('insert into tbl_movies(title, description, date, duration, genre, favourite) values (:title, :description, STR_TO_DATE(:date, \'%d-%m-%Y\'), :duration,
:genre, :favourite)');

$query->bindParam(':title', $title, PDO::PARAM_STR);
$query->bindParam(':description', $description, PDO::PARAM_STR);
$query->bindParam(':date', $date, PDO::PARAM_STR);
$query->bindParam(':duration', $duration, PDO::PARAM_STR);
$query->bindParam(':genre', $genre, PDO::PARAM_STR);
$query->bindParam(':favourite', $favourite, PDO::PARAM_STR);

$tempvar = $favourite;

$query->execute();

$rowCount = $query->rowCount();

if($rowCount == 0){
  $response = new Response();
  $response->setHttpStatusCode(500);
  $response->setSuccess(false);
  $response->addMessage("Error: Failed to Insert movie into Database.");
  $response->send();
  exit();
}
$lastMovieID = $writeDB->lastInsertId();
$query = $readDB->prepare('select id, title, description,
 DATE_FORMAT(date, "%d-%m-%Y") as "date", duration,
genre, favourite from tbl_movies where id = :movieid');
$query->bindParam(':movieid', $lastMovieID, PDO::PARAM_INT);
$query->execute();

$rowCount = $query->rowCount();
$movieArray = array();

if($rowCount === 0){
  $response = new Response();
  $response->setHttpStatusCode(404);
  $response->setSuccess(false);
  $response->addMessage("Error: movie ID: Not Found");
  $response->send();
  exit;
}
while($row = $query->fetch(PDO::FETCH_ASSOC)){
  $movie = new Movie($row['id'],$row['title'],$row['description'],$row['date'], $row['duration'], $row['genre'], $row['favourite']);
  $movieArray[] = $movie->getMovieAsArray();
}

$returnData = array();
$returnData['row_returned'] = $rowCount;
$returnData['movies'] = $movieArray;

$response = new Response();
$response->setHttpStatusCode(200);
$response->setSuccess(true);
$response->toCache(true);
$response->setData($returnData);
$response->send();
exit;

  }
  catch(MovieException $exception){
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage($exception->getMessage());
    $response->send();
    exit();
  }

  catch(PDOException $exception){
    error_log("Datatbase Query Error: ".$exception, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("PDO Error: Failed to Insert movie into Database.");
    $response->send();
    exit();
  }
}
  else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Error: Invalid Endpoint");
    $response->send();
    exit();
  }
}


?>
