<?php
/**
 * Types-field: Checkbox
 *
 * Description: Displays a checkbox to the user. Checkboxes can be
 * used to get binary, yes/no responsers from a user.
 *
 * Rendering: The "Value to stored" for the checkbox the front end
 * if the checkbox is checked or 'Selected'|'Not selected' HTML
 * will be rendered. If 'Selected'|'Not selected' HTML is not specified then
 * nothing is rendered.
 * 
 * Parameters:
 * 'raw' => 'true'|'false' (display raw data stored in DB, default false)
 * 'output' => 'html' (wrap data in HTML, optional)
 * 'show_name' => 'true' (show field name before value e.g. My checkbox: $value)
 * 'checked_html' => base64_encode('<img src="image-on.png" />')
 * 'unchecked_html' => base64_encode('<img src="image-off.png" />')
 *
 * Example usage:
 * With a short code use [types field="my-checkbox"]
 * In a theme use types_render_field("my-checkbox", $parameters)
 * 
 */

/**
 * Form data for group form.
 * 
 * @return type 
 */
function wpcf_fields_checkbox_insert_form() {
    $form['name'] = array(
        '#type' => 'textfield',
        '#title' => __('Name of custom field', 'wpcf'),
        '#description' => __('Under this name field will be stored in DB (sanitized)',
                'wpcf'),
        '#name' => 'name',
        '#attributes' => array('class' => 'wpcf-forms-set-legend'),
        '#validate' => array('required' => array('value' => true)),
    );
    $form['description'] = array(
        '#type' => 'textarea',
        '#title' => __('Description', 'wpcf'),
        '#description' => __('Text that describes function to user', 'wpcf'),
        '#name' => 'description',
        '#attributes' => array('rows' => 5, 'cols' => 1),
    );
    $form['value'] = array(
        '#type' => 'textfield',
        '#title' => __('Value to store', 'wpcf'),
        '#name' => 'set_value',
        '#value' => 1,
    );
    $form['checked'] = array(
        '#type' => 'checkbox',
        '#title' => __('Set checked by default (on new post)?', 'wpcf'),
        '#name' => 'checked',
    );
    $form['display'] = array(
        '#type' => 'radios',
        '#default_value' => 'db',
        '#name' => 'display',
        '#options' => array(
            'display_from_db' => array(
                '#title' => __('Display the value of this field from the database',
                        'wpcf'),
                '#name' => 'display',
                '#value' => 'db',
                '#inline' => true,
                '#after' => '<br />'
            ),
            'display_values' => array(
                '#title' => __('Show one of these two values:', 'wpcf'),
                '#name' => 'display',
                '#value' => 'value',
            ),
        ),
        '#inline' => true,
    );
    $form['display-value-1'] = array(
        '#type' => 'textfield',
        '#title' => __('Not selected:', 'wpcf'),
        '#name' => 'display_value_not_selected',
        '#value' => '',
        '#inline' => true,
    );
    $form['display-value-2'] = array(
        '#type' => 'textfield',
        '#title' => __('Selected:', 'wpcf'),
        '#name' => 'display_value_selected',
        '#value' => '',
    );
    return $form;
}