<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
echo "Testing";

include "DbConnect.php";
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER["REQUEST_METHOD"];
switch($method) {
    case "GET":
        $sql = "SELECT * FROM users";
        $path = explode("/", $_SERVER["REQUEST_URL"]);
        if(isset($path[3]) && is_numeric($path[3])){
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $path[3]);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_decode($users);
    break;
    case "POST":
        $user = json_decode(file_get_contents("php://input"));
        $sql = "INSERT INTO users(id, firstName, lastName, birthDate, gender, country, zipCode, course, email, phone, password) VALUES(null, :firstName, :lastName, :birthDate, :gender, :country, :zipCode, :course, :email, :phone, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":firstName", $user->firstName);
        $stmt->bindParam(":lastName", $user->lastName);
        $stmt->bindParam(":birthDate", $user->birthDate);
        $stmt->bindParam(":gender", $user->gender);
        $stmt->bindParam(":country", $user->country);
        $stmt->bindParam(":zipCode", $user->zipCode);
        $stmt->bindParam(":course", $user->course);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":phone", $user->phone);
        $stmt->bindParam(":password", $user->password);
        if($stmt->execute()) {
            $response = ["status" => 1, "message" => "Record created successfully"];
        } else {
              $response = ["status" => 0, "message" => "Failed to create record."];
        }
        echo json_encode($response);
        break;
        
    case "PUT":
        $user = json_decode( file_get_contents('php://input') );
        $sql = "UPDATE users SET firstName= :firstNname, lastName =:lastName, birthDate =:birthDate, gender =:gender, country =:country, zipCode =:zipCode, course =:course, email =:email, phone =:phone, WHERE id = :id";
        $stmt = $conn->prepare($sql);
       $stmt->bindParam(":firstName", $user->firstName);
        $stmt->bindParam(":lastName", $user->lastName);
        $stmt->bindParam(":birthDate", $user->birthDate);
        $stmt->bindParam(":gender", $user->gender);
        $stmt->bindParam(":country", $user->country);
        $stmt->bindParam(":zipCode", $user->zipCode);
        $stmt->bindParam(":course", $user->course);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":phone", $user->phone);
        $stmt->bindParam(":password", $user->password);
        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM users WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);
        
}