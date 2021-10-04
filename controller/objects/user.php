<?php
// check if given email exist in the database
function usernameExists(){

    // query to check if email exists
    $query = "SELECT id, name, username, password
            FROM " . $this->tbl_user . "
            WHERE username = ?
            LIMIT 0,1";

    // prepare the query
    $stmt = $this->conn->prepare( $query );

    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));

    // bind given email value
    $stmt->bindParam(1, $this->username);

    // execute the query
    $stmt->execute();

    // get number of rows
    $num = $stmt->rowCount();

    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->favourites = $row['favourites']

        // return true because email exists in the database
        return true;
    }

    // return false if email does not exist in the database
    return false;
}
 ?>
