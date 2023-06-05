<?php

declare(strict_types=1);
ob_start(); // mise en tampon pour permettre les redirections
// avant session_star()
session_id();
session_start();
error_reporting(E_ALL);
//ini_set('display_errors', 1);
// require_once('../include/_define.php');
// require_once('../include/functions.php');
require_once('include/functions.php');
require_once('include/_connection.php');


if ($_GET) {
    $page = $_GET['page'];
    switch ($page) {
        case 'home':
            $page = 'home.php';
            $menu = true;
            break;
        case 'insert_products':
            $page = 'insert_products.php';
            $menu = true;
            break;
        case 'insert_categorie':
            $page = 'insert_categorie.php';
            $menu = true;
            break;
        case 'modif_products':
            $page = 'modif_products.php';
            $menu = true;
            break;
        case 'modif_categorie':
            $page = 'modif_categorie.php';
            $menu = true;
            break;
        case 'delete_products':
            $page = 'delete_products.php';
            $menu = true;
            break;
        case 'delete_categorie':
            $page = 'delete_categorie.php';
            $menu = true;
            break;
        case 'inscription':
            $page = 'inscription.php';
            $menu = true;
            break;
        case 'display_products':
            $page = 'display_products.php';
            $menu = true;
            break;
        case 'display_categorie':
            $page = 'display_categorie.php';
            $menu = true;
            break;
        default:
            $page = 'login.php';
    }
} else {
    $page = 'login.php';
}
?>


<?php include_once('pages/parts/header.php'); ?>

<?php include_once('pages/' . $page); ?>

<?php ob_end_flush(); // effacement de la memoire tampon 
?>

<?php include_once('pages/parts/footer.php'); ?>



        