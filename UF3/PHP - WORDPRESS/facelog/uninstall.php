<?php
require_once __DIR__ . '/../../../wp-load.php';
require_once('includes/db.php');

// exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

// remove plugin options

// delete database table
facelog_dbdelete();

// remove plugin transients

// remove plugin cron events

// remove plugin pages

/**
 * Eliminació de les pàgines entrades pel seu nom.
 * 
 * @param string $nom
 * @return void
 */

function facelog_eliminarPagines(string $nom): void
{
    $page_id = intval(get_option($nom));

    // Force delete this so the Title/slug "Menu" can be used again.
    wp_delete_post($page_id, true);
}

facelog_eliminarPagines("FaceLog");
facelog_eliminarPagines("Galeria");

// remove plugin images tmp

/**
 * Eliminació de les imatges de la carpeta uploads i tmp.
 * 
 * @param string $tmp
 * @return void
 */

function facelog_eliminarImatges(string $tmp = ""): void
{
    $extensions = ["png", "jpg", "jpeg"];

    $directori = substr(plugin_dir_path(__FILE__), 0, -1) . "\uploads\\" . $tmp;

    foreach ($extensions as $key => $valor) {
        $imatges = glob($directori . "*." . $valor . "");

        foreach ($imatges as $imatge) {
            unlink($imatge);
        }
    }
}

facelog_eliminarImatges();
facelog_eliminarImatges("tmp\\");

// ..etc., based on what needs to be removed



// Més info: https://digwp.com/2019/11/wordpress-uninstall-php/

/**
 * By: 01001001 01110110 01100001 01101110
 */
