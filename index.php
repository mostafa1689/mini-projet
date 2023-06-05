<?php

declare(strict_types=1);
ob_start(); // mise en tampon pour permettre les redirections
session_start();
error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('include/_define.php');
require_once('include/functions.php');
require_once('include/_connection.php');

if ($_GET) {
    $page = $_GET['page'];
    switch ($page) {
        case 'home':
            $page = 'home.php';
            $banner = true;
            $aside = true;
            break;
        case 'boutique_products':
            $page = 'boutique_products.php';
            $banner = false;
            $aside = false;
            break;
        case 'boutique_categorie':
            $page = 'boutique_categorie.php';
            $banner = false;
            $aside = false;
            break;
        case 'display_products':
            $page = 'display_products.php';
            $banner = false;
            $aside = false;
            break;
        case 'contact':
            $page = 'contact.php';
            $banner = false;
            $aside = false;
            break;
        case 'contact-confirm':
            $page = 'contact-confirm.php';
            $banner = false;
            $aside = false;
            break;
        default:
            $page = 'home.php';
            $banner = true;
            $aside = true;
    }
} else {
    $page = 'home.php';
    $banner = true;
    $aside = true;
}
?>


<?php get_header($banner, $aside); ?>

<?php include_once('pages/' . $page); ?>

<?php ob_end_flush(); // effacement de la memoire tampon 
?>


 <?php if ($aside === true) : ?>
    <?php get_aside(); ?>
 <?php endif; ?> 

 <?php get_footer(); ?>
        