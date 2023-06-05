<?php
// define 
define('PARTS', 'pages/parts/');
$max_size_upload = 100 * 1024 * 1024; // 1Mo 
define('MAX_SIZE_UPLOAD', $max_size_upload);
define('MAX_WIDTH', 1024); // largeur max de la photo en px
define('MAX_CARACT_TEXTAREA', 5);
//fin defin
function session_valid_id($session_id)
{
    return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
}

function var_dump_pre($var): void
{
    echo '<pre>', var_dump($var), '</pre>';
}


function upload_photo(string $name): array
{
    // var_dump($_SESSION['url_photo']); 

    // Gestion des erreurs
    $error_upload = $_FILES[$name]['error'];

    switch ($error_upload) {
        case 1:
            $error = true;
            $msg_error = "Taille max du fichier 2M ( upload_max_filesize directive in php.ini";
            break;
            // ne pas se servir de MAX_FILE_SIZE: facilement detounable
        case 2:
            $error = true;
            $msg_error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
            break;
        case 3:
            $error = true;
            $msg_error = "The uploaded file was only partially uploaded. ";
            break;
        case 4:
            $error = true;
            $msg_error = "No file was uploaded.";
            break;
        case 6:
            $error = true;
            $msg_error = "Missing a temporary folder. ";
            break;
        case 7:
            $error = true;
            $msg_error = " Failed to write file to disk";
            break;
        case 8:
            $error = true;
            $msg_error = " A PHP extension stopped the file upload";
            break;

            /* ou bien
         case 2:
         case 3:
         case 4:
         case 6:
         case 7:
         case 8:
            $error = true;
            $msg_error = "Problème de téléchargement";
            break;
         */

            // Pas d'erreur
        case 0:
            // controle de la taille du fichier
            $size = $_FILES[$name]['size'];
            if ($size > MAX_SIZE_UPLOAD) {
                $error = true;
                $msg_error = "La taille de votre fichier ne doit pas dépasser " . round(MAX_SIZE_UPLOAD / 1024, 0) . "Ko";
            }

            // controle du type de fichier
            $type =  $_FILES[$name]['type'];
            if (stristr($type, 'jpg') === false &&  stristr($type, 'jpeg') === false) {
                $error = true;
                $msg_error = "Le fichier doit être au format jpg ou jpeg";
            }

            // controle de la dimension
            $photo_tmp = $_FILES[$name]['tmp_name'];
            // destructuration
            list($width, $height) = getimagesize($photo_tmp);

            if ($width > 10000) {
                $error = true;
                $msg_error = "La photo est trop grande(" . $width . "px) <br> Largeur max autorisée:" . MAX_WIDTH . "px";
            }
    } // fin switch 

    // Enregistrement de la photo
    if (empty($error)) {
        // creation d'un dossier s'il n'existe pas
        if (!file_exists('upload')) {
            //var_dump('upload don\'t exist');
            $upload = mkdir('../upload/', 0777); // renvoie true si ok
            if (!$upload) {
                $error = true;
                die("Dossier d'upload non créé"); // die stop l'execution du script;
                $msg_error = "Dossier d'upload non créé";
            }
        } else {
            $upload_dir = "../upload/";
            // on recupere le nom du fichier
            $photo_name = basename($_FILES[$name]['name']);
            // tout en minuscule
            $photo_name = strtolower($photo_name);
            // on supprime les espaces
            $photo_name = str_replace(' ', '', $photo_name);
            // traitement des caractères spéciaux
            $photo_name = replace_special_caract($photo_name);
            // URL de la photo
            $upload_file = $upload_dir . $photo_name;

            // si on modifie la photo: on supprime l'ancienne
            /*
             if(isset($_SESSION['url_photo'])) {                
                if($upload_file !== $_SESSION['upload_photo']) {
                    unlink($_SESSION['url_photo']);
                }
             }
             */

            // deplacement de la photo du dossier temporaire vers le dossier créé
            $move = move_uploaded_file($_FILES[$name]['tmp_name'], $upload_file);
            // controle du deplacement
            if ($move) {
                $tab_response = [true, $upload_file];
            } else {
                die("Problème de téléchargement");
            }
        }
        // si erreurs
    } else {
        $tab_response = [false, $msg_error];
    }

    return $tab_response;
}
function replace_special_caract(string $string): string
{
    // Remplacer les caractères accentués
    $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
    $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
    $string = str_replace($search, $replace, $string);

    // Remplacer les espaces par des tirets
    $string = str_replace(' ', '-', $string);

    // Mettre la première lettre en minuscule
    $string = lcfirst($string);

    // Enlever les apostrophes
    $string = str_replace("'", '', $string);

    return $string;
}


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
