<?php require '../inc/header.php' ?>
<?php require_once '../config/User.php' ?>
<h1>Home</h1>

<?php  
$apple = new User();
$apple->set_details('Imran Khan', 'imrankhan.in3@gmail.com'); // Set property values
$apple->get_details();
?>

<?php require '../inc/footer.php'?>