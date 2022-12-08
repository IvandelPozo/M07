<?php
require_once '../classes/classeGos.php';

/**
 * Forma el formulari de Concursants de la part Administrativa
 * 
 * @return string
 * @return bool
 */
function formulariConcursants(): string | bool
{
    $gossos = Gos::obtenirConcursants();

    if ($gossos) {
        $html = "";
        foreach ($gossos as $gos) {
            $html .= '<form id="formGos-' . $gos->id . '">
                <input type="hidden" name="action" value="modificar">
                <input type="hidden" name="Id" value="' . $gos->id . '">
                <input type="text" value="' . $gos->nom . '" name="Nom" placeholder="Nom">
                <input type="text" value="' . $gos->imatge . '" name="Imatge" placeholder="Imatge">
                <input type="text" value="' . $gos->amo . '" name="Amo" placeholder="Amo">
                <input type="text" value="' . $gos->raça . '" name="Raça" placeholder="Raça">
                <input type="button" value="Modifica" onclick="actualitzarGos(' . $gos->id . ');">
            </form>';
        }
        return $html;
    }
    return false;
}

/**
 * Forma el formulari d'Inscripció de Concursants de la part Administrativa.
 * En cas de superar els 9 concursants totals, el botó d'afegir, desapareix.
 * 
 * @return string
 */
function formulariConcursantsInscripcio(): string
{
    $html = '<form id="formInscripcio">
        <input type="hidden" name="action" value="insertar">
        <input id="nomGos" type="text" name="Nom" placeholder="Nom">
        <input id="imatgeGos" type="text" name="Imatge" placeholder="Imatge">
        <input id="amoGos" type="text" name="Amo" placeholder="Amo">
        <input id="raçaGos" type="text" name="Raça" placeholder="Raça">';

    $numConcursants = Gos::contadorConcursants();

    if ($numConcursants) {
        if ($numConcursants["CONTADOR"] < 9) {
            $html .= '<input id="botoAfegirConcursant" type="button" value="Afegeix">';
        }
    }
    $html .= '</form>';

    return $html;
}

/**
 * By: 01001001 01110110 01100001 01101110
 */
