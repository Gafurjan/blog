<?
define("HOST", "localhost");
define("DBNAME", "myblog");
define("DBUSER", "root");
define("DBPASSWORD", "root");

try{
    $db = new PDO('mysql: host='.HOST.'; dbname='. DBNAME, DBUSER, DBPASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
}catch(PDOException $e){
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}

// $id = $_GET['id'];

// $stmt = $db->query("SELECT * FROM articles WHERE id = $id");

// echo'<pre>';
// var_dump($stmt->fetch());
// echo'</pre>';

$id = 2;

$db->beginTransaction();

$deleteLikes = $db->prepare("DELETE FROM likes WHERE id_art = ?");
$deleteLikes = $deleteLikes->execute(array($id));

echo'<pre>';
var_dump($deleteLikes);
echo'</pre>';


$deleteComments = $db->prepare("DELETE FROM comments WHERE id_article = ?");
$deleteComments = $deleteComments->execute(array($id));

echo'<pre>';
var_dump($deleteComments);
echo'</pre>';

$deleteImages = $db->prepare("DELETE FROM images WHERE id_article = ?");
$deleteImages = $deleteImages->execute(array($id));

echo'<pre>';
var_dump($deleteImages);
echo'</pre>';

$result = $db->prepare("DELETE FROM articles WHERE id = ?");
$result = $result->execute(array($id));

echo'<pre>';
var_dump($result);
echo'</pre>';

if($deleteLikes && $deleteComments && $deleteImages && $result){
    $db->commit();
    echo"commit";
    die();
}else{
    $db->rollback();
    echo"rollback2";
}