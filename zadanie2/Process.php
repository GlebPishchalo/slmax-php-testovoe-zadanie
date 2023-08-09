<?php
  require_once 'PersonDatabase.php';
  require_once 'PeopleList.php';

if (class_exists('PeopleList')) {
    
    $searchParams = array('name','surname','age','gender','City');
    $peopleList = new PeopleList($searchParams);

   
    $people = $peopleList->getPeople();

    
    $peopleList->deletePeople();
} else {
    echo "Ошибка: отсутствует второй класс.";
}
?>
