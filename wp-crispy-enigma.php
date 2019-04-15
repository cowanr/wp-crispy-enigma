<?php

/*
Plugin Name: Simple ToDo
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: A Basic WordPress Plugin for illustrating CI and unit testing.   Creates a custom post type for todo items.
Version:     1.0
Author:      Ryan Cowan
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_ce_to_do_init() {
 
    $labels = array(
        'name'                => __( "To-Do's", 'Post Type General Name', 'twentynineteen' ),
        'singular_name'       => __( 'To-Do', 'Post Type Singular Name', 'twentynineteen' ),
        'menu_name'           => __( 'To-Do', 'twentynineteen' ),
        'item_published'		=> __( 'To-Do Saved', 'twentynineteen' ),
        'add_new_item'			=> __( 'Add New To-Do', 'twentynineteen' ),
        'edit_item'			=> __( 'Edit To-Do', 'twentynineteen' ),
        'new_item'			=> __( 'New To-Do', 'twentynineteen' ),
        'view_item'			=> __( 'View To-Do', 'twentynineteen' ),
        'view_items'			=> __( 'View To-Dos', 'twentynineteen' ),
        'search_items'			=> __( 'Edit To-Do', 'twentynineteen' ),
        'not_found_in_trash'	=> __( 'No To-Dos found in Trash', 'twentynineteen' ),
        'not_found'			=> __( 'No To-Dos found', 'twentynineteen' ),
        'all_items'			=> __( 'All To-Dos', 'twentynineteen' ),
        'archives'			=> __( 'To-Dos Archives', 'twentynineteen' ),
        'attributes'			=> __( 'To-Do Attributes', 'twentynineteen' ),
        'insert_into_item'			=> __( 'Insert into To-Do', 'twentynineteen' ),
        'uploaded_to_this_item'			=> __( 'Uploaded to To-Do', 'twentynineteen' ),
        'item_published'			=> __( 'To-Do published.', 'twentynineteen' ),
        'item_published_privately'			=> __( 'To-Do published privately', 'twentynineteen' ),
        'item_reverted_to_draft'			=> __( 'To-Do reverted to draft.', 'twentynineteen' ),
        'item_scheduled'			=> __( 'To-Do scheduled.', 'twentynineteen' ),
        'item_updated'			=> __( 'To-Do Updated', 'twentynineteen' ),
    );
    $args = array(
        'description'         => __( 'Things that I need to do!', 'twentynineteen' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'author', 'comments' ),
        'taxonomies'          => array( 'category' ),
        'hierarchical'        => false,
        'public'              => true,
        'rewrite'				=> array('slug' => 'to-do'),
        'query_var'          => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'				 => 'dashicons-welcome-write-blog',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        
    );
     
    register_post_type( 'ce-to-do', $args );
 
}
add_action( 'init', 'custom_ce_to_do_init');

function ce_to_do_scripts() {
	wp_enqueue_script( 'ce-to-do', '/wp-content/plugins/wp-crispy-enigma/js/script.js' );
}
add_action( 'load-post-new.php', 'ce_to_do_scripts' );
add_action( 'load-post.php', 'ce_to_do_scripts' );

function ce_to_do_due_field() {

	global $post;

	if ( "ce-to-do" != $post->post_type ) return;

	$date = get_post_meta( $post->ID, 'ce_to_do_due_date', true );

	$month = date('n');
	$day = '';
	$year = date('Y');
	$label = __( 'Never', 'twentynineteen' );

	if(false !== $date && !empty($date)) {
		$dateArr = explode('-', $date); // n-j-Y https://codex.wordpress.org/Formatting_Date_and_Time
		$month = absint($dateArr[0]);
		$day = absint($dateArr[1]);
		$year = absint($dateArr[2]);
		$label = sanitize_text_field($date);
	}

	echo '
		<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="timestamp">
				Due <b>' . $label . '</b></span>
			<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> <span class="screen-reader-text">Edit Date</span></a>
			<fieldset id="duetimestampdiv" class="hide-if-js">
			<legend class="screen-reader-text">Date</legend>
				<div class="timestamp-wrap"><label><span class="screen-reader-text">Month</span><select id="ce_to_do_month" name="ce_to_do_month">
					<option value="" data-text="Never" ' . selected(empty($month), '', false) . '></option>
					<option value="1" data-text="Jan" ' . selected($month, '1', false) . '>Jan</option>
					<option value="2" data-text="Feb" ' . selected($month, '2', false) . '>Feb</option>
					<option value="3" data-text="Mar" ' . selected($month, '3', false) . '>Mar</option>
					<option value="4" data-text="Apr" ' . selected($month, '4', false) . '>Apr</option>
					<option value="5" data-text="May" ' . selected($month, '5', false) . '>May</option>
					<option value="6" data-text="Jun" ' . selected($month, '6', false) . '>Jun</option>
					<option value="7" data-text="Jul" ' . selected($month, '7', false) . '>Jul</option>
					<option value="8" data-text="Aug" ' . selected($month, '8', false) . '>Aug</option>
					<option value="9" data-text="Sep" ' . selected($month, '9', false) . '>Sep</option>
					<option value="10" data-text="Oct" ' . selected($month, '10', false) . '>Oct</option>
					<option value="11" data-text="Nov" ' . selected($month, '11', false) . '>Nov</option>
					<option value="12" data-text="Dec" ' . selected($month, '12', false) . '>Dec</option>
		</select></label> 
		<label><span class="screen-reader-text">Day</span><input type="text" id="ce_to_do_day" name="ce_to_do_day" value="' . $day . '" size="2" maxlength="2" autocomplete="off"></label>, <label><span class="screen-reader-text">Year</span><input type="text" id="ce_to_do_year" name="ce_to_do_year" value="' . $year . '" size="4" maxlength="4" autocomplete="off"></label> 


		<input type="hidden" id="hidden_ce_to_do_month" name="hidden_ce_to_do_month" value="' . $month . '">
		<input type="hidden" id="hidden_ce_to_do_day" name="hidden_ce_to_do_day" value="' . $day .'">
		<input type="hidden" id="hidden_ce_to_do_year" name="hidden_ce_to_do_year" value="' . $year . '">
		<input type="hidden" id="hidden_ce_to_do_clear" name="hidden_ce_to_do_clear" value="0">

		<p>
		<a href="#edit_timestamp" class="save-timestamp hide-if-no-js button">OK</a>
		<a href="#edit_timestamp" class="cancel-timestamp hide-if-no-js button-cancel">Cancel</a>
		<a href="#edit_timestamp" class="clear-timestamp hide-if-no-js button-cancel">Clear</a>
		</p>
				</fieldset>
		</div>';
}
add_action( 'post_submitbox_misc_actions', 'ce_to_do_due_field' );

function ce_to_do_save( $post_id ) {

	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}
	
	// Clear Due Date
	if($_POST['hidden_ce_to_do_clear'] == 1) {
		delete_post_meta( $post_id, 'ce_to_do_due_date' );
		return;
	}

	if( empty( $_POST['ce_to_do_month'] ) || empty($_POST['ce_to_do_day']) || empty($_POST['ce_to_do_year'])  ) {
		return;
	}

	// Validate Due Date.
	$month = absint($_POST['ce_to_do_month']);
	$day = absint($_POST['ce_to_do_day']);
	$year = absint($_POST['ce_to_do_year']);
	$error = false;

	if(true === checkdate($month, $day, $year)) {

		$due   = new DateTime($year . '-' . $month . '-' . $day . ' 24:00:00');
		$now   = new DateTime();
		if($due < $now) {
			$error = true;
		}
	} else {
		$error = true;
	}

	if($error) {
		set_transient('ce_to_do_save_post_errors_' . $post_id . '_' . get_current_user_id(), 'error', 60);
		return;
	}

	// Save	
	update_post_meta( $post_id, 'ce_to_do_due_date', $month . '-' . $day . '-' . $year );
	return;
}
add_action( 'save_post_ce-to-do', 'ce_to_do_save', 10, 1 );

function ce_to_do_error_message() {
	global $post;
	$transient = 'ce_to_do_save_post_errors_' .  $post->ID . '_' . get_current_user_id();
	if ( 'error' === get_transient( $transient ) ) {
  		echo '
		    <div class="error">
		        <p>' . __( 'Please enter a valid due date in the format of mm/dd/yyyy and that is not in the past!', 'twentynineteen' ) . '
		    </p>
		    </div>';
		delete_transient($transient);
	}
}
add_action( 'admin_notices', 'ce_to_do_error_message' );

add_filter( 'post_updated_messages', 'ce_to_do_updated_messages' );
function ce_to_do_updated_messages( $messages ) {

	global $post;

	if($post->post_type == 'ce-to-do') {

		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View To-Do', 'twentynineteen' ) );

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview To-Do', 'twentynineteen' ) );

		$messages['post'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'To-Do updated.', 'twentynineteen' ) . ' ' . $view_link,
			2  => __( 'Custom field updated.', 'twentynineteen' ),
			3  => __( 'Custom field deleted.', 'twentynineteen' ),
			4  => __( 'To-Do updated.', 'twentynineteen' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'To-Do restored to revision from %s', 'twentynineteen' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'To-Do published.', 'twentynineteen' ) . ' ' . $view_link,
			7  => __( 'To-Do saved.', 'twentynineteen' ),
			8  => __( 'To-Do submitted.', 'twentynineteen' ) . ' ' . $preview_link,
			9  => sprintf(
				__( 'To-Do scheduled for: <strong>%1$s</strong>.', 'twentynineteen' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'twentynineteen' ), strtotime( $post->post_date ) )
			) . ' ' . $view_link,
			10 => __( 'To-Do draft updated.', 'twentynineteen' ) . ' ' . $preview_link
		);		
	}
	return $messages;
}

function ce_to_do_rewrite_flush() {
    custom_ce_to_do_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ce_to_do_rewrite_flush' );

// Add the custom columns to the post type:
add_filter( 'manage_ce-to-do_posts_columns', 'ce_to_do_set_custom_edit_columns' );
function ce_to_do_set_custom_edit_columns($columns) {
    $columns['author'] = __( 'Responsible', 'twentynineteen' );
    $columns['status'] = __( 'Due', 'twentynineteen' );

    return $columns;
}

// Add the data to the custom columns for the To-Do post type:
add_action( 'manage_ce-to-do_posts_custom_column' , 'ce_to_do_custom_column', 10, 2 );
function ce_to_do_custom_column( $column, $post_id ) {
    switch ( $column ) {

        case 'status' :
	        $due_date = get_post_meta( $post_id, 'ce_to_do_due_date', true );

			if( ! empty( $due_date ) ) {
				$dateArr = explode('-', $due_date); // n-j-Y https://codex.wordpress.org/Formatting_Date_and_Time
				$month = absint($dateArr[0]);
				$day = absint($dateArr[1]);
				$year = absint($dateArr[2]);
				$due   = new DateTime($year . '-' . $month . '-' . $day . ' 24:00:00');
				$now   = new DateTime();
				if($due < $now) {
					echo 'Past Due';
				} else {
					echo $due_date;
				}
			} else {
				echo 'No Due Date';
			}

            break;

    }
}
