<?php

/**
 * TYPO3 Importer settings class
 *
 * @ref http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */
class T3I_Settings {
	
	private $sections;
	private $reset;
	private $settings;
	private $required			= ' <span style="color: red;">*</span>';
	
	/**
	 * Construct
	 */
	public function __construct() {
		global $wpdb;
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->reset		= array();
		$this->settings			= array();
		$this->get_settings();
		
		$this->sections['typo3']	= __( 'TYPO3 Access', 'typo3-importer');
		$this->sections['selection']	= __( 'News Selection', 'typo3-importer');
		$this->sections['general']	= __( 'Import Settings', 'typo3-importer');
		$this->sections['testing']	= __( 'Testing Options', 'typo3-importer');
		$this->sections['oops']		= __( 'Oops...', 'typo3-importer');
		$this->sections['reset']	= __( 'Reset/Restore', 'typo3-importer');
		$this->sections['TBI']		= __( 'Not Implemented', 'typo3-importer');
		$this->sections['about']	= __( 'About TYPO3 Importer', 'typo3-importer');
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		load_plugin_textdomain( 'typo3-importer', false, '/typo3-importer/languages/' );
		
		if ( ! get_option( 't3i_options' ) )
			$this->initialize_settings();

		$this->wpdb				= $wpdb;
	}
	
