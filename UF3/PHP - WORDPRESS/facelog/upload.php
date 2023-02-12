<?php

require_once __DIR__ . '/../../../wp-load.php';
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');

date_default_timezone_set('Europe/Madrid');

/** Sets up the WordPress Environment. */
define('WP_USE_THEMES', false); /* Disable WP theme for this file (optional) */

$status = "S'ha produït un error inesperat."; // per defecte error genèric

$processGoodImgs = substr(plugin_dir_path(__FILE__), 0, -1) . "\uploads\\";
$process = substr(plugin_dir_path(__FILE__), 0, -1) . "\uploads\\tmp\\";
$targetFile = $process . basename($_FILES["imageupload"]["name"]);
$targetFileGoodImages = $processGoodImgs . basename($_FILES["imageupload"]["name"]);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$filesystem = new WP_Filesystem_Direct('');

/**
 * Tractament dels arxius pujats dins el formulari de Facelog (add-log).
 * Amb diferents missatges d'error, si es detecten.
 * 
 * @return void
 */

function facelog_pujar_imatge(): void
{

  global $status;
  global $targetFile;
  global $targetFileGoodImages;
  global $imageFileType;
  global $uploadOk;

  // Check if image file is a actual image or fake image
  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["imageupload"]["tmp_name"]);
    if ($check !== false) {
      // Allow certain file formats
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $status = "El fitxer no té l'extenció adequada: JPG, JPEG, PNG";
        $uploadOk = 0;
      } else {
        $uploadOk = 1;
      }
    } else {
      $status = "El fitxer no és una imatge.";
      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($targetFile) || file_exists($targetFileGoodImages)) {
    $status = "El fitxer ja existeix.";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["imageupload"]["size"] > 500000) {
    $status = "El fitxer pesa massa.";
    $uploadOk = 0;
  }

  // if everything is ok, try to upload file
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["imageupload"]["tmp_name"], $targetFile)) {

      facelog_curl_img($targetFile, $_FILES["imageupload"]["name"], $imageFileType);
    } else {
      $status = "Hi ha hagut un error al pujar el fitxer.";
    }
  }
}

facelog_pujar_imatge();


/**
 * Rotació de la imatge pujada per l'usuari. Aquesta rotació es fa perquè la imatge quedi en la posició correcta.
 * 
 * @param string $ullEY
 * @param string $ullDY
 * @param string $ullEX
 * @param string $ullDX
 * @param GdImage $img
 * @return GDImage
 */

function facelog_rotar_imatge(string $ullEY, string $ullDY, string $ullEX, string $ullDX, GdImage $img): GDImage
{

  $distanciaY = $ullDY - $ullEY;
  $distanciaX = $ullDX - $ullEX;

  $angle = atan2($distanciaY, $distanciaX);
  $angle = fmod($angle + 2 * M_PI, 2 * M_PI);
  $angle = rad2deg($angle);

  $imgRotated = imagerotate($img, $angle, 0);

  return $imgRotated;
}

/**
 * Sobreescriu la imatge pujada, amb la imatge donada que s'ha modificat.
 * 
 * @param string $fileType
 * @param string $targetFile
 * @param GdImage $img
 * @return void
 */

function facelog_guardar_img(string $fileType, string $targetFile, GdImage $img): void
{
  if ($fileType == "jpg" || $fileType == "jpeg") {
    imagejpeg($img, $targetFile);
  } else if ($fileType == "png") {
    imagepng($img, $targetFile);
  }
}

/**
 * Obtenir certes dades de la imatge donada amb ajuda de l'API Facedetection.
 * 
 * @param string $imgTarget
 * @return string
 * @return array
 */

