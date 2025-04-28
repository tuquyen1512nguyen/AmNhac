<?php
include __DIR__ . "/../utils/dbConnection.php";
include(__DIR__ . "/auth.php");


if (isset($_GET['songID'])) {
    $songID = $_GET['songID'];

    $addToFavQuery = "INSERT INTO favourites
                    VALUES ($uid, $songID);";

    if (mysqli_query($conn, $addToFavQuery)) {
        echo json_encode($songID);
    } else {
        echo json_encode("Error");
    }
}