	/**
	 * Add options page
	 */
	public function add_pages() {
		
		$admin_page = add_options_page( __( 'TYPO3 Importer Settings', 'typo3-importer'), __( 'TYPO3 Importer', 'typo3-importer'), 'manage_options', 't3i-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );

		add_screen_meta_link(
        	'typo3-importer-link',
			__('TYPO3 Importer', 'typo3-importer'),
			admin_url('tools.php?page=typo3-importer'),
			$admin_page,
			array('style' => 'font-weight: bold;')
		);
		
	}
	
	/**
	 * Create settings field
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field', 'typo3-importer'),
			'desc'    => __( '', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'req'     => '',
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'req'		=> $req
		);
		
		$this->reset[$id] = $std;

		if ( '' != $req )
			$req	= $this->required;
		
		add_settings_field( $id, $title . $req, array( $this, 'display_setting' ), 't3i-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'TYPO3 Importer Settings', 'typo3-importer') . '</h2>';
	
		echo '<form action="options.php" method="post">';
	
		settings_fields( 't3i_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes', 'typo3-importer') . '" /></p>

		<div class="ready">When ready, <a href="'.get_admin_url().'tools.php?page=typo3-importer">'.__('begin importing', 'typo3-importer').'</a>.</div>
		
	</form>';

		$copyright				= '<div class="copyright">Copyright %s <a href="http://typo3vagabond.com">TYPO3Vagabond.com.</a></div>';
		$copyright				= sprintf( $copyright, date( 'Y' ) );
		echo					<<<EOD
				$copyright
EOD;
	
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
		});
	</script>
</div>';
		
	}
	
	/**
	 * Description for section
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * Description for About section
	 */
	public function display_about_section() {
		
		echo					<<<EOD
			<div style="width: 50%;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="/wp-content/plugins/typo3-importer/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/typo3-importer/">TYPO3 Importer</a> is by <a href="mailto:michael@typo3vagabond.com">Michael Cannon</a>.</p>
				<p>He's <a title="Lot's of stuff about Peichi Liu..." href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an adventurous&nbsp;<a title="Water rat" href="http://www.chinesezodiachoroscope.com/facebook/index1.php?user_id=690714457" target="_blank">water-rat</a>,&nbsp;<a title="Michael's poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>,&nbsp;<a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">road biker</a>,&nbsp;<a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>,&nbsp;<a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a>&nbsp;and is the&nbsp;<a title="The TYPO3 Vagabond" href="http://typo3vagabond.com/c/featured/">TYPO3 Vagabond</a>&nbsp;with&nbsp;<a title="in2code. Wir leben TYPO3" href="http://www.in2code.de/">in2code</a>.</p>
				<p>If you like this plugin, <a href="http://typo3vagabond.com/about-typo3-vagabond/donate/">please donate</a>.</p>
			</div>
EOD;
		
	}
	
	/**
	 * HTML output for text field
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 't3i_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="t3i_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="t3i_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="t3i_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="t3i_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="t3i_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="t3i_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 */
	public function get_settings() {
		// TYPO3 Website Access
		$this->settings['typo3_url'] = array(
			'title'   => __( 'Website URL', 'typo3-importer'),
			'desc'    => __( 'e.g. http://example.com/', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'req'	=> true,
			'section' => 'typo3'
		);
		
		$this->settings['t3db_host'] = array(
			'title'   => __( 'Database Host', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'req'	=> true,
			'section' => 'typo3'
		);
		
		$this->settings['t3db_name'] = array(
			'title'   => __( 'Database Name', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'req'	=> true,
			'section' => 'typo3'
		);
		
		$this->settings['t3db_username'] = array(
			'title'   => __( 'Database Username', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'req'	=> true,
			'section' => 'typo3'
		);
		
		$this->settings['t3db_password'] = array(
			'title'   => __( 'Database Password', 'typo3-importer'),
			'type'    => 'password',
			'std'     => '',
			'req'	=> true,
			'section' => 'typo3'
		);
		
		
		// Import Settings
		$this->settings['default_author'] = array(
			'section' => 'general',
			'title'   => __( 'Default Author', 'typo3-importer'),
			'desc'    => __( 'Select incoming news author when none is provided.', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'0'	=> __('Current user', 'typo3-importer'),
			)
		);

		$users					= get_users();
		foreach( $users as $user ) {
			$user_name			= $user->display_name;
			$user_name			.= ' (' . $user->user_email . ')';
			$this->settings['default_author']['choices'][ $user->ID ]	= $user_name;
		}

		$this->settings['protected_password'] = array(
			'title'   => __( 'Protected Post Password', 'typo3-importer'),
			'desc'    => __( 'If set, posts will require this password to be viewed.', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['force_post_status'] = array(
			'section' => 'general',
			'title'   => __( 'Override Post Status as...?', 'typo3-importer'),
			'desc'    => __( 'Hidden news records will remain as Draft.', 'typo3-importer'),
			'type'    => 'radio',
			'std'     => 'default',
			'choices' => array(
				'default'	=> __('No Change', 'typo3-importer'),
				'draft'		=> __('Draft', 'typo3-importer'),
				'publish'	=> __('Publish', 'typo3-importer'),
				'pending'	=> __('Pending', 'typo3-importer'),
				'future'	=> __('Future', 'typo3-importer'),
				'private'	=> __('Private', 'typo3-importer')
			)
		);

		$this->settings['insert_more_link'] = array(
			'section' => 'general',
			'title'   => __( 'Insert More Link?', 'typo3-importer'),
			'desc'    => __( 'Denote where the &lt;--more--&gt; link is be inserted into post content.', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '0',
			'choices' => array(
				'0'	=> __('No', 'typo3-importer'),
				'1'	=> __('After 1st paragraph', 'typo3-importer'),
				'2'	=> __('After 2nd paragraph', 'typo3-importer'),
				'3'	=> __('After 3rd paragraph', 'typo3-importer'),
				'4'	=> __('After 4th paragraph', 'typo3-importer'),
				'5'	=> __('After 5th paragraph', 'typo3-importer'),
				'6'	=> __('After 6th paragraph', 'typo3-importer'),
				'7'	=> __('After 7th paragraph', 'typo3-importer'),
				'8'	=> __('After 8th paragraph', 'typo3-importer'),
				'9'	=> __('After 9th paragraph', 'typo3-importer'),
				'10'	=> __('After 10th paragraph', 'typo3-importer')
			)
		);

		$this->settings['set_featured_image'] = array(
			'section' => 'general',
			'title'   => __( 'Set Featured Image?', 'typo3-importer'),
			'desc'    => __( 'Set first image found in content or related as the Featured Image.', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['insert_gallery_shortcut'] = array(
			'section' => 'general',
			'title'   => __( 'Insert Gallery Shortcode?', 'typo3-importer'),
			'desc'    => __( 'Inserts [gallery] into post content if news record has related images. Follows more link if insert points match.', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '-1',
			'choices' => array(
				'0'	=> __('No', 'typo3-importer'),
				'1'	=> __('After 1st paragraph', 'typo3-importer'),
				'2'	=> __('After 2nd paragraph', 'typo3-importer'),
				'3'	=> __('After 3rd paragraph', 'typo3-importer'),
				'4'	=> __('After 4th paragraph', 'typo3-importer'),
				'5'	=> __('After 5th paragraph', 'typo3-importer'),
				'6'	=> __('After 6th paragraph', 'typo3-importer'),
				'7'	=> __('After 7th paragraph', 'typo3-importer'),
				'8'	=> __('After 8th paragraph', 'typo3-importer'),
				'9'	=> __('After 9th paragraph', 'typo3-importer'),
				'10'	=> __('After 10th paragraph', 'typo3-importer'),
				'-1'	=> __('After content', 'typo3-importer')
			)
		);

		$this->settings['related_files_header'] = array(
			'title'   => __( 'Related Files Header' , 'typo3-importer'),
			'std'     => __( 'Related Files', 'typo3-importer' ),
			'type'	=> 'text',
			'section' => 'general'
		);

		$this->settings['related_files_header_tag'] = array(
			'section' => 'general',
			'title'   => __( 'Related Files Header Tag', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '3',
			'choices' => array(
				'0'	=> __('None', 'typo3-importer'),
				'1'	=> __('H1', 'typo3-importer'),
				'2'	=> __('H2', 'typo3-importer'),
				'3'	=> __('H3', 'typo3-importer'),
				'4'	=> __('H4', 'typo3-importer'),
				'5'	=> __('H5', 'typo3-importer'),
				'6'	=> __('H6', 'typo3-importer')
			)
		);
		
		$this->settings['related_files_wrap'] = array(
			'title'   => __( 'Related Files Wrap' , 'typo3-importer'),
			'desc'   => __( 'Useful for adding membership oriented shortcodes around premium content. "|" separates before and after content. e.g. [paid]|[/paid]' , 'typo3-importer'),
			'type'	=> 'text',
			'section' => 'general'
		);

		$this->settings['related_links_header'] = array(
			'title'   => __( 'Related Links Header' , 'typo3-importer'),
			'std'     => __( 'Related Links', 'typo3-importer' ),
			'type'	=> 'text',
			'section' => 'general'
		);

		$this->settings['related_links_header_tag'] = array(
			'section' => 'general',
			'title'   => __( 'Related Links Header Tag', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '3',
			'choices' => array(
				'0'	=> __('None', 'typo3-importer'),
				'1'	=> __('H1', 'typo3-importer'),
				'2'	=> __('H2', 'typo3-importer'),
				'3'	=> __('H3', 'typo3-importer'),
				'4'	=> __('H4', 'typo3-importer'),
				'5'	=> __('H5', 'typo3-importer'),
				'6'	=> __('H6', 'typo3-importer')
			)
		);
		
		$this->settings['related_links_wrap'] = array(
			'title'   => __( 'Related Links Wrap' , 'typo3-importer'),
			'desc'   => __( 'Useful for adding membership oriented shortcodes around premium content. "|" separates before and after content. e.g. [member]|[/member]' , 'typo3-importer'),
			'section' => 'general'
		);
		$this->settings['approve_comments'] = array(
			'section' => 'general',
			'title'   => __( 'Approve Non-spam Comments?', 'typo3-importer'),
			'desc'    => __( 'Not fool proof, but beats mass approving comments after import.', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		// Testing
		$this->settings['no_comments_import'] = array(
			'section' => 'testing',
			'title'   => __( "Don't Import Comments" , 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['no_media_import'] = array(
			'section' => 'testing',
			'title'   => __( "Don't Import Media" , 'typo3-importer'),
			'desc'    => __( 'Skips importing any related images and other media files of news records.', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['import_limit'] = array(
			'section' => 'testing',
			'title'   => __( 'Import Limit', 'typo3-importer'),
			'desc'    => __( 'Number of news records allowed to import at a time. 0 for all..', 'typo3-importer'),
			'std'     => '',
			'type'    => 'text'
		);
		
		$this->settings['debug_mode'] = array(
			'section' => 'testing',
			'title'   => __( 'Debug Mode' , 'typo3-importer'),
			'desc'	  => __( 'Bypass Ajax controller to handle news_to_import directly for testing purposes', 'typo3-importer' ),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		// Oops...
		$this->settings['force_private_posts'] = array(
			'section' => 'oops',
			'title'   => __( 'Convert Imported Posts to Private, NOW!', 'typo3-importer'),
			'desc'    => __( 'A quick way to hide imported live posts.', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$desc_imports		= __( "This will remove ALL posts imported with the 't3:tt_news.uid' meta key. Related post media and comments will also be deleted.", 'typo3-importer');
		$desc_comments		= __( "This will remove ALL comments imported with the 't3:tx_comments' comment_agent key." , 'typo3-importer');
		$desc_attachments	= __( "This will remove ALL media without a related post. It's possible for non-imported media to be deleted.", 'typo3-importer');

		// Reset/restore
		$this->settings['delete'] = array(
			'section' => 'reset',
			'title'   => __( 'Delete...', 'typo3-importer'),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'imports'		=> __( 'Prior imports', 'typo3-importer') . ': ' . $desc_imports,
				'comments'		=> __( 'Imported comments', 'typo3-importer') . ': ' . $desc_comments,
				'attachments'	=> __( 'Unattached media', 'typo3-importer') . ': ' . $desc_attachments
			)
		);
		
		$this->settings['reset_plugin'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset plugin', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset plugin options to their defaults.', 'typo3-importer')
		);


		// selection
		$this->settings['news_custom_where'] = array(
			'title'   => __( 'News WHERE Clause' , 'typo3-importer'),
			'desc'    => __( "WHERE clause used to select news records from TYPO3. e.g.: AND tt_news.deleted = 0 AND tt_news.pid > 0" , 'typo3-importer'),
			'std'     => 'AND tt_news.deleted = 0 AND tt_news.pid > 0',
			'type'	=> 'text',
			'section' => 'selection'
		);
		
		$this->settings['news_custom_order'] = array(
			'title'   => __( 'News ORDER Clause' , 'typo3-importer'),
			'desc'    => __( "ORDER clause used to select news records from TYPO3. e.g.: ORDER BY tt_news.uid ASC" , 'typo3-importer'),
			'std'     => 'ORDER BY tt_news.uid ASC',
			'type'	=> 'text',
			'section' => 'selection'
		);

		$this->settings['news_to_import'] = array(
			'title'   => __( 'News to Import' , 'typo3-importer'),
			'desc'    => __( "A CSV list of news uids to import, like '1,2,3'. Overrides 'News Selection Criteria'." , 'typo3-importer'),
			'type'	=> 'text',
			'section' => 'selection'
		);
		
		$this->settings['news_to_skip'] = array(
			'title'   => __( 'Skip Importing News' , 'typo3-importer'),
			'desc'    => __( "A CSV list of news uids not to import, like '1,2,3'." , 'typo3-importer'),
			'type'	=> 'text',
			'section' => 'selection'
		);
		

		// Pending
		$this->settings['make_nice_image_title'] = array(
			'section' => 'TBI',
			'title'   => __( 'Make Nice Image Title?' , 'typo3-importer'),
			'desc'    => __( 'Tries to make a nice title out of filenames if no title exists.' , 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		
		// Here for reference
		if ( false ) {
		$this->settings['example_text'] = array(
			'title'   => __( 'Example Text Input', 'typo3-importer'),
			'desc'    => __( 'This is a description for the text input.', 'typo3-importer'),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input', 'typo3-importer'),
			'desc'    => __( 'This is a description for the textarea input.', 'typo3-importer'),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox', 'typo3-importer'),
			'desc'    => __( 'This is a description for the checkbox.', 'typo3-importer'),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);
		
		$this->settings['example_radio'] = array(
			'section' => 'general',
			'title'   => __( 'Example Radio', 'typo3-importer'),
			'desc'    => __( 'This is a description for the radio buttons.', 'typo3-importer'),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Choice 1',
				'choice2' => 'Choice 2',
				'choice3' => 'Choice 3'
			)
		);
		
		$this->settings['example_select'] = array(
			'section' => 'general',
			'title'   => __( 'Example Select', 'typo3-importer'),
			'desc'    => __( 'This is a description for the drop-down.', 'typo3-importer'),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Other Choice 1',
				'choice2' => 'Other Choice 2',
				'choice3' => 'Other Choice 3'
			)
		);
		}
	}
	
	/**
	 * Initialize settings to their default values
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 't3i_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*/
	public function register_settings() {
		
		register_setting( 't3i_options', 't3i_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 't3i-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 't3i-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		
	}
	
	/**
	* Styling for the plugin options page
	*/
	public function styles() {
		
		wp_register_style( 't3i-admin', plugins_url( 'settings.css', __FILE__ ) );
		wp_enqueue_style( 't3i-admin' );
		
	}
	
	/**
	* Validate settings
	*/
	public function validate_settings( $input ) {
		
		// TODO validate for
		// TYPO3 db connectivity

		if ( $input['debug_mode'] && '' == $input['news_to_import'] ) {
			add_settings_error( 't3i-options', 'news_to_import', __( 'News to Import is required' , 'typo3-importer') );
		}

		if ( '' != $input['import_limit'] ) {
			$input['import_limit']	= intval( $input['import_limit'] );
		}
		
		if ( '' != $input['news_to_import'] ) {
			$news_to_import		= $input['news_to_import'];
			$news_to_import		= preg_replace( '#\s+#', '', $news_to_import);

			$input['news_to_import']	= $news_to_import;
		}
		
		if ( '' != $input['news_to_skip'] ) {
			$news_to_skip		= $input['news_to_skip'];
			$news_to_skip		= preg_replace( '#\s+#', '', $news_to_skip);

			$input['news_to_skip']	= $news_to_skip;
		}
		
		if ( '' == $input['typo3_url'] ) {
			add_settings_error( 't3i-options', 'typo3_url', __('Website URL is required', 'typo3-importer') );
		} else {
			$typo3_url			= $input['typo3_url'];
			// append / if needed and save to options
			$typo3_url	= preg_replace('#(/{0,})?$#', '/',  $typo3_url);
			// silly // fix, above regex no matter what doesn't seem to work on 
			// this
			$typo3_url	= preg_replace('#//$#', '/',  $typo3_url);
			// Store details for later
			$input['typo3_url']	= $typo3_url;

			// check for typo3_url validity & reachability
			if ( ! $this->_is_typo3_website( $typo3_url ) ) {
				add_settings_error( 't3i-options', 'typo3_url', __( "TYPO3 site not found at given Website URL", 'typo3-importer' ) );
			}
		}
		
		if ( '' == $input['t3db_host'] ) {
			add_settings_error( 't3i-options', 't3db_host', __('Database Host is required', 'typo3-importer') );
		}
		
		if ( '' == $input['t3db_name'] ) {
			add_settings_error( 't3i-options', 't3db_name', __('Database Name is required', 'typo3-importer') );
		}
		
		if ( '' == $input['t3db_username'] ) {
			add_settings_error( 't3i-options', 't3db_username', __('Database Username is required', 'typo3-importer') );
		}
		
		if ( '' == $input['t3db_password'] ) {
			add_settings_error( 't3i-options', 't3db_password', __('Database Password is required', 'typo3-importer') );
		}

		if ( isset( $input['delete'] ) && $input['delete'] ) {
			switch ( $input['delete'] ) {
				case 'imports' :
					$this->delete_import();
					break;

				case 'comments' :
					$this->delete_comments();
					break;

				case 'attachments' :
					$this->delete_attachments();
					break;
			}

			unset( $input['delete'] );
			return $input;
		}

		if ( isset( $input['force_private_posts'] ) && $input['force_private_posts'] ) {
			$this->force_private_posts();

			unset( $input['force_private_posts'] );
			return $input;
		}

		if ( $input['reset_plugin'] ) {
			foreach ( $this->reset as $id => $std ) {
				$input[$id]	= $std;
			}
			
			unset( $input['reset_plugin'] );
		}

		return $input;

	}

	function _is_typo3_website( $url = null ) {
		// regex url
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			// pull site's TYPO3 admin url, http://example.com/typo3
			$typo3_url			= preg_replace( '#$#', 'typo3/index.php', $url );

			// check for TYPO3 header code
			$html				= @file_get_contents( $typo3_url );

			// look for `<meta name="generator" content="TYPO3`
			// looking for meta doesn't work as TYPO3 throws browser error
			// if exists, return true, else false
			if ( preg_match( '#typo3logo#', $html ) ) {
				return true;
			} else {
				// not typo3 site
				return false;
			}
		} else {
			// bad url
			return false;
		}
	}

	function delete_comments() {
		$comment_count			= 0;

		$query					= "SELECT comment_ID FROM {$this->wpdb->comments} WHERE comment_agent = 't3:tx_comments'";
		$comments				= $this->wpdb->get_results( $query );

		foreach( $comments as $comment ) {
			// returns array of obj->ID
			$comment_id			= $comment->comment_ID;

			wp_delete_comment( $comment_id, true );

			$comment_count++;
		}

		add_settings_error( 't3i-options', 'comments', sprintf( __( "Successfully removed %s comments." , 'typo3-importer'), number_format( $comment_count ) ), 'updated' );
	}

	function force_private_posts() {
		$post_count				= 0;

		// during botched imports not all postmeta is read successfully
		// pull post ids with typo3_uid as post_meta key
		$posts					= $this->wpdb->get_results( "SELECT post_id FROM {$this->wpdb->postmeta} WHERE meta_key = 't3:tt_news.uid'" );

		foreach( $posts as $post ) {
			// returns array of obj->ID
			$post_id			= $post->post_id;

			// dels post, meta & comments
			// true is force delete

			$post_arr				= array(
				'ID'			=> $post_id,
				'post_status'	=> 'private',
			);
		 
			wp_update_post( $post_arr );

			$post_count++;
		}

		if ( $post_count )
			add_settings_error( 't3i-options', 'force_private_posts', sprintf( __( "Successfully updated %s TYPO3 news imports to 'Private'." , 'typo3-importer'), number_format( $post_count ) ), 'updated' );
		else
			add_settings_error( 't3i-options', 'force_private_posts', __( "No TYPO3 news imports found to mark as 'Private'." , 'typo3-importer'), 'updated' );
	}

	function delete_import() {
		$post_count				= 0;

		// during botched imports not all postmeta is read successfully
		// pull post ids with typo3_uid as post_meta key
		$posts					= $this->wpdb->get_results( "SELECT post_id FROM {$this->wpdb->postmeta} WHERE meta_key = 't3:tt_news.uid'" );

		foreach( $posts as $post ) {
			// returns array of obj->ID
			$post_id			= $post->post_id;

			// remove media relationships
			$this->delete_attachments( $post_id, false );

			// dels post, meta & comments
			// true is force delete
			wp_delete_post( $post_id, true );

			$post_count++;
		}

		add_settings_error( 't3i-options', 'imports', sprintf( __( "Successfully removed %s TYPO3 news and their related media and comments." , 'typo3-importer'), number_format( $post_count ) ), 'updated' );
	}

	function delete_attachments( $post_id = false, $report = true ) {
		$post_id				= $post_id ? $post_id : 0;
		$query					= "SELECT ID FROM {$this->wpdb->posts} WHERE post_type = 'attachment' AND post_parent = {$post_id}";
		$attachments			= $this->wpdb->get_results( $query );

		$attachment_count		= 0;
		foreach( $attachments as $attachment ) {
			// true is force delete
			wp_delete_attachment( $attachment->ID, true );
			$attachment_count++;
		}

		if ( $report )
			add_settings_error( 't3i-options', 'attachments', sprintf( __( "Successfully removed %s no-post attachments." , 'typo3-importer'), number_format( $attachment_count ) ), 'updated' );
	}
	
}

$T3I_Settings					= new T3I_Settings();

function get_t3i_options( $option ) {
	$options					= get_option( 't3i_options' );
	if ( isset( $options[$option] ) ) {
		return $options[$option];
	} else {
		return false;
	}
}

function update_t3i_options( $option, $value = null ) {
	$options					= get_option( 't3i_options' );

	if ( ! is_array( $options ) ) {
		$options				= array();
	}

	$options[$option]			= $value;
	update_option( 't3i_options', $options );
}
?>