function facelog_getCurl_data(string $imgTarget): string | array
{
  $curl = curl_init();

  $face_plugins = "landmarks";
  $api_key = "3605527e-480c-4e59-a469-c246210d4cd9";

  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://bosc.boscdelacoma.cat:8000/api/v1/detection/detect?face_plugins=$face_plugins",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: multipart/form-data",
      "x-api-key: $api_key"
    ),
    CURLOPT_POSTFIELDS => array("file" => curl_file_create($imgTarget))
  ));

  $response = curl_exec($curl);

  if ($errCurl = curl_errno($curl)) {
    $errorMsg = curl_strerror($errCurl);
    return ['error' => "cURL error ($errCurl): $errorMsg"];
  }

  curl_close($curl);

  return $response;
}


/**
 * Tractament de la imatge pujada si tot està correcte. Passa per cURL per l'API facedetection i agafa els paràmetres dels ulls.
 * Les dades necessàries son insertades a la base de dades. Hi ha diferents errors donats si hi ha més d'una persona o ja
 * s'ha pujat la imatge en un dia en concret.
 * 
 * @param string $imgTarget
 * @param string $imgName
 * @param string $imgType
 * @return void
 */

function facelog_curl_img(string $imgTarget, string $imgName, string $imgType): void
{
  global $filesystem;
  global $status;

  $curlData = facelog_getCurl_data($imgTarget, $imgName, $imgType);

  if ($imgType == "jpg" || $imgType == "jpeg") {
    $img = imagecreatefromjpeg($imgTarget);
  } else if ($imgType == "png") {
    $img = imagecreatefrompng($imgTarget);
  }

  if (!isset($curlData['error'])) {

    $data = json_decode($curlData, true);

    $countPersones = substr_count($curlData, "landmarks");

    if ($countPersones > 1) {
      $status = "Apareix més d'una persona a l'imatge.";
    } else if (isset($data['code']) && $data['code'] == 28) {
      $status = "No s'ha pogut pujar l'imatge. Error: " . $data['message'];
    } else if (!isset($data['result'][0]['landmarks'])) {
      $status = "Error inesperat";
    } else {

      //ULLS
      $ulls = $data['result'][0]['landmarks'];

      $ullEX = $ulls[0][0];
      $ullEY = $ulls[0][1];
      $ullDX = $ulls[1][0];
      $ullDY = $ulls[1][1];

      //rotacio imatge
      $imgRotated = facelog_rotar_imatge($ullEY, $ullDY, $ullEX, $ullDX, $img, $imgType, $imgTarget);

      //sobreescriure imatge
      facelog_guardar_img($imgType, $imgTarget, $imgRotated);

      //moure la imatge a la carpeta uploads
      $destiPath =  substr(plugin_dir_path(__FILE__), 0, -1) . "/uploads/" . basename($_FILES["imageupload"]["name"]);
      $destiUrl = substr(plugin_dir_url(__FILE__), 0, -1) . "/uploads/" . basename($_FILES["imageupload"]["name"]);
      $filesystem->move($imgTarget, $destiPath);

      if (isset($_POST['today'], $_POST['date'])) {
        $user_id = get_current_user_id();
        $data = $_POST['today'] == 1 ? date("Y-m-d") : date($_POST['date']);

        $checkSiJaHaPujat = facelog_dbcheck_upload_by_date($data, $user_id);

        if ($checkSiJaHaPujat) {
          $status = "Ja has pujat una imatge pel dia $data!";
        } else {
          //insertar dades
          facelog_dbinsert_good_image($_FILES["imageupload"]["name"], $destiUrl, $data, $ullEX, $ullEY, $ullDX, $ullDY);
          $status = "S'ha pujat amb èxit.";
        }
      }
    }
  } else {
    $status = $curlData['error'];
  }

  if ($status != "S'ha pujat amb èxit.") {
    wp_delete_file($imgTarget);
  }
}


// Redirecció d'errors per header
$redireccioOk = get_site_url(null, 'facelog?ok=' . $status, null);
$redireccioErr = get_site_url(null, 'facelog?err=' . $status, null);

if (str_contains($status, "èxit")) {
  header("Location: $redireccioOk"); // per defecte 302
} else {
  header("Location: $redireccioErr", true, 303);
}


/**
 * By: 01001001 01110110 01100001 01101110
 */
