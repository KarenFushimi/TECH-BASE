<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
<title>掲示板</title>
</head>

<body>
<?php
/*
データベースへの接続
*/
$dsn='mysql:dbname=(データベース名);host=localhost';
$user = '(ユーザー名)';
$password = '(パスワード)';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

/*
データベース内にテーブルを作成
*/
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT,"
. "name char(32),"
. "comment TEXT"
.");";
$stmt = $pdo->query($sql);

/*
入力済みのデータの格納
*/

$rows = array();

$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	$rows[] = $row;
	//echo $rows[0]['id'];
	//echo $rows[0]['id'].',';
	//echo $rows[0]['name'].',';
	//echo $rows[0]['comment'].'<br>';
}

/*
最後の番号に続けて記入
*/

//$_POST['id'];
//$rows[count($rows)-1]['id'];
//if(empty($_POST['id'])){
	$num = $rows[count($rows)-1]['id'];
//}else{
//	$num = $_POST['id'];
//}

if(!empty($_POST['id_edit'])){
	$numEdit=$_POST['id_edit'];
}else{
	$numEdit = "なし";
}

?>

名前<br/>
	<form action="http://tt-936.99sv-coco.com/mission_4-1.php" method = "post">
	<input type = "text" placeholder="名前" name ="name"/><br/>
コメント<br/>
	<textarea name = "comment"　rows ="4"></textarea></br>
	<input type = "hidden" name = "id_edit1" value="<?php echo $numEdit; ?>" />
	<input type="submit" value="投稿する"/><br/><br/>
削除番号<br/>
	<input type = "text" placeholder="削除指定番号" name ="id_delete"/><br/>
	<input type="submit" value="削除する"/><br/><br/>
編集番号<br/>
	<input type = "text" placeholder="編集番号" name ="id_edit"/><br/>
	<input type="submit" value="編集する"/><br/><br/>
	<input type="submit" name="delete_table" value="テーブル削除"/>
	<br/>

</form>

<?php

//echo $_POST[delete_table]."<br/>";
if(!empty($_POST[delete_table])){
	echo "<br/>テーブルを削除しました";
	$sql = 'DROP TABLE tbtest';
	$stmt = $pdo->query($sql);
	exit();
}

/*
foreach ($rows as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
}
*/

/*
テーブルの削除
*/

$sql = 'DROP TABLE tbtest';
$stmt = $pdo->query($sql);



/*
データベース内にテーブルを作成
*/
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT,"
. "name char(32),"
. "comment TEXT"
.");";
$stmt = $pdo->query($sql);

/*
作成したテーブルに一度消したデータを入力
*/
$sql = $pdo -> prepare("INSERT INTO tbtest (id,name, comment) VALUES (:id,:name, :comment)");
foreach($rows as $row){
	$sql -> bindParam(':id', $row['id'], PDO::PARAM_STR);
	$sql -> bindParam(':name', $row['name'], PDO::PARAM_STR);
	$sql -> bindParam(':comment', $row['comment'], PDO::PARAM_STR);
//$id = 3;
//$name = 'LyLy4';
//$comment = 'Bow4!'; //好きな名前、好きな言葉
	$sql -> execute();
}

/*
作成したテーブルにinsertを行ってデータを入力
*/
/*
ただし削除欄が空欄で、コメント欄にコメントがある時のみ
*/
$num++;
echo "<br/>";
if($_POST['id_edit1']=="なし"&&empty($_POST['id_edit'])&&empty($_POST['id_delete'])&&!empty($_POST['comment'])){
	echo $_POST['name']."が".$_POST['comment']."を投稿しました<br/>";
	$sql = $pdo -> prepare("INSERT INTO tbtest (id,name, comment) VALUES (:id,:name, :comment)");
	$sql -> bindParam(':id', $num, PDO::PARAM_STR);
	$sql -> bindParam(':name', $_POST['name'], PDO::PARAM_STR);
	$sql -> bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
//$id = 3;
//$name = 'LyLy4';
//$comment = 'Bow4!'; //好きな名前、好きな言葉
	$sql -> execute();
}elseif(!empty($_POST['id_edit'])){
	$numEdit = $_POST['id_edit'];
	echo $numEdit."を編集中<br/>";
}elseif($_POST['id_edit1']!=="なし"&&!empty($_POST['id_edit1'])){
	echo $_POST['id_edit1']."を編集しました<br/>";
	$sql = 'update tbtest set name=:name,comment=:comment where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id', $_POST['id_edit1'], PDO::PARAM_STR);
	$stmt -> bindParam(':name', $_POST['name'], PDO::PARAM_STR);
	$stmt -> bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
	$stmt->execute();
}elseif($_POST['id_delete']){
	echo $_POST['id_delete']."を削除しました<br/>";
}else{
	echo "<br/>";
}
echo "<br/>";

/*
データの削除
*/
$id = $_POST['id_delete'];
$sql = 'delete from tbtest where id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id',$id, PDO::PARAM_INT);
$stmt->execute();

/*
データの削除
*/
$id = '';
$sql = 'delete from tbtest where id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id',$id, PDO::PARAM_INT);
$stmt->execute();

/*
入力したデータをselectで表示
*/
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
	//echo 'row'.$row.'<br>';
}






//echo $rows[count($rows)-1]['id'];
/*
echo '$rows[10][id]'.': ';
echo $rows[10]['id'].',';
echo $rows[0]['name'].',';
echo $rows[0]['comment'].'<br>';

echo '$rows[11][id]'.': ';
echo $rows[11]['id'].',';
echo $rows[1]['name'].',';
echo $rows[1]['comment'].'<br>';

*/

?>
</body>


<html/>