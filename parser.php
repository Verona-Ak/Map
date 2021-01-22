<?php
    $link = $_POST['link'];

    function curl_get($url, $referer = 'http://www.google.com') {   // Откуда мы пришли на нужный сайт
        $ch = curl_init(); // сh хранит все насройки curl
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31');
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, fopen('file.txt', 'w+'));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    $html = curl_get($link);
    $filename = 'file.txt'; // имя файла, в кот хранится страница другого сайта
    $str = file_get_contents($filename);

    // echo mb_strlen($str, 'UTF-8');

    $pos_start = mb_stripos($str, '</table>');
    $paragrah_1_start = mb_stripos($str, '<p>', $pos_start); // вхождение <p>
    $paragrah_1_end = mb_stripos($str, '</p>', $paragrah_1_start) + 4; //конец параграфа
    $paragrah_1_length = $paragrah_1_end - $paragrah_1_start;
    $paragrah_1 = mb_substr($str, $paragrah_1_start, $paragrah_1_length, 'UTF-8');

    $paragrah_2_start = $paragrah_1_end;
    $paragrah_2_end = mb_stripos($str, '</p>', $paragrah_2_start) + 4;
    $paragrah_2_length = $paragrah_2_end - $paragrah_2_start;
    $paragrah_2 = mb_substr($str, $paragrah_2_start, $paragrah_2_length, 'UTF-8');

    $paragrah_3_start = $paragrah_2_end;
    $paragrah_3_end = mb_stripos($str, '</p>', $paragrah_3_start) + 4;
    $paragrah_3_length = $paragrah_3_end - $paragrah_3_start;
    $paragrah_3 = mb_substr($str, $paragrah_3_start, $paragrah_3_length, 'UTF-8');

    $paragraph = $paragrah_1.$paragrah_2.$paragrah_3;
    $result = strip_tags($paragraph);

    

    $json = json_encode($result);
    $file = fopen('wiki.json', 'w+');
    fwrite($file, $json);
    fclose($file);

    // function search_line($file_name) {
    //     $str = file_get_contents($file_name);
    //     $start = '<p>';
    //     $end = '</p>';
    //     $pos_start = strpos($str, $start);
    //     $pos_end = strpos($str, $end);
    //     $pos_q = $pos_end - $pos_start;
    //     echo $pos_q;
    //     return $pos_q;
    //     // $mass = explode ("\n", $str);
    //     // foreach ($mass as $key => $line) {
    //     //     $key++;
    //     //     if (strripos($line, $key_line) !== false) {
    //     //         $resultline[$key+1] = $line;
    //     //     }
    //     // }
    //     // return $resultline;
    // }




?>