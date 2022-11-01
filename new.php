<?php


$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=dakoutros", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$sql = "SELECT * FROM `test`";
$results = $conn->query($sql);

foreach($results as $key => $value){
    echo "$key $value - <br>";
    var_dump($value[0]);
    var_dump($value['id']);
    echo "$key $value - <br><br>";
    var_dump($value[1]);
    var_dump($value['name']);

}
var_dump($results);
exit;


if(isset($_POST['add_user'])){
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $new_user = "INSERT INTO `test` (`id`, `name`, `email`, `password`) VALUES (NULL, '$name', '$email', '$password');";
    $conn->query($new_user);

    var_dump($_POST);
var_dump($_GET);
// var_dump($_GET['name']);
var_dump($_POST['name']);


}else{
    echo 'no iseeet found';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <form action="" method="post" class="form-example">
        <div class="form-example">
            <label for="name">Enter your name: </label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="form-example">
            <label for="email">Enter your email: </label>
            <input type="email" name="email" id="email">
        </div>
        <label for="lname">password</label><br>
        <input type="text" class="form-control" id="lname" name="password">
        <div class="form-example">
            <input type="submit" name="add_user" value="Submit!">
        </div>
    </form>
</body>

</html>
<pre>

    <?php 

?>
</pre>