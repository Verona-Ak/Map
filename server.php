<?php
    

    // function insert($id, $name) {
    //     $mysqli = new mysqli('localhost', 'root', '', 'countries');
    //     if(mysqli_connect_errno()) {
    //         printf('Соединение не установлено');
    //         exit();
    //     }
    //     $mysqli->set_charset('utf8');
    //     printf($id);
    //     // printf(`$name\n`);
    //     $query= "INSERT INTO countries_info VALUES (null, '$id', '$name')";

    //     $result = false;


    //     if($mysqli->query($query)) {
    //         $result = true;
    //         printf('+');
    //     }
    //     printf($result);
    //     if($result == false) {
    //         printf('-');
    //     }
    //     return $result;

    // }
    // $xml = simplexml_load_file("inf.xml") or die ("Error");

    // $id = null;
    // $name = null;
    // // $information = null;

    // foreach ($xml as $key => $value) {
    //     $id = $value->id;
    //     $name = $value->name;
    //     // $information = $value->information;
    //     insert($id, $name);
        
    // }
    // $mysqli->close();
    $arr = [];
    
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