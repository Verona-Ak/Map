<?php
    $link = $_POST['link']; // ссылка на конкретную страницу в Википедии

    $filename = 'file.txt'; // имя файла, в кот загрузится html страница другого сайта

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
    
    $str = file_get_contents($filename); // содержимое файла сохраняем в строку

    
    function paragraphs($str) {
        function get_paragraph($start) {
            global $str;
            $paragrah_start = mb_stripos($str, '<p>', $start); // ищим вхождения
            $paragrah_end = mb_stripos($str, '</p>', $paragrah_start); 
            $paragrah_length = $paragrah_end + mb_strlen('</p>') - $paragrah_start; // длина подстроки
            $paragrah = mb_substr($str, $paragrah_start, $paragrah_length, 'UTF-8'); // находим подстроку
            return $paragrah;
        }
        $paragraph_1 = get_paragraph(mb_stripos($str, '</table>')); // в ф-ию передаём первое вхождение </table>
        $paragraph_2 = get_paragraph(mb_stripos($str, $paragraph_1) + mb_strlen($paragraph_1));
        $paragraph_3 = get_paragraph(mb_stripos($str, $paragraph_2) + mb_strlen($paragraph_2)); 
        return $paragraph_1.$paragraph_2.$paragraph_3;
    }
    $paragraph = paragraphs($str);

    $paragraph_after_func = delete_additional_tags($paragraph);
    $result = html_entity_decode(strip_tags($paragraph_after_func), ENT_COMPAT | ENT_HTML5, 'UTF-8'); 
    /*
        strip_tags() - удаление html тегов
        html_entity_decode() - получаем спец. символы от их html- эквивалентов
    */

    echo $result;

    // $json = json_encode($result);
    // $file = fopen('wiki.json', 'w+');
    // fwrite($file, $json);
    // fclose($file);

    function delete_additional_tags($str) {
        $string = $str;
        while(mb_stripos($string, '/sup>')) {
            $start = mb_stripos($string, '<sup');
            $end = mb_stripos($string, '/sup>');
            $delete_string = mb_substr($string, $start, ($end + mb_strlen('/sup>') - $start), 'UTF-8');
            $string = str_replace($delete_string, '', $string);
        }
        while(mb_stripos($string, ")")) {
            $start = mb_stripos($string , "(" );
            $start_pl = $start + 1;

            $end = mb_stripos($string, ")");

            $str_delete = mb_substr($string, $start, ($end + mb_strlen(")") - $start), 'UTF-8');
            $str_delete_minus = mb_substr($string, $start_pl, ($end + mb_strlen(")") - $start_pl), 'UTF-8');
            
            if(mb_substr_count($str_delete, '(', 'UTF-8') > 1) {
                $string = str_replace($str_delete_minus, '', $string);
            } else {
                $string = str_replace($str_delete, '', $string);
            }
        }
        return $string;
    }

?>