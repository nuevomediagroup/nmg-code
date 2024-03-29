<?php
/**
 * Register data (called automatically).
 * 
 * @return type 
 */
function wpcf_fields_file() {
    return array(
        'id' => 'wpcf-file',
        'title' => __('File', 'wpcf'),
        'description' => __('File', 'wpcf'),
        'validate' => array('required'),
        'meta_box_js' => array(
            'wpcf-jquery-fields-file' => array(
                'inline' => 'wpcf_fields_file_meta_box_js_inline',
            )
        ),
    );
}

/**
 * Form data for post edit page.
 * 
 * @param type $field 
 */
function wpcf_fields_file_meta_box_form($field, $image = false) {
    add_thickbox();
    $type = $image ? 'image' : 'file';
    $button_text = $image ? __('Upload image', 'wpcf') : __('Upload file',
                    'wpcf');

    // Get attachment by guid
    global $wpdb;
    $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts}
    WHERE post_type = 'attachment' AND guid=%s",
                    $field['value']));

    // Set preview
    $preview = '';
    if (!empty($attachment_id)) {
        $preview = wp_get_attachment_image($attachment_id, 'thumbnail');
    } else {
        // If external image set preview
        $file = pathinfo($field['value']);
        if (isset($file['extension'])
                && in_array($file['extension'],
                        array('jpg', 'jpeg', 'gif', 'png'))) {
            $preview = '<img alt="" src="' . $field['value'] . '" />';
        }
    }

    // Set button
    if (!empty($field['#attributes']['readonly']) || !empty($field['#attributes']['disabled'])) {
        $button = '';
    } else {
        $button = '<a href="javascript:void(0);"'
                . ' class="wpcf-fields-' . $type . '-upload-link button-secondary"'
                . ' id="wpcf-fields-' . $field['slug'] . '-upload">'
                . $button_text . '</a>';
    }

    // Set form
    $form = array(
        '#type' => 'textfield',
        '#id' => 'wpcf-fields-' . $field['slug'] . '-upload-holder',
        '#name' => 'wpcf[' . $field['slug'] . ']',
        '#suffix' => '&nbsp;' . $button,
        '#after' => '<div id="wpcf-fields-' . $field['slug']
        . '-upload-holder-preview"'
        . ' class="wpcf-fields-file-preview">' . $preview . '</div>',
        '#attributes' => array('class' => 'wpcf-fields-file-textfield'),
    );

    return $form;
}

/**
 * Renders inline JS.
 */
function wpcf_fields_file_meta_box_js_inline() {
    global $post;

    ?>
    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function(){
            window.wpcf_formfield = false;
            jQuery('.wpcf-fields-file-upload-link').click(function() {
                window.wpcf_formfield = '#'+jQuery(this).attr('id')+'-holder';
                tb_show('<?php _e('Upload file',
            'wpcf'); ?>', 'media-upload.php?post_id=<?php echo $post->ID; ?>&type=file&wpcf-fields-media-insert=1&TB_iframe=true');
                        return false;
                    });
                });
                function wpcfFieldsFileMediaInsert(url, type) {
                    jQuery(window.wpcf_formfield).val(url);
                    if (type == 'image') {
                        jQuery(window.wpcf_formfield+'-preview').html('<img src="'+url+'" />');
                    } else {
                        jQuery(window.wpcf_formfield+'-preview').html('');
                    }
                    tb_remove();
                    window.wpcf_formfield = false;
                }
                //]]>
    </script>
    <?php
}

/**
 * Media popup JS.
 */
function wpcf_fields_file_media_admin_head() {

    ?>
    <script type="text/javascript">
        function wpcfFieldsFileMediaTrigger(guid, type) {
            window.parent.wpcfFieldsFileMediaInsert(guid, type);
            window.parent.jQuery('#TB_closeWindowButton').trigger('click');
        }
    </script>
    <style type="text/css">
        tr.submit { display: none; }
    </style>
    <?php
}

/**
 * Adds 'Types' column to media item table.
 * 
 * @param type $form_fields
 * @param type $post
 * @return type 
 */
function wpcf_fields_file_attachment_fields_to_edit_filter($form_fields, $post) {
    $type = (strpos($post->post_mime_type, 'image/') !== false) ? 'image' : 'file';
    $form_fields['wpcf_fields_file'] = array(
        'label' => __('Types', 'wpcf'),
        'input' => 'html',
        'html' => '<a href="#" title="' . $post->guid
        . '" class="wpcf-fields-file-insert-button'
        . ' button-primary" onclick="wpcfFieldsFileMediaTrigger(\''
        . $post->guid . '\', \'' . $type . '\')">'
        . __('Use as field value', 'wpcf') . '</a><br /><br />',
//        'helps' => __('Set this file as file value', 'wpcf'),
    );
    return $form_fields;
}

