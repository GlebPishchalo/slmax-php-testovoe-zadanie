<!DOCTYPE html>
<html>
<head>
    <title>Поиск людей</title>
</head>
<body>
    <h1>Поиск людей</h1>
    <form action="search.php" method="post">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="searchParams[name]"><br><br>
        
        <label for="surname">Фамилия:</label>
        <input type="text" id="surname" name="searchParams[surname]"><br><br>
        
        <label for="age">Возраст:</label>
        <input type="text" id="age" name="searchParams[age]"><br><br>
        
        <label for="gender">Пол:</label>
        <select id="gender" name="searchParams[gender]">
            <option value="= 0">Мужчина</option>
            <option value="= 1">Женщина</option>
        </select><br><br>
        
        <label for="City">Город рождения:</label>
        <input type="text" id="City" name="searchParams[City]"><br><br>
        
        <input type="submit" value="Найти">
    </form>
    
    <?php
    
        require_once 'PersonDatabase.php';
        require_once 'PeopleList.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchParams = array();
        
            if (!empty($_POST['searchParams']['name'])) {
                $searchParams['name'] = $_POST['searchParams']['name'];
            }
            if (!empty($_POST['searchParams']['surname'])) {
                $searchParams['surname'] = $_POST['searchParams']['surname'];
            }
            if (!empty($_POST['searchParams']['age'])) {
                $searchParams['age'] = $_POST['searchParams']['age'];
            }
            if (!empty($_POST['searchParams']['gender'])) {
                $searchParams['gender'] = $_POST['searchParams']['gender'];
            }
            if (!empty($_POST['searchParams']['City'])) {
                $searchParams['birthCity'] = $_POST['searchParams']['City'];
            }
        
            $peopleList = new PeopleList($searchParams);
            $people = $peopleList->getPeople();
        
            echo "<html><head><title>Результаты поиска</title></head><body>";
        
            if (!empty($people)) {
                echo "<h2>Результаты поиска:</h2>";
                foreach ($people as $person) {
                    $formattedPerson = $person->formatPerson();
                    echo "<p>ID: $formattedPerson->id, Имя: $formattedPerson->name, Фамилия: $formattedPerson->surname, Возраст: $formattedPerson->age, Пол: $formattedPerson->gender, Город рождения: $formattedPerson->City</p>";
                }}
            
                if (!empty($people)) {
                 
                    echo "<form action=\"delete.php\" method=\"post\">";
                    foreach ($people as $person) {
                        $formattedPerson = $person->formatPerson();
                        echo "<input type=\"checkbox\" name=\"delete_ids[]\" value=\"" . $person->getId() . "\"> Удалить $formattedPerson->name<br>";
                    }
                    echo "<input type=\"submit\" value=\"Удалить\">";
                    echo "</form>";
                } else {
                    echo "<p>Ничего не найдено.</p>";
                }}
    ?>
</body>
</html>
