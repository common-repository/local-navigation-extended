<?php
/**
 * Plugin activation hook
 */
function nlws_lnm_activate() {
	// Only send if the plugin hasn't been activated before
	$isinstalled = get_option('nlws_lnm_version');
	
	if (!$isinstalled) {

	}
	
	// Update version
	update_option( 'nlws_lnm_version', NLWS_LNM_VERSION );
}

/**
 * Output our widget
 */
function nlws_lnm_process( ) {
	$options = nlws_lnm_get_options();
	
	nlws_lnm($options);
}

function nlws_lnm($options = null){
	global $post;
	
	if ($options == null)
		$options = nlws_lnm_get_options();
	
	/**
	 * This variable tells us if
	 * this page has children or not
	 */
	$currentPageHasChildren = wp_list_pages('child_of='.$post->ID.'&echo=0&depth=1&title_li='); ;
	
	/**
	 * Check to see if the currently selected page
	 * has a parent and has children
	 */
	if (($post->post_parent != 0) && ($currentPageHasChildren)){
		/**
		 * Display the current menu name in the title
		 * and its children as options
		 */
		$titleID = $post->ID;
		$childrenID = $post->ID;
	}
	
	/**
	 * Check to see if the currently selected page
	 * has a parent and does NOT have children
	 */
	elseif (($post->post_parent != 0) && !($currentPageHasChildren)) {
		/**
		 * Display the parent menu name in the title
		 * and its children as options
		 */
		
		$titleID = $childrenID = $post->post_parent;
	}
	
	/**
	 * Check to see if the currently selected page
	 * does not have a parent and has children
	 */
	elseif (($post->post_parent == 0) && ($currentPageHasChildren) ) {
		/**
		 * Display the current menu name in the title
		 * and its children as options
		 */
		
		$titleID = $childrenID = $post->ID;
	}
	
	/**
	 * Create our display title and get our children
	 */
	if ($options['linkTitle'])
		$displayTitle = '<a href="' . get_permalink($titleID) . '" />' . get_the_title($titleID) . '</a>';
	else
		$displayTitle = get_the_title($titleID);
	
	/**
	 * Take into consideration a
	 * multi-level menu
	 */
	if ($options['menuMode'] == 'multilevel')
		$displayChildren = wp_list_pages('echo=0&title_li=&depth=' . $options['menuDepth']);
	else
		$displayChildren = wp_list_pages('child_of=' . $childrenID . '&echo=0&depth=1&title_li=');
	
	
	/**
	 * If we have both, then display them
	 */
	if ($displayTitle && $displayChildren) {
		echo '<div class="widget">';
		
		if ($options['displayTitle'])
			echo '<h2 id="localnavtitle">' . $displayTitle . '</h2>' . "\n";
		
		echo '<ul id="localnavmenu">' . $displayChildren . '</ul>' . "\n";
		echo '</div>';
	}
}

function nlws_lnm_get_options() {
	$options = get_option("nlws_lnm_options");
	
	/**
	 * These are the default options
	 */
	if (!is_array( $options )) {
		$options = array(
			'displayTitle' => 1,
			'linkTitle' => 1,
			'menuDepth' => 0,
			'menuMode' => 'local'
		);
	}
	
	return $options;
}

function nlws_lnm_control () {
	/**
	 * Get our widget options
	 */
	$options = nlws_lnm_get_options();

	if ($_POST['nlws_lnm_submit']) {
		$options['menuMode'] = $_POST['nlws_lnm_menu_mode'];
		$options['displayTitle'] = $_POST['nlws_lnm_display_title'];
		$options['linkTitle'] = $_POST['nlws_lnm_link_title'];
		$options['menuDepth'] = $_POST['nlws_lnm_menu_depth'];
		update_option("nlws_lnm_options", $options);
	}
	
	?>
	<p>
		Use the options below to configure how the menu will display.
	<p>
	<h4>
		Menu Mode
	</h4>
	<p>
		<input type="radio" name="nlws_lnm_menu_mode" value="local" <?php if ($options['menuMode'] == 'local') echo ' checked="checked"'; ?> /> Local<br />
		<input type="radio" name="nlws_lnm_menu_mode" value="multilevel" <?php if ($options['menuMode'] == 'multilevel') echo ' checked="checked"'; ?> /> Multi-Level
	</p>
	<p>
		<strong>Local -</strong> Use this mode if you wish to output a menu that is based off the user's location in the page heirarchy.&nbsp; This will output a menu that is 1 level deep.
	</p>
	<p>
		<strong>Multi-Level -</strong> This will output the entire page heirarchy.
	</p>
	<h4>
		Title Options
	</h4>
	<p>
		<input type="checkbox" <?php if ($options['displayTitle']) echo ' checked="checked" '; ?> id="nlws_lnm_display_title" name="nlws_lnm_display_title" value="1" />
		<label for="nlws_lnm_display_title">Display parent menu title</label><br />
		
		<input type="checkbox" <?php if ($options['linkTitle']) echo ' checked="checked" '; ?> id="nlws_lnm_link_title" name="nlws_lnm_link_title" value="1" />
		<label for="nlws_lnm_link_title">Link parent menu title</label>
	</p>
	<h4>
		Multi-Level Options
	</h4>
	<p>
		<label for="nlws_lnm_menu_depth">Menu Depth:</label><br />
		<input type="text" id="nlws_lnm_menu_depth" name="nlws_lnm_menu_depth" value="<?php echo $options['menuDepth']; ?>" />
		<ul>
			<li><strong>0 (default)</strong> Displays pages at any depth and arranges them hierarchically in nested lists</li>
			<li><strong>-1</strong> Displays pages at any depth and arranges them in a single, flat list</li>
			<li><strong>1</strong> Displays top-level Pages only</li>
			<li><strong>2, 3, ...</strong> Displays Pages to the given depth</li>
		</ul>
	</p>
	<input type="hidden" id="nlws_lnm_submit" name="nlws_lnm_submit" value="1" />
	<?php
}

function nlws_lnm_init() {
	wp_register_sidebar_widget(
		"lnm_widget",
		"Local Navigation Extended",
		"nlws_lnm_process",
		array (
			'description' => 'Output a local menu based on the page the user is one.'
		)
	);
	wp_register_widget_control('lnm_widget', "Local Nav Menu", "nlws_lnm_control");  
}