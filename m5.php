<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
    <form action="" method="post">
        <input type="text"  value="" name="name" placeholder="名前を入力してください"> 
        <input type="number" name="delnum" style="position:relative;left:50px;top:0px;" placeholder="数字を入力してください"> 
        <input type="submit" name="submit" style="position:relative;left:50px;top:0px;" value="削除">
        <input type="number" name="ednum" style="position:relative;left:50px;top:0px;" placeholder="数字を入力してください">
        <input type="submit" name="submit" style="position:relative;left:50px;top:0px;" value="編集"><br>
        <input type="text"  value="" name="com" placeholder="コメントを入力してください"><br>
        <input type="text"  value="" name="pass" placeholder="パスワードを入力してください">
        <input type="submit" name="submit">
    </form>
    <?php
    $date=date("Y-m-d H:i:s");
    $dsn = 'mysql:dbname=XXXdb;host=localhost';
    $user = 'XXXuser';
    $password = 'XXXpassword';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql="create table if not exists tb"."("."id int auto_increment primary key,"."name char(32),"."comment TEXT,"."password char(32),"."date datetime".");"; //命令を入れる変数
    $stmt=$pdo -> query($sql);
    $edpass=array();
    $delpass=array();
    if(!empty($_POST["ednum"]))        //パスワード認証
    {
    $sql = 'SELECT password FROM tb where id=:id;';
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindparam(":id",$_POST["ednum"],PDO::PARAM_INT);
    $stmt -> execute();
    $result=$stmt -> fetchAll();
     foreach($result as $row)
    {
        $edpass=$row["password"];
    }
    }
    if(!empty($_POST["delnum"]))        //パスワード認証
    {
    $sql = 'SELECT password FROM tb where id=:id';
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindparam(":id",$_POST["delnum"],PDO::PARAM_INT);
    $stmt -> execute();
    $result=$stmt -> fetchAll();
    foreach($result as $row)
    {
        $delpass=$row["password"];
    }
    }
    
    
    
    
    if(!empty($_POST["ednum"]) && !empty($_POST["name"]) && !empty($_POST["com"]) && !empty($_POST["pass"]) && !empty($edpass) && ($edpass== $_POST["pass"]))      //編集処理
    {

        $ednum=$_POST["ednum"];
        $name=$_POST["name"];
        $com=$_POST["com"];
        $pass=$_POST["pass"];
        $stmt=$pdo -> prepare("update tb set name=:name,comment=:comment,password=:password,date=:date where id=:id");
        $stmt -> bindparam(":id",$ednum, PDO::PARAM_INT);
        $stmt -> bindparam(":name",$name, PDO::PARAM_STR);
        $stmt -> bindparam(":comment",$com, PDO::PARAM_STR);
        $stmt -> bindparam(":password",$pass, PDO::PARAM_STR);
        $stmt -> bindparam(":date",$date,PDO::PARAM_STR);//新しいパスワード欄作ればはここの変数変えるだけでよい
        $stmt -> execute();

    }elseif(!empty($_POST["name"]) && !empty($_POST["com"]) && !empty($_POST["pass"]))
    {
        $name = $_POST["name"];
        $com=$_POST["com"];
        $pass=$_POST["pass"];

        $stmt=$pdo -> prepare("insert into tb(name,comment,password,date) values(:name,:comment,:password,:date)");
        $stmt -> bindparam(":name",$name, PDO::PARAM_STR);
        $stmt -> bindparam(":comment",$com, PDO::PARAM_STR);
        $stmt -> bindparam(":password",$pass, PDO::PARAM_STR);
        $stmt -> bindparam(":date",$date,PDO::PARAM_STR);
        $stmt -> execute();
    }
    
    if(!empty($_POST["delnum"]) && !empty($delpass) && $delpass==$_POST["pass"])
    {
        $delnum=$_POST["delnum"];
        $stmt=$pdo -> prepare("delete from tb where id=:id");
        $stmt -> bindparam(":id",$delnum,PDO::PARAM_INT);
        $stmt -> execute();
    }
    
    if((!empty($_POST["name"]) && !empty($_POST["com"])) || !empty($_POST["delnum"]))
    {
    $sql = 'SELECT * FROM tb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
    }
     
    ?>
</body>
</html>