<?php
$host = "localhost";
$username = "root";
$password = "1234";
$database = "testdb1";


$db = new mysqli($host, $username, $password, $database);


if ($db->connect_error) {
    die("Ошибка подключения: " . $db->connect_error);
}


$personDb = new PersonDatabase($db);
class PersonDatabase {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function savePerson($person) {
        
        $query = "INSERT INTO person (id, name, surname, birthdate, gender, city) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isssis", $person->id, $person->name, $person->surname, 
                          $person->birthdate, $person->gender, $person->City);
        return $stmt->execute();
    }

    public function deletePerson($id) {
       
        $query = "DELETE FROM person WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function calculateAge($birthdate) {
        $today = new DateTime();
        $birthDate = new DateTime($birthdate);
        $age = $today->diff($birthDate)->y;
        return $age;
    }

    public static function convertGender($gender) {
        return ($gender == 0) ? 'муж' : 'жен';
    }

    public function getPersonById($id) {
        
        $query = "SELECT id, name, surname, birthdate, gender, city 
                  FROM person WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function formatPerson($id, $calculateAge = true, $convertGender = true) {
        $personData = $this->getPersonById($id);
        if (!$personData) {
            return null;
        }

        if ($calculateAge) {
            $personData['age'] = self::calculateAge($personData['birthdate']);
        }

        if ($convertGender) {
            $personData['gender'] = self::convertGender($personData['gender']);
        }

        return (object) $personData;
    }
}

class Person {
    public $id;
    public $name;
    public $surname;
    public $birthdate;
    public $gender;
    public $City;

    public function __construct($name, $surname, $birthdate, $gender, $City) {
        
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->City = $City;
    }}
?>