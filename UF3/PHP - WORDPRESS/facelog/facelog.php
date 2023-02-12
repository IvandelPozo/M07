<?php

/**
 * Plugin Name: FaceLog Plugin
 * Plugin URI: http://boscdelacoma.cat
 * Description: Pràctica MP07.
 * Version: 0.1
 * Author: Ivan del Pozo
 * Author URI:  http://boscdelacoma.cat
 **/

require_once('includes/custom-pages.php');
require_once('includes/db.php');

const FACELOG_DB_VERSION = '1.0';
const FACELOG_VERSION = '1.0';

// Allow subscribers to see Private posts and pages
$subRole = get_role('subscriber');
$subRole->add_cap('read_private_posts');
$subRole->add_cap('read_private_pages');

/**
 * Insertar Pàgina FaceLog (add-log).
 * 
 * @return void
 */

function facelog_insertarPaginaFaceLog(): void
{
   // Define my page arguments
   $page = array(
      'post_title'   => "FaceLog",
      'post_content' => "[facelog]",
      'post_status'  => "private",
      'post_author'  => get_current_user_id(),
      'post_type'    => 'page',
   );

   if (!post_exists($page['post_title'])) {
      if ($page_id = wp_insert_post($page)) {
         // Only update this option if `wp_insert_post()` was successful
         update_option("FaceLog", $page_id);
      }
   }
}

/**
 * Insertar Pàgina Galeria (log).
 * 
 * @return void
 */

function facelog_insertarPaginaGaleria(): void
{
   // Define my page arguments
   $page = array(
      'post_title'   => "Galeria",
      'post_content' => "[galeria]",
      'post_status'  => "publish",
      'post_author'  => get_current_user_id(),
      'post_type'    => 'page',
   );

   if (!post_exists($page['post_title'])) {
      if ($page_id = wp_insert_post($page)) {
         // Only update this option if `wp_insert_post()` was successful
         update_option("Galeria", $page_id);
      }
   }
}

/**
 * Registra fulla d'estils.
 * 
 * @return void
 */

function register_script(): void
{
   wp_register_style('new_style', plugins_url('/assets/css/style.css', __FILE__), false, '1.0.0', 'all');
}

/**
 * Utilització de l'estil registrat.
 * 
 * @return void
 */

function enqueue_style(): void
{
   wp_enqueue_style('new_style');
}

/**
 * Hooks i accions d'activació del plugin al inicialitzar-se.
 * 
 * @return void
 */

function facelog_activacions(): void
{
   register_activation_hook(__FILE__, 'facelog_insertarPaginaFaceLog');
   register_activation_hook(__FILE__, 'facelog_insertarPaginaGaleria');
   register_activation_hook(__FILE__, 'facelog_DB_Galeria');

   // register style on initialization
   add_action('init', 'register_script');

   // use the registered style above
   add_action('wp_enqueue_scripts', 'enqueue_style');

   // administration menu

   add_action('admin_menu', 'register_facelog_options_page');
   add_action('admin_init', 'register_facelog_options');

   // Creació shortcodes
   add_shortcode('facelog', 'facelog_addlog');
   add_shortcode('galeria', 'facelog_gallery');
}

facelog_activacions();


$facelog_options = [
   'velocitat_galeria' => 1000,
];


/**
 * Registrar pàgina d'opcions.
 * 
 * @return void
 */

function register_facelog_options_page(): void
{
   add_options_page(
      'Opcions de Facelog',
      'Facelog',
      'manage_options',
      'facelog_options',
      'render_facelog_options_page'
   );
}

/**
 * Registrar opcions.
 * 
 * @return void
 */

function register_facelog_options(): void
{
   register_setting(
      'facelog_options',
      'facelog_options',
      'sanitize_facelog_options'
   );
}

/**
 * Sanititzar opcions.
 * 
 * @return void
 */

function sanitize_facelog_options($input): array
{
   global $facelog_options;
   $options = get_option('facelog_options', $facelog_options);
   $options['velocitat_galeria'] = sanitize_text_field($input['velocitat_galeria']);

   return $options;
}

/**
 * Renderitzar pàgina d'opcions. Aquesta permet canviar la velocitat de la galeria en la mostra d'imatges.
 * 
 * @return void
 */

function render_facelog_options_page(): void
{
   global $facelog_options;

   if (!isset($_REQUEST['settings-updated'])) {
      $_REQUEST['settings-updated'] = false;
   }

   $options = get_option('facelog_options', $facelog_options);

   // If the form was submitted, save the new options
   if (isset($_REQUEST['submit'])) {
      $options = array(
         'velocitat_galeria' => sanitize_text_field($_REQUEST['velocitat_galeria'])
      );
      update_option('facelog_options', $options);
      $_REQUEST['settings-updated'] = true;
   }

   // Display the form
?>
   <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

      <?php if ($_REQUEST['settings-updated'] === true) : ?>
         <div class="notice notice-success is-dismissible">
            <p><strong><?php esc_html_e('Settings saved.', 'my-plugin'); ?></strong></p>
         </div>
      <?php endif; ?>

      <form action="options.php" method="post">
         <?php settings_fields('facelog_options'); ?>
         <table class="form-table">
            <tr valign="top">
               <th scope="row"><label for="velocitat_galeria"><?php esc_html_e('velocitat_galeria', 'my-plugin'); ?></label></th>
               <td><input type="number" id="velocitat_galeria" name="facelog_options[velocitat_galeria]" value="<?php echo esc_attr($options['velocitat_galeria']); ?>" /></td>
            </tr>
         </table>
         <?php submit_button(); ?>
      </form>
   </div>
<?php
}

/**
 * By: 01001001 01110110 01100001 01101110
 */