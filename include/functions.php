<?php
require_once('_define.php');
// define 

//fin defin
function session_valid_id($session_id)
{
    return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
}

function var_dump_pre($var): void
{
    echo '<pre>', var_dump($var), '</pre>';
}


// function upload_photo(string $name): array
// {
//     // var_dump($_SESSION['url_photo']); 

//     // Gestion des erreurs
//     $error_upload = $_FILES[$name]['error'];

//     switch ($error_upload) {
//         case 1:
//             $error = true;
//             $msg_error = "Taille max du fichier 2M ( upload_max_filesize directive in php.ini";
//             break;
//             // ne pas se servir de MAX_FILE_SIZE: facilement detounable
//         case 2:
//             $error = true;
//             $msg_error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
//             break;
//         case 3:
//             $error = true;
//             $msg_error = "The uploaded file was only partially uploaded. ";
//             break;
//         case 4:
//             $error = true;
//             $msg_error = "No file was uploaded.";
//             break;
//         case 6:
//             $error = true;
//             $msg_error = "Missing a temporary folder. ";
//             break;
//         case 7:
//             $error = true;
//             $msg_error = " Failed to write file to disk";
//             break;
//         case 8:
//             $error = true;
//             $msg_error = " A PHP extension stopped the file upload";
//             break;

//             /* ou bien
//          case 2:
//          case 3:
//          case 4:
//          case 6:
//          case 7:
//          case 8:
//             $error = true;
//             $msg_error = "Problème de téléchargement";
//             break;
//          */

//             // Pas d'erreur
//         case 0:
//             // controle de la taille du fichier
//             $size = $_FILES[$name]['size'];
//             if ($size > MAX_SIZE_UPLOAD) {
//                 $error = true;
//                 $msg_error = "La taille de votre fichier ne doit pas dépasser " . round(MAX_SIZE_UPLOAD / 1024, 0) . "Ko";
//             }

//             // controle du type de fichier
//             $type =  $_FILES[$name]['type'];
//             if (stristr($type, 'jpg') === false &&  stristr($type, 'jpeg') === false) {
//                 $error = true;
//                 $msg_error = "Le fichier doit être au format jpg ou jpeg";
//             }

//             // controle de la dimension
//             $photo_tmp = $_FILES[$name]['tmp_name'];
//             // destructuration
//             list($width, $height) = getimagesize($photo_tmp);

//             if ($width > 10000) {
//                 $error = true;
//                 $msg_error = "La photo est trop grande(" . $width . "px) <br> Largeur max autorisée:" . MAX_WIDTH . "px";
//             }
//     } // fin switch 

//     // Enregistrement de la photo
//     if (empty($error)) {
//         // creation d'un dossier s'il n'existe pas
//         if (!file_exists('upload')) {
//             //var_dump('upload don\'t exist');
//             $upload = mkdir('upload/', 0777); // renvoie true si ok
//             if (!$upload) {
//                 $error = true;
//                 die("Dossier d'upload non créé"); // die stop l'execution du script;
//                 $msg_error = "Dossier d'upload non créé";
//             }
//         } else {
//             $upload_dir = "upload/";
//             // on recupere le nom du fichier
//             $photo_name = basename($_FILES[$name]['name']);
//             // tout en minuscule
//             $photo_name = strtolower($photo_name);
//             // on supprime les espaces
//             $photo_name = str_replace(' ', '', $photo_name);
//             // traitement des caractères spéciaux
//             $photo_name = replace_special_caract($photo_name);
//             // URL de la photo
//             $upload_file = $upload_dir . $photo_name;

//             // si on modifie la photo: on supprime l'ancienne
//             /*
//              if(isset($_SESSION['url_photo'])) {                
//                 if($upload_file !== $_SESSION['upload_photo']) {
//                     unlink($_SESSION['url_photo']);
//                 }
//              }
//              */

//             // deplacement de la photo du dossier temporaire vers le dossier créé
//             $move = move_uploaded_file($_FILES[$name]['tmp_name'], $upload_file);
//             // controle du deplacement
//             if ($move) {
//                 $tab_response = [true, $upload_file];
//             } else {
//                 die("Problème de téléchargement");
//             }
//         }
//         // si erreurs
//     } else {
//         $tab_response = [false, $msg_error];
//     }

//     return $tab_response;
// }


function slugify($text)
{
    // Remplace les caractères spéciaux par des tirets
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Translittère les caractères non ASCII en ASCII
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Supprime les caractères non autorisés
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Supprime les tirets en début et fin de chaîne
    $text = trim($text, '-');

    // Convertit en minuscules
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function removeAccents($text)
{
    $text = htmlentities($text, ENT_COMPAT, 'UTF-8');
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|lig);/', '$1', $text);
    $text = html_entity_decode($text, ENT_COMPAT, 'UTF-8');
    return $text;
}

