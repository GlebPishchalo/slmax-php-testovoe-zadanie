<?php
class PersonDatabase {
    private $connection;
    private $id;
    private $name;
    private $surname;
    private $birthdate;
    private $gender;
    private $City;

    public function __construct($id = null, $name = '', $surname = '', $birthdate = '', $gender = 0, $City = '') {
        $this->connection = new mysqli('localhost', 'root', '1234', 'testdb1');
        if ($this->connection->connect_error) {
            die("Ошибка подключения к базе данных: " . $this->connection->connect_error);
        }

        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->City = $City;
    }

    public function saveToDatabase() {
        $query = "INSERT INTO person (id, name, surname, birthdate, gender, city) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isssis", $this->id, $this->name, $this->surname, $this->birthdate, $this->gender, $this->City);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Ошибка при сохранении в базу данных: " . $this->connection->error;
            return false;
        }
    }

    public function removeFromDatabase() {
        $query = "DELETE FROM person WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $this->id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Ошибка при удалении из базы данных: " . $this->connection->error;
            return false;
        }
    }

    public static function ageFromBirthdate($birthdate) {
        $birthTimestamp = strtotime($birthdate);
        $currentTimestamp = time();
        $age = date('Y', $currentTimestamp) - date('Y', $birthTimestamp);
        if (date('md', $currentTimestamp) < date('md', $birthTimestamp)) {
            $age--;
        }
        return $age;
    }

    public static function genderToText($gender) {
        return $gender == 1 ? 'жен' : 'муж';
    }

    public static function getById($id) {
        $connection = new mysqli('localhost', 'root', '1234', 'testdb1');
        if ($connection->connect_error) {
            die("Ошибка подключения к базе данных: " . $connection->connect_error);
        }

        $query = "SELECT * FROM person WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $person = new self($row['id'], $row['name'], $row['surname'], $row['birthdate'], $row['gender'], $row['city']);
                return $person;
            }
        } else {
            echo "Ошибка при выполнении запроса: " . $connection->error;
        }
        return null;
    }

    public function formatPerson($formatAge = true, $formatGender = true) {
        $formattedPerson = new stdClass();
        $formattedPerson->id = $this->id;
        $formattedPerson->name = $this->name;
        $formattedPerson->surname = $this->surname;
        $formattedPerson->birthdate = $this->birthdate;
        $formattedPerson->gender = $formatGender ? self::genderToText($this->gender) : $this->gender;
        $formattedPerson->City = $this->City;
        if ($formatAge) {
            $formattedPerson->age = self::ageFromBirthdate($this->birthdate);
        }
        return $formattedPerson;
    }
    public function getId() {
        return $this->id;
    }
}
