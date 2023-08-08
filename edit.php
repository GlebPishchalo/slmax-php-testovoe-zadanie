<?php
require_once "test_1.php";  

$host = "localhost";
$username = "root";
$password = "1234";
$database = "testdb1";

$db = new mysqli($host, $username, $password, $database);

if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}

$personDb = new PersonDatabase($db);

if (isset($_POST['format'])) {
        $personId = $_POST['person_id'];
        $calculateAge = isset($_POST['calculate_age']);
        $convertGender = isset($_POST['convert_gender']);
    
        $formattedPerson = $personDb->formatPerson($personId, $calculateAge, $convertGender);
    
        if ($formattedPerson) {
            echo "ID: {$formattedPerson->id}<br>";
            echo "Name: {$formattedPerson->name}<br>";
            echo "Surname: {$formattedPerson->surname}<br>";
            echo "Birthdate: {$formattedPerson->birthdate}<br>";
            echo "Gender: {$formattedPerson->gender}<br>";
            echo "Birth City: {$formattedPerson->city}<br>";
            if ($calculateAge) {
                echo "Age: {$formattedPerson->age}<br>";
            }
        } else {
            echo "Person not found.";
        }
} elseif (isset($_POST['save'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $City = $_POST['city'];

    $newPerson = new Person(null, $name, $surname, $birthdate, $gender, $City);
    if ($personDb->savePerson($newPerson)) {
        echo "Person saved successfully.";
    } else {
        echo "Error saving person.";
    }

} elseif (isset($_POST['delete'])) {
    $deletePersonId = $_POST['delete_person_id'];
    if ($personDb->deletePerson($deletePersonId)) {
        echo "Person deleted successfully.";
    } else {
        echo "Error deleting person.";
    }
}

