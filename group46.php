<!--データベース接続-->
<?php

    //エラー表示
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );

   //データベースユーザ
    $user = 'testuser';
    $password = 'pw4testuser';

    $dbName = 'group46';
    $host = 'localhost:8889';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>坂道メンバー検索システム</title>


	<!--jQuery etc-->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<!--<script src="js/script.js"></script>-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/script.js"></script>


	<!--BootStrap CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!--stylesheet-->
	<link rel="stylesheet" type="text/css" href="css/style.css">



</head>
<body>
	<div class="header">
            <a href="group46.php"><h1>坂道メンバー検索</h1></a>
    </div>

    <div class="container">
            <div class="form-box mx-auto">
                <nav class="navbar navbar-light" style="background-color: #F8BBD0;">
                    <h3>検索フォーム</h3>
                </nav>
                <form class="rounded "name="input" method="POST" action="group46.php">
                    <div class="form-group mx-auto" style="width: 400px; height: 150px;">
                        <div class="form-inline">
                            <label><input class="form-control" type="text" name="name" placeholder="メンバー名または愛称を入力してください" style="width: 330px;"></label>
                            <label><input class="form-control" name="subButton" type="submit" value="検索"></label>
                        </div>

                        <div class="groups btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                            <input type="radio" name="group" autocomplete="off" value="全グループ" checked>全グループ
                            </label>
                            <label class="btn btn-outline-primary">
                            <input type="radio" name="group" autocomplete="off" value="乃木坂">乃木坂
                            </label>
                            <label class="btn btn-outline-primary">
                            <input type="radio" name="group" autocomplete="off" value="欅坂">欅坂
                            </label>
                            <label class="btn btn-outline-primary">
                            <input type="radio" name="group" autocomplete="off" value="けやき坂">けやき坂
                            </label>
                        </div>



                        <div class="graduate btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                            <input type="radio" name="graduate" value="全員" checked>全員<br>
                            </label>
                            <label class="btn btn-outline-primary">
                            <input type="radio" name="graduate" value="在籍">在籍<br>
                            </label>
                            <label class="btn btn-outline-primary">
                            <input type="radio" name="graduate" value="卒業">卒業<br>
                            </label>
                        </div>

                    </div>
                </form>
               
            </div>
        </div>

    <?php

    	$pdo = new PDO($dsn, $user, $password);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	if(isset($_POST['subButton'])) {

    		 //名前取得
            $name = htmlspecialchars($_POST['name']);

            //ラジオボタンの値を取得
            $group = htmlspecialchars($_POST['group']);
            $graduate = htmlspecialchars($_POST['graduate']);

			//絞り込み検索
            if($group === '全グループ'){
                if($graduate === '全員'){
                    if(empty($name)) {
                        $sql = "SELECT * FROM member_tbl ORDER BY group_id ASC, kana";
                    }
                    else {
                        $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                                . "m.id = n.id WHERE m.name LIKE '%$name%' OR n.nickname = '$name' ORDER BY m.id ASC";
                    }
                }else if($graduate === '在籍') {
                   if(empty($name)) {
                       $sql = "SELECT * FROM member_tbl WHERE graduate = 0 ORDER BY group_id ASC, kana";
                   }else {
                       $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 0 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.id ASC";
                   }
                }else { //卒業
                    if(empty($name)) {
                        $sql = "SELECT * FROM member_tbl WHERE graduate = 1";
                    }else {
                        $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 1 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.id ASC";
                    } 
                }
            }else if($group === '乃木坂') {
                if($graduate === '全員') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE group_id = 1 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.group_id ASC, m.kana";
                }else if($graduate === '在籍') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 0 AND group_id = 1 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.group_id ASC, m.kana";
                }else {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 1 AND group_id = 1 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.group_id ASC, m.kana";
                }
            }else if($group === '欅坂') {
                if($graduate === '全員') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE group_id = 2 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }else if($graduate === '在籍') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 0 AND group_id = 2 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }else {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 1 AND group_id = 2 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }
            }else {
                if($graduate === '全員') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE group_id = 3 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }else if($graduate === '在籍') {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 0 AND group_id = 3 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }else {
                    $sql = "SELECT DISTINCT m.id, group_id, name, face_img, no, birthday, birthplace, graduate FROM member_tbl m INNER JOIN nickname_tbl n ON "
                               . "m.id = n.id WHERE graduate = 1 AND group_id = 3 AND (name LIKE '%$name%' OR n.nickname = '$name') ORDER BY m.kana ASC";
                }
            }

        echo '<div class="container">';
            echo '<nav class="search_result navbar navbar-light" style="background-color: #f2dae8;">';

            echo '<h2>検索結果:';
            if(empty($name)){ //検索フォームが空で送信されたなら
                echo '全員';
            }
            $s_name = htmlspecialchars($_POST['name']);
            echo $s_name;
            echo '</h2>';

            /*冗長な気がする*/  
            echo '（';
            echo htmlspecialchars($_POST['group']);
            echo ':';
            echo htmlspecialchars($_POST['graduate']);
            echo '）';

            echo'</nav>';

        echo '</div>';

            echo '<div class="container">';
            echo '<div class="waku">';
            echo '<table cellpadding="8">';
            echo '<tr>';
        

        $stm = $pdo->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

     
        $flag = 0; //該当するメンバーが一人でもいたら1になる
        $i = 0; //段数制御用変数
        foreach ($result as $key => $value) {
            $flag = 1;
            if($i % 5 == 0) { //五人ごとに一段下ろす
                echo '</tr>';
                echo '<tr>';
            }

        	echo "<td>";

        	if($value['group_id'] == 1) {
                    echo '<div class="nogi">';
                }else {
                    echo '<div class="keyaki">';
            }

            echo '<div class="image">';

            /*検索後の顔写真と名前*/
            echo '<a data-target="modal_'.$value['id'].'" class="modal-open"><img src="'.$value['face_img'].'" class="thumbnail"></a>';
            echo '<p class="top_name">'.$value['name'].'</p>';

            /*モーダルウィンドウ*/
            echo '<div id="modal_'.$value['id'].'" class="container modal-content">';
            echo '<div class="content">';
            if($value['group_id'] == 1) {
                echo '<nav class="navbar navbar-light" style="background-color: #f2dae8;">';
            }else {
                echo '<nav class="navbar navbar-light" style="background-color: #b7ffb7;">';
            }
            echo '<h3>メンバー情報</h3>';
            echo '</nav>';

            //顔写真表示
            echo '<div class="parent_img">';
            echo '<img src="'.$value['face_img'].'">';
            echo '</div>';

            /*グループidからグループ名を取得し表示*/
            $group_id = $value['group_id'];
       
            if($group_id == 1) {
            	$group_name = "乃木坂46";
            }else if($group_id == 2) {
            	$group_name = "欅坂46";
            }else {
            	$group_name = "けやき坂46";
            }

            echo '<div class="detail">';
            echo '<h5>所属グループ</h5>';
            echo '<span class="group">　'.$group_name.'　'.$value['no'].'期生</span><br>';

          /*名前を表示*/
            echo '<h5>名前</h5>';
            echo '<span class="member_name">　'.$value['name'].'</span><br>';

            /*愛称を取得し表示*/
            $id = $value['id'];
            $sql_nick = "SELECT * FROM nickname_tbl WHERE id = '$id'";
            $stm_nick = $pdo->prepare($sql_nick);
            $stm_nick->execute();
            $result_nick = $stm_nick->fetchAll(PDO::FETCH_ASSOC);

            echo '<h5>愛称</h5>';
            echo '<span class="member_nickname">　';
            foreach ($result_nick as $key => $value_nick) {
            	echo $value_nick['nickname'].'  ';
            }
            
            echo '</span><br>';

            //生年月日出力
            echo '<h5 class="day_tag">生年月日</h5>';
            echo '<span class="member_birthday">　'.$value['birthday'].'</span><br>';

            //出身出力
            echo '<h5>出身</h5>';
            echo '<span class="member_birthplace">　'.$value['birthplace'].'</span><br>';

	      //写真集出力
            $sql_ph = "SELECT * FROM photo_album_tbl WHERE id = '$id' ORDER BY photo_album_id ASC";
            $stm_ph = $pdo->prepare($sql_ph);
            $stm_ph->execute();
            $result_ph = $stm_ph->fetchAll(PDO::FETCH_ASSOC);
         
            echo '<h5>写真集</h5>';
            echo '<span class="member_photo_album">';
            $num = 1;
            foreach ($result_ph as $key => $value_ph) {
            	 echo '　<a href="'.$value_ph['url'].'" target="_blank">'.$num.'.'.$value_ph['title'].'</a><br>';
            }
            
            echo '</span><br>';

            echo '</div>';

            //閉じるボタン
            echo '<a class="modal-close">×</a>';



            echo "</td>";

            echo '</div>';
            echo '</div>';
            echo '</div>';

            $i++;

        }


        if($flag == 0) { //該当するメンバーが一人もいないとき
            echo '<div class="container no_one"><h1>該当するメンバーはいませんでした。</h1></div>';
        }

    } //最初のifの閉じ括弧

    	
    ?>

	
</body>
</html>