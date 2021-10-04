<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost:7707/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here
// files needed to connect to database
include_once 'controller/db.php';
include_once 'objects/user.php';

// get database connection
$database = new DB();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// submitted data will be here
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->name = $data->name;
$user->username = $data->username;
$user->password = $data->password;
$user->favourites = $data->favourites;

// use the create() method here
// create the user
if(
    !empty($user->name) &&
    !empty($user->username) &&
    !empty($user->password) &&
    !empty($user->favourites) &&
    $user->create()
){

    // set response code
    http_response_code(200);

    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}

// message if unable to create user
else{

    // set response code
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>
?>
