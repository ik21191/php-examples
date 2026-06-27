<?php
require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/*
$queryString = $_SERVER['QUERY_STRING'];
echo "<script>console.log($queryString);</script>";
*/

$uri = explode( '/index.php', $uri ); // /saiitech/api/index.php/user/list?

$user_action_path =  $uri[1];
$user_action_array = explode("/", $user_action_path);

$user = $user_action_array[1];
$action = $user_action_array[2];


//echo "<script>console.log(user);</script>";

if ($user != 'user' || $action != 'list') {
    header("HTTP/1.1 404 Not Found");
    exit();
}
/*
if ((isset($uri[4]) && $uri[4] != 'user') || !isset($uri[5])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
*/
require PROJECT_ROOT_PATH . "/Controllers/Api/UserController.php";
$objFeedController = new UserController();
$strMethodName = $action . 'Action';
$objFeedController->{$strMethodName}();
?>