<?php
require_once "db.php";

/**
 * Crea la galeria dels registres de cares per cada usuari
 * @return string
 */
function facelog_gallery(): string
{

    $plugin_js = plugins_url('facelog/assets/js/render.js');

    $output = "<div class='facelog_gallery'>";

    $options = get_option('facelog_options', [
        'velocitat_galeria' => '1000'
    ]);
    $velocitatGaleria = $options['velocitat_galeria'];

    $users = get_users(); // Array d'objectes WP_User
    $jsdata = [];

    foreach ($users as $user) {
        $data = facelog_dbget($user->user_login); // Funció que em retorna les dades donat un usuari    

        if (!$data || count($data) == 0) continue;

        $output .= "<div class='facelog_box' id='facelog_user_$user->user_login'>";
        $output .= "<h2> $user->user_login </h2>";
        $output .= "<canvas id='facelog_canvas_$user->user_login' width='300' height='500'> </canvas>";
        $output .= "<div class='info' id='facelog_info_$user->user_login'> </div>";
        $output .= "</div>";

        $jsdata[$user->user_login] = [];

        foreach ($data as $row) {

            $jsdata[$user->user_login][] = ["img" => $row->image, "date" => $row->date];
        }
    }

    $output .= "</div>";
    $output .= "<script>let velocitatGaleria = " . $velocitatGaleria . "; let facelog_data = " . json_encode($jsdata) . "</script>";
    $rand = rand();
    $output .= "<script src='$plugin_js' type='text/javascript' />";
    return $output;
}


/**
 * Crea el formulari per afegir el log diàri
 * @return string
 */
function facelog_addlog(): string
{
    $process = plugin_dir_url(__DIR__ . "..") . "/upload.php";
    $msg = $_GET["err"] ?? "";
    $msg .= isset($_GET["ok"]) ? "Tot Ok!" : "";
    $msg_class =  isset($_GET["ok"]) ? "ok" : "error";

    return '
    
    <form class="facelog_form" action="' . $process . '" method="post" enctype="multipart/form-data">
        Hola ' . wp_get_current_user()->user_login . ', puja el teu log
        <select class="inline-input" name="today" id="date-today" onchange="changeDateSelect()" required>
            <option value="1">d\'avui</option>
            <option value="0">d\'un altre dia</option>
        </select>:
        <div class="facelog_date" id="facelog_setdate" style="display: none">
            <label> Data: </label><input name="date" class="inline-input" type="date">
        </div>

        <div class="facelog_upload">
            <input class="clear-input" type="file" name="imageupload" id="imatgeupload" accept=".png,.jpg,.jpeg" required>
            <input type="submit" value="Puja" name="submit">
        </div>
    </form>
     <div class="facelog_message"><span class="' . $msg_class . '">' . $msg . '</span></div>

    <script>
    window.history.replaceState({},{}, window.location.pathname);

    function changeDateSelect(){
        let sel = document.getElementById("date-today")
        document.getElementById("facelog_setdate").style.display = sel.selectedIndex === 0 ? "none" : ""
    }
    </script>
    ';
}


/**
 * By: 01001001 01110110 01100001 01101110
 */