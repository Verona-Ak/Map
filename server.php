<?php

    /*
        Загрузка в mysql данных из xml
    */

    // function insert($id, $name, $link) {
    //     $mysqli = new mysqli('localhost', 'root', '', 'countries');
    //     if(mysqli_connect_errno()) {
    //         printf('Соединение не установлено');
    //         exit();
    //     }
    //     $mysqli->set_charset('utf8');
    //     $query= "INSERT INTO countries_info VALUES (null, '$id', '$name', '$link')";

    //     $result = false;
    //     if($mysqli->query($query)) {
    //         $result = true;
    //     }

    //     $mysqli->close();
    //     return $result;
    // }
    // $xml = simplexml_load_file("inf.xml") or die ("Error");

    // $id = null;
    // $name = null;
    // $link = null;

    // foreach ($xml as $key => $value) {
    //     $id = $value->id;
    //     $name = $value->name;
    //     $link = $value->link;
    //     insert($id, $name, $link);
    // }

    
    /*
        Получение данных из mysqli и сохранение в массив, который записываем в json файл
    */

    
    $arr = []; // Массив с данными из БД


    $mysqli = new mysqli('localhost', 'root', '', 'countries');
    if(mysqli_connect_errno()) {
        printf('Соединение не установлено');
        exit();
    }
    $mysqli->set_charset('utf8');

    $query = $mysqli->query('SELECT * FROM countries_info');
    while($row = mysqli_fetch_assoc($query)) {
        $arr[] = $row;
    }
    $json = json_encode($arr);
    $file = fopen('data.json', 'w+');
    fwrite($file, $json);
    fclose($file);
    $mysqli->close();

    
?>