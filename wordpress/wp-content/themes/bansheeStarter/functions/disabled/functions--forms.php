<?php

/**
 * Filter: acf/load_field 
 * - Add Formidable Forms as values to an ACF dropdown 
 */

function load_forms_function($field)
{
    $result = get_forms();
    if (is_array($result)) {
        $field['choices'] = array();
        foreach ($result as $key => $match) {
            $field['choices'][$key] = $match;
        }
    }
    return $field;
}
add_filter('acf/load_field/name=form_select', 'load_forms_function');


/* helper function to get formidable forms to ACF: */
function get_forms()
{
    ob_start();
    FrmFormsHelper::forms_dropdown('frm_add_form_id');
    $forms = ob_get_contents();
    ob_end_clean();
    preg_match_all('/<option\svalue="([^"]*)" >([^>]*)<\/option>/', $forms, $matches);
    $result = array_combine($matches[1], $matches[2]);
    return $result;
}
