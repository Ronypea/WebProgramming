<?php

// Соединение с базой данных
$db = mysqli_connect('localhost', 'root', 'root', 'news');

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

echo $out;

if (isset ($_POST['add'])) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}

$title = addslashes(htmlspecialchars($_POST['Title']));
$text = addslashes(htmlspecialchars($_POST['Text']));
$author = addslashes(htmlspecialchars($_POST['Author']));
$data = mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'));

$sql = 'INSERT INTO news VALUES (0,' . $title . ',' . $text . ',' . $author . ',' . $data . ')';
$query = mysqli_query($db, $sql);
if ($query) echo 'Новость добавлена';
mysqli_close($db);

