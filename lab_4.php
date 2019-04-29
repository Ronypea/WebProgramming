<?php

// Работа с БД

function createConnection()
{
    $mysqli = new mysqli('localhost', 'root', 'root', 'news', 3306);
    if ($mysqli->connect_errno) {
        die('Cannot connect to mySQL');
    }
    return $mysqli;
}

function createArticle($title, $text, $author, $date)
{
    $mysqli = createConnection();
    $insert_query = "INSERT INTO news (Title, Text, Author, Date) VALUES ('$title', '$text', '$author', '$date');";;
    if ($mysqli->query($insert_query) === TRUE) {
        return 1;
    } else {
        echo $mysqli->error;
        return 0;
    }
}

function removeArticle($id)
{
    $mysqli = createConnection();
    $remove_query = 'DELETE FROM news WHERE ID = ' . $id;
    if ($mysqli->query($remove_query) === TRUE) {
        echo '<font color="green"> Запись удалена!</font>';
        echo show_news_admin();
        return '';
    } else {
        echo $mysqli->error;
        return '<font color="red">Я что-то сломала</font>>';
    }
}

function add_news()
{
    $out = '';
    $out .= '<form method="POST">';
    $out .= '<table cellpadding="0" cellspacing="0" border="0" width="80%" align="center">';
    $out .= '<tr>';
    $out .= '<td><b>Заголовок новости:</b>';
    $out .= '<td><input type="text" name="Title" style="width:98%" value="" /></td>';

    $out .= '<tr>';
    $out .= '<td><b>Поведай новость:</b>';
    $out .= '<tr><td colspan="2"><textarea name="Text" 
style="height:150px; width:98%;"></textarea></td></tr>';
    $out .= '<tr>';

    $out .= '<tr>';
    $out .= '<td><b>Автор:</b>';
    $out .= '<tr><td colspan="2"><textarea name="Author" 
style="height:30px; width:33%;"></textarea></td></tr>';
    $out .= '<tr>';


    $out .= '<tr><td colspan="2" align="center"><input
 type="submit" name="add" value="Добавить новость"></td></tr>';

    $out .= '</table>';
    $out .= '</form>';

    return $out;

}

function show_news_admin()
{
    $table = '';

    $mysqli = createConnection();
    $select_articles = 'SELECT * from news ORDER BY ID DESC';
    $qur = $mysqli->query($select_articles);
    $qur->data_seek(0);

    if ($qur) {
        $kol = mysqli_num_rows($qur);
        if ($kol) {
            $table .= '<br>';
            $table .= '<table cellpadding="0" cellspacing="0" border="0" width="80%"
        align="center">';
            $table .= '<tr><a colspan="3" align="right"><a href="?des=add">Добавить запись</a></td></tr>';
            $table .= '<br>';
            $table .= '<tr><a colspan="3" align="right"><a href="?des=view">Просмотр ленты</a></td></tr>';
            $table .= '<tr><td width="20%" align="left"><b>Заголовок:</b></td><td width="20%"
        align="left"><b>Новость:</b></td><td width="20%" align="left"><b>Автор:</b></td><td width="20%"
        align="left"><b>Дата:</b></td><tr>';
            while ($rez = mysqli_fetch_assoc($qur)) {
                $table .= '<tr>';
                $table .= '<td>' . stripslashes($rez['Title']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Text']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Author']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Date']) . '</td>';
                $table .= '<td align="right"><a href="?des=del&id=' . $rez['ID'] . '">Удалить.</a></td></tr>';
            }
            $table .= '</table>';
        } else $table = '<font color="red">Нет записей </font>';
    } else $table = '<font color="red">Ошибка запроса </font>';

    return $table;
}

function show_news()
{
    $table = '';

    $mysqli = createConnection();
    $select_articles = 'SELECT * from news ORDER BY ID DESC';
    $qur = $mysqli->query($select_articles);
    $qur->data_seek(0);

    if ($qur) {
        $kol = mysqli_num_rows($qur);
        if ($kol) {
            $table .= '<table cellpadding="0" cellspacing="0" border="0" width="80%"
        align="center">';
            $table .= '<tr><a colspan="3" align="right"><a href="?des=admin">Админка</a></td></tr>';
            $table .= '<tr><td width="20%" align="left"><b>Заголовок:</b></td><td width="20%"
        align="left"><b>Новость:</b></td><td width="20%" align="left"><b>Автор:</b></td><td width="20%"
        align="left"><b>Дата:</b></td><tr>';
            while ($rez = mysqli_fetch_assoc($qur)) {
                $table .= '<tr>';
                $table .= '<td>' . stripslashes($rez['Title']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Text']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Author']) . '</td>';
                $table .= '<td>' . stripslashes($rez['Date']) . '</td>';
            }
            $table .= '</table>';
        } else $table = '<font color="red">Нет записей </font>';
    } else $table = '<font color="red">Ошибка запроса </font>';

    return $table;
}


$mysqli = createConnection();

if ($_GET['des'] == 'add') {
    echo add_news();
    $title = addslashes(htmlspecialchars($_POST['Title']));
    $text = addslashes(htmlspecialchars($_POST['Text']));
    $author = addslashes(htmlspecialchars($_POST['Author']));
    $date = date('Y-m-d H:i:s');

    if ($title != ''  && $text != '' &&  $author != '') {
        createArticle($title, $text, $author, $date);
        echo show_news_admin(); }
    else echo '<font color="red">Не все поля заполнены!</font>';
}
if ($_GET['des'] == 'del') echo removeArticle($_GET["id"]);
if ($_GET['des'] == 'view') echo show_news();
if ($_GET['des'] == 'admin') echo show_news_admin();

