<?
class to_db2 {

    public function five_authors ($mysqli) {
                    
                    $mysqli->query("SET NAMES 'utf8'"); 
                    $mysqli->query("SET CHARACTER SET 'utf8'");
                    $mysqli->query("SET SESSION collation_connection = 'utf8_general_ci'");
                    $authors = $mysqli->query("SELECT count(`articles`.`id_author`), `articles`.`id_author`,  `users`.`name`, `users`.`surname`, `users`.`age` FROM `articles` JOIN `users` ON `articles`.`id_author` = `users`.`id` GROUP BY `id_author` ORDER BY count(`id_author`) DESC LIMIT 5;");
             
                    
                    while($authors2 = $authors->fetch_assoc()) {
                        $auts['count'][] = $authors2['count(`articles`.`id_author`)'];
                        $auts['id'][] = $authors2['id_author'];
                        $auts['name'][] = $authors2['name'];
                        $auts['surname'][] = $authors2['surname'];
                        $auts['age'][] = $authors2['age'];
                    }
                return $auts;
    }

        public function most_read ($mysqli) {
                 $result4 = $mysqli->query("SELECT * FROM `articles` ORDER BY  `stat` DESC LIMIT 5");

                while ($row4 = $result4->fetch_assoc()) {
                     ($row4['title'] . ' ' . $row4['text']) . '<br>';
                    $art2['titles'][] = $row4['title'];
                    $art2['stat'][] = $row4['stat'];
                    if (strlen($row4['text']) > 160) {
                     $art2['text'][] = mb_substr($row4['text'], 0, strpos(($row4['text']), ' ', 160)) . ' ...';
                     strpos(($row4['text']), ' ', 100);
                 }else {
                    $art2['text'][] = $row4['text'];
                 }
                     
                     $art2['img'][] = $row4['img']; 
                     $art2['date'][] = $row4['date'];
                     $art2['time'][] = $row4['time'];
                     $art2['id'][] = $row4['id'];

                }

                return $art2;
           
        }

        public function comments ($mysqli){
           
                $result_comm = $mysqli->query("SELECT * FROM `comments` ORDER BY id DESC LIMIT 5");
                    while ($row5 = $result_comm->fetch_assoc()) {
                     ($row5['title'] . ' ' . $row5['text']) . '<br>';
                    $art3['text_comm'][] = $row5['text_comm'];
                    $art3['time_comm'][] = $row5['time_comm'];
                    $art3['date_comm'][] = $row5['date_comm'];
                   
                 }
                 return $art3;
        }

        public function pagination1 ($mysqli) {
            
                // количество записей, выводимых на странице
                $per_page=10;
                // получаем номер страницы
                if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
                // вычисляем первый оператор для LIMIT
                $start=abs($page*$per_page);
                // составляем запрос и выводим записи
                // переменную $start используем, как нумератор записей.
                //$res2 = $mysqli->query("SELECT * FROM `articles` JOIN `users` ON  `articles`.`id_author` = `users`.`id` ORDER BY `articles`.`id`  LIMIT $start,$per_page");
                $res2 = $mysqli->query("SELECT * FROM `articles` ORDER BY `id` DESC LIMIT $start,$per_page");


               
                return $res2;

        }

        public function pagination2($mysqli) {
             // дальше выводим ссылки на страницы:
                $per_page=10;
             
                $res = $mysqli->query("SELECT count(*) FROM `articles`");
                $row2=$res->fetch_row();
                $total_rows=$row2[0];

                $num_pages=ceil($total_rows/$per_page);
                return $num_pages;
        }


        public function authorization ($mysqli) {

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['login']) && !empty($_POST['password'])) {
            $mysqli->query("SET names 'cp1251'");
            $login = $mysqli->query("SELECT * FROM `users` WHERE `email` = '{$_POST['login']}';");
            $res = $login->fetch_assoc();

            if ($res['email'] != $_POST['login'] || $res['password'] != $_POST['password']) {
                if ($res['email'] == $_POST['login']) {
                    $_SESSION['msg'] = 'неправильный пароль';
                }elseif ($res['email'] != $_POST['login']) {
                    $_SESSION['msg'] = 'неправильный логин';
                }
                
            }else {
                $_SESSION['msg'] = " вы авторизованы, {$res['name']}!!";
                $_SESSION['name'] = $res['name'];
                $_SESSION['login'] = $res['login'];
                $_SESSION['id'] = $res['id'];
                header("Location: index.php");
            }
            }
    }



    public function create_article ($mysqli) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['title']) && !empty($_POST['text'])) {
    
    $title = htmlspecialchars(mb_convert_encoding($_POST['title'], 'cp1251', mb_detect_encoding($_POST['title'])));
    $text = htmlspecialchars(mb_convert_encoding($_POST['text'], 'cp1251', mb_detect_encoding($_POST['text'])));
    $author = htmlspecialchars(mb_convert_encoding($_POST['author'], 'cp1251', mb_detect_encoding($_POST['author'])));
    $date = date(Y . '-' . m .'-' . d);
    $time = date(G . ':' . i . ':' . s);
    $name_file = trim(mb_strtolower($_FILES['file']['name']));
    $tmp_name = $_FILES['file']['tmp_name'];
//header("Location: index.php");
    move_uploaded_file($tmp_name, "img/$name_file");
    

    if(!empty($_POST['title']) && !empty($_POST['text'])) {
//$mysqli->query("SET names 'cp1251'");
        $mysqli->query("SET NAMES 'utf8'"); 
        $mysqli->query("SET CHARACTER SET 'utf8'");
        $mysqli->query("SET SESSION collation_connection = 'utf8_general_ci'");
$result = $mysqli->query("INSERT INTO `articles` VALUES (NULL, '$title', '$text', '$author', '$time', '$date', '$name_file', '0', '0');");

        header("Location: index.php");
}
}

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['title'])) {
        $er = 'Введите название для статьи!';
    }elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['text'])) {
        $er2 = 'Введите текст для статьи!';
    }

    if (!empty($_GET['id'])) {
        header("Location: create.php");
    }
    }



}


