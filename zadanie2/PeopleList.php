<?php
  require_once 'PersonDatabase.php';

  if (class_exists('PersonDatabase')) {
    

class PeopleList {
    private $peopleIds = array();
    private $connection;
    public function __construct($searchParams) {
        $this->connection = new mysqli('localhost', 'root', '1234', 'testdb1');
        if ($this->connection->connect_error) {
            die("Ошибка подключения к базе данных: " . $this->connection->connect_error);
        }

        $query = "SELECT id FROM person WHERE 1"; 

        foreach ($searchParams as $field => $value) {
            
            $validFields = array('name', 'surname', 'birthdate', 'gender', 'City');
            if (in_array($field, $validFields)) {
                
                $value = $this->connection->real_escape_string($value);
                if ($field == 'age') {
                    $query .= " AND YEAR(CURDATE()) - YEAR(birthdate) $value";
                } elseif ($field == 'gender') {
                    $query .= " AND gender $value";
                } else {
                    $query .= " AND $field = '$value'";
                }
            }
        }

        $result = $this->connection->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->peopleIds[] = $row['id'];
            }
        } else {
            echo "Ошибка запроса: " . $this->connection->error;
        }
    }

    public function getPeople() {
        $people = array();
        if (!empty($this->peopleIds)) {
            $ids = implode(',', $this->peopleIds);

            $query = "SELECT * FROM person WHERE id IN ($ids)";

            $result = $this->connection->query($query); 

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $person = new PersonDatabase($row['id'], $row['name'], $row['surname'], $row['birthdate'], $row['gender'], $row['city']);
                    $people[] = $person;
                }
            } else {
                echo "Ошибка запроса: " . $this->connection->error;
            }
        }
        return $people;
    
    
    }

    public function deletePeople() {
        if (!empty($this->peopleIds)) {
            $ids = implode(',', $this->peopleIds);

            $query = "DELETE FROM person WHERE id IN ($ids)";

            if ($this->connection->query($query)) {
                echo "Удаление успешно выполнено.";
            } else {
                echo "Ошибка при удалении: " . $this->connection->error;
            }
        }
    }
}}else {
    echo "Класс не найден";
}
  
