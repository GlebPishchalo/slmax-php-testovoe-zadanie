<?php
require_once 'PersonDatabase.php';
require_once 'PeopleList.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $deleteIds = $_POST['delete_ids'];
    $peopleList = new PeopleList($deleteIds);
    $peopleList->deletePeople();
}
?>