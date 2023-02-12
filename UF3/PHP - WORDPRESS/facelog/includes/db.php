<?php

/**
 * Creació de la Base de Dades fl_galeria.
 * 
 * @return bool
 */

function facelog_DB_Galeria(): bool
{
    global $wpdb;

    $fl_galeria = $wpdb->prefix . 'fl_galeria';
    // set the default character set and collation for the table
    $charset = $wpdb->get_charset_collate;
    // Check that the table does not already exist before continuing
    $sql = "CREATE TABLE IF NOT EXISTS " . $fl_galeria . "(
        usuari_id  bigint(20) UNSIGNED,
        nom_img varchar(50),
        image varchar(200),
        date Date,
        ullEX varchar(20),
        ullEY varchar(20),
        ullDX varchar(20),
        ullDY varchar(20),
        PRIMARY KEY (usuari_id, date),
        FOREIGN KEY (usuari_id) REFERENCES " . $wpdb->prefix . "users (ID)
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

    $is_error = empty($wpdb->last_error);

    return $is_error;
}

/**
 * Inserció de dades a la Base de Dades fl_galeria.
 * 
 * @param string $nom_img
 * @param string $image
 * @param string $date
 * @param string $ullEX
 * @param string $ullEY
 * @param string $ullDX
 * @param string $ullDy
 * @return void
 */

function facelog_dbinsert_good_image(string $nom_img, string $image, string $date, string $ullEX, string $ullEY, string $ullDX, string $ullDY): void
{
    global $wpdb;
    $tablename = $wpdb->prefix . "fl_galeria";

    $usuari_id = get_current_user_id(); //string value use: %s

    $sql = $wpdb->prepare("INSERT INTO `$tablename` (`usuari_id`, `nom_img`, `image`, `date`, `ullEX`, `ullEY`, `ullDX`, `ullDY`) values (%s, %s, %s, %s, %s, %s, %s, %s)", $usuari_id, $nom_img, $image, $date, $ullEX, $ullEY, $ullDX, $ullDY);

    $wpdb->query($sql);
}

/**
 * Obtenir la imatge i data d'aquesta, donat un usuari.
 * 
 * @param string $user
 * @return array
 */

function facelog_dbget(string $user): array
{
    global $wpdb;

    $result_data = $wpdb->prepare("SELECT `image`, `date` FROM `wp_fl_galeria` WHERE `usuari_id` IN ( SELECT ID FROM `wp_users` WHERE wp_users.user_login = %s)", $user);
    $result_data = $wpdb->get_results($result_data);

    return $result_data;
}

/**
 * Comprova si l'usuari que puja una imatge ja l'ha pujada en la data que se li passa.
 * 
 * @param string $data
 * @param string $usuari_id
 * @return array
 */

function facelog_dbcheck_upload_by_date(string $data, string $usuari_id): array
{

    global $wpdb;

    $result_data = $wpdb->prepare("SELECT `usuari_id` FROM `wp_fl_galeria` WHERE `date` = %s AND `usuari_id` = %s", $data, $usuari_id);
    $result_data = $wpdb->get_results($result_data);

    return $result_data;
}

/**
 * Eliminació de la taula wp_fl_galeria.
 * 
 * @return void
 */

function facelog_dbdelete(): void
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'fl_galeria';

    // drop the table from the database.
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/**
 * By: 01001001 01110110 01100001 01101110
 */