/**
 * View function.
 * 
 * @param type $params 
 */
function wpcf_fields_file_view($params) {
    $output = '';
    if (isset($params['link']) && $params['link'] == 'true') {
        $title = '';
        $add = '';
        if (!empty($params['title'])) {
            $add .= ' title="' . $params['title'] . '"';
            $title .= $params['title'];
        } else {
            $add .= ' title="' . $params['field_value'] . '"';
            $title .= $params['field_value'];
        }
        if (!empty($params['class'])) {
            $add .= ' class="' . $params['class'] . '"';
        }
        $output = '<a href="' . $params['field_value'] . '"' . $add . '>'
                . $title . '</a>';
    } else {
        $output = $params['field_value'];
    }

    $output = wpcf_frontend_wrap_field_value($params['field'], $output, $params);
    $output = wpcf_frontend_wrap_field($params['field'], $output, $params);

    return $output;
}

/**
 * Editor callback form.
 */
function wpcf_fields_file_editor_callback() {
    wp_enqueue_style('wpcf-fields-file', WPCF_EMBEDDED_RES_RELPATH . '/css/basic.css',
            array(), WPCF_VERSION);

    // Get field
    $field = wpcf_admin_fields_get_field($_GET['field_id']);
    if (empty($field)) {
        _e('Wrong field specified', 'wpcf');
        die();
    }

    // Get post_ID
    $post_ID = false;
    if (isset($_POST['post_id'])) {
        $post_ID = intval($_POST['post_id']);
    } else {
        $http_referer = explode('?', $_SERVER['HTTP_REFERER']);
        parse_str($http_referer[1], $http_referer);
        if (isset($http_referer['post'])) {
            $post_ID = $http_referer['post'];
        }
    }

    // Get attachment
    $attachment_id = false;
    if ($post_ID) {
        $file = get_post_meta($post_ID, wpcf_types_get_meta_prefix($field) . $field['slug'], true);
        if (!empty($file)) {
            // Get attachment by guid
            global $wpdb;
            $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts}
    WHERE post_type = 'attachment' AND guid=%s",
                            $file));
        }
    }

    $last_settings = wpcf_admin_fields_get_field_last_settings($_GET['field_id']);

    $form = array();
    $form['#form']['callback'] = 'wpcf_fields_file_editor_submit';
    if ($attachment_id) {
        $form['preview'] = array(
            '#type' => 'markup',
            '#markup' => '<div class="message updated" style="margin: 0 0 20px 0"><p>'
            . $file . '</p></div>',
        );
    }
    $form['link'] = array(
        '#type' => 'checkbox',
        '#title' => __('Display as link', 'wpcf'),
        '#name' => 'link',
        '#default_value' => isset($last_settings['link']) ? $last_settings['link'] : 1,
    );
    $form['title'] = array(
        '#type' => 'textfield',
        '#title' => __('Link title', 'wpcf'),
        '#name' => 'title',
        '#value' => isset($last_settings['title']) ? $last_settings['title'] : '',
    );
    $form['submit'] = array(
        '#type' => 'markup',
        '#markup' => get_submit_button(__('Insert shortcode', 'wpcf')),
    );
    $f = wpcf_form('wpcf-form', $form);
    wpcf_admin_ajax_head('Insert email', 'wpcf');
    echo '<form method="post" action="">';
    echo $f->renderForm();
    echo '</form>';
    wpcf_admin_ajax_footer();
}

/**
 * Editor callback form submit.
 */
function wpcf_fields_file_editor_submit() {
    $add = '';
    if (!empty($_POST['link'])) {
        $add .= ' link="true"';
        if (!empty($_POST['title'])) {
            $add .= ' title="' . strval($_POST['title']) . '"';
        }
    }
    $add .= ' class=""';
    $field = wpcf_admin_fields_get_field($_GET['field_id']);
    if (!empty($field)) {
        $shortcode = wpcf_fields_get_shortcode($field, $add);
        wpcf_admin_fields_save_field_last_settings($_GET['field_id'], $_POST);
        echo wpcf_admin_fields_popup_insert_shortcode_js($shortcode);
        die();
    }
}

/**
 * Filters media TABs.
 * 
 * @param type $tabs
 * @return type 
 */
function wpcf_fields_file_media_upload_tabs_filter($tabs) {
    unset($tabs['type_url']);
    return $tabs;
}