// $banner utilisé dans le fichier header.php pour afficher ou pas la banner
// $aside utilisé pour corriger le CSS dans le head
function get_header(bool $banner, bool $aside): string
{
    if (file_exists((PARTS . 'header.php'))) {
        $include = include_once(PARTS . 'header.php');
    } else {
        $include = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Free Web tutorials">
            <title>Projet initiation HTML/CSS</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/animations.css">
        </head>
        <body>';
    }
    return $include;
}

function get_footer(): string
{
    if (file_exists((PARTS . 'footer.php'))) {
        $include = include_once(PARTS . 'footer.php');
    } else {
        $include = '
        </body>
        </html>';
    }
    return $include;
}

function get_aside(): string
{
    if (file_exists((PARTS . 'aside.html'))) {
        $include = include_once(PARTS . 'aside.html');
    } else {
        $include = '<img src="img/logo-via.jpg" alt="logo via formation">';
    }
    return $include;
}

function replace_special_caract(string $string): string
{
    $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
    $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
    $MaChaine = str_replace($search, $replace, $string);
    //return utf8_encode($string); // attention methode deprecié dpuis PHP 8.2.0.
    return iconv('ISO-8859-1', 'UTF-8', $string);
}

function display_label(string $key): string
{
    switch ($key) {
        case 'pseudo':
            $key = 'Pseudo';
            break;
        case 'pays':
            $key = 'Pays';
            break;
        case 'comment':
            $key = 'Commentaires';
            break;
        case 'date_entree':
            $key = 'Date d\'entrée';
            break;
        case 'age':
            $key = 'Age';
            break;
        case 'tel':
            $key = 'Téléphone';
            break;
        case 'website':
            $key = 'Site web';
            break;
        case 'tab_profil':
            $key = 'Profil';
            break;
        case 'tab_niveau':
            $key = 'Niveau';
            break;
        case 'email':
            $key = 'Email';
            break;
        default:
            $key = "";
    }
    return $key;
}

// return: error message and bool error
// if no error -> empty array
function valid_form(): array
{
    //var_dump($_POST);
    if ($_POST) {
        //var_dump($_POST);
        $msg_error = "Veuillez renseigner les champs suivant(s)<br>";
        // traitement des input, select, textarea
        foreach ($_POST as $key => $value) {
            if (!is_array($value)) {
                ${$key} = htmlentities($value);
            }
            ${$key} = $value;
            if (empty($value)) {
                $key = display_label($key);
                $msg_error .= $key;
                $msg_error .= "<br>";
                $error = true;
            }
        }

        // Nombre de caract max dans le textarea
        if (strlen($comment) > MAX_CARACT_TEXTAREA) {
            $msg_error .= "Trop de caractères dans les commentaires<br>";
            $error = true;
        }

        // Checkbox
        if (empty($tab_profil)) {
            $msg_error .= 'Scientifique ou artistique?<br> ';
            $error = true;
        }

        // bt radio profil
        if (empty($tab_niveau)) {
            $msg_error .= 'Votre niveau?<br> ';
            $error = true;
        }

        // traitement de l'upload du fichier    
        if (isset($_FILES)) {

            if (empty($_FILES['photo']['tmp_name'])) {
                $msg_error .= 'Votre photo? ';
                $error = true;
            } else {
                $tab_response = upload_photo('photo');
            }

            // reponse de la fonction upload_photo ds functions.php
            if (isset($tab_response) && $tab_response[0] === false) {
                $error = true;
                $msg_error .= $tab_response[1];
            }
        } // fin if isset $_FILES



        // traitement du formulaire si pas d'erreur
        if (empty($error) && isset($tab_response) && $tab_response[0] === true) {
            $tab_return_errors = [];
            // recup de l'url de la photo du tableau de retour de la fonction upload_photo
            $url_upload = $tab_response[1];
            // mises en session
            $_SESSION = $_POST;
            $_SESSION['url_photo'] = $url_upload;
        } else {
            $tab_return_errors = [$error, $msg_error];
        }
    } // end if POST 

    return $tab_return_errors;
}

// param $name: name of file form
// return array : (2)
// [false, 'error_message']
// [true, 'url_upload_file']


function date_uk_fr(string $date): string
{
    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    //$date_fr =  $formatter->format($date); 
    $formatter->setPattern('EEEE d MMMM y');
    return ucwords($formatter->format($date));
    //return $date_fr;
}

// return array : (2)
// [false, 'email_send']
// [true, 'msg_error']
function send_email(string $from, string $to, string $subject, string $message): array
{

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf8';
    $headers[] = 'From: ' . $from;

    $sendmail = mail($to, $subject, $message, implode("\r\n", $headers));
    if (!$sendmail) {
        $error = true;
        $msg_error = "Problème d'envoi de l'email";
    } else {
        $error = false;
        $msg_error = "Merci pour votre inscription, à très bientôt!<br>Un email récapitulatif vous a été envoyé.";
    }

    $tab_email_response = [$error, $msg_error];
    return $tab_email_response;
}
