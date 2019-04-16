<?php

require_once CE_ABSPATH . '/includes/todo.php';

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;
use Brain\Monkey;

class CrispyEnigmaUnitTests extends PluginTestCase {

    private $todo;

    public function setUp() {
        parent::setUp();       
    }

    public function tearDown() {
        parent::tearDown();
    }

	public function testInit() {

		// validate specific register actication
		Functions\expect('register_activation_hook')
		  ->once();

        // Since testing plugin return false for any is admin check
        Functions\when( 'is_admin' )
            ->justReturn( false );

        $todo = new CE_To_Do();
        $todo->init();

        $this->assertTrue( has_action('load-post-new.php', 'CE_To_Do->admin_scripts()') );
        $this->assertTrue( has_action('init', 'CE_To_Do->custom_post_type()') );
    }

    public function test_admin_scripts() {
      
        Functions\expect('wp_enqueue_script')
          ->with('ce-to-do-admin', '/wp-content/plugins/wp-crispy-enigma/js/script.js')
          ->once();

        $todo = new CE_To_Do();
        $todo->admin_scripts();
    }

    public function test_due_field_not_cpt() {
        global $post;
        $post = new stdClass();
        $post->post_type = 'post';

        $todo = new CE_To_Do();
        $this->assertFalse( $todo->due_field() );
        unset($post);
    }

    public function test_save_user_cant_edit() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( false );

        $todo = new CE_To_Do();   
        $this->assertFalse($todo->save(1));
    }

    public function test_save_clear_due_date() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( true );

        $_POST['hidden_ce_to_do_clear'] = 1;
        
        Functions\expect('delete_post_meta')
          ->with(1, 'ce_to_do_due_date');

        $todo = new CE_To_Do();   
        $this->assertFalse($todo->save(1));
    }    

    public function test_save_no_due_date() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( true );

        $_POST['hidden_ce_to_do_clear'] = 0;
        
        Functions\expect('delete_post_meta')
          ->with(1, 'ce_to_do_due_date');

        $todo = new CE_To_Do();   
        $this->assertFalse($todo->save(1));
    }   

    public function test_save_valid_due_date() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( true );

        $_POST['hidden_ce_to_do_clear'] = 0;
        $_POST['ce_to_do_month'] = date('n');
        $_POST['ce_to_do_day'] = date('j');
        $_POST['ce_to_do_year'] = date('Y');

        Functions\expect('update_post_meta')
          ->with(1, 'ce_to_do_due_date', date('n') . '-' . date('j') . '-' . date('Y'));

        $todo = new CE_To_Do();   
        $this->assertTrue($todo->save(1));
    }

    public function test_save_due_date_in_past() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( true );

        $_POST['hidden_ce_to_do_clear'] = 0;
        $_POST['ce_to_do_month'] = '1';
        $_POST['ce_to_do_day'] = '1';
        $_POST['ce_to_do_year'] = '2000';

        Monkey\Functions\when( 'get_current_user_id' )
            ->justReturn( 1 );

        Functions\expect('set_transient')
          ->with('ce_to_do_save_post_errors_1_1', 'error', 60);

        $todo = new CE_To_Do();   
        $this->assertFalse($todo->save(1));
    }  

    public function test_save_invalid_due_date() {

        Monkey\Functions\when( 'current_user_can' )
            ->justReturn( true );

        $_POST['hidden_ce_to_do_clear'] = 0;
        $_POST['ce_to_do_month'] = date('n');
        $_POST['ce_to_do_day'] = 200;
        $_POST['ce_to_do_year'] = date('Y');

        Monkey\Functions\when( 'get_current_user_id' )
            ->justReturn( 1 );

        Functions\expect('set_transient')
          ->with('ce_to_do_save_post_errors_1_1', 'error', 60);

        $todo = new CE_To_Do();   
        $this->assertFalse($todo->save(1));
    }       

    public function test_due_field_cpt() {
        global $post;
        $post = new stdClass();
        $post->post_type = 'ce-to-do';
        $post->ID = 1;

        Functions\expect('get_post_meta')
          ->with(1, 'ce_to_do_due_date', true)
          ->andReturn('1-1-2019');

        Monkey\Functions\when( 'selected' )
            ->justReturn( false );

        $todo = new CE_To_Do();
        
        ob_start();
        $this->assertNull($todo->due_field());
        $output = ob_get_clean();
        $this->assertFalse( empty($output) );
        unset($post);
    }

    public function test_create_cpt() {
        
        $labels = array(
            'name'                => __( "To-Do's", 'Post Type General Name', 'twentynineteen' ),
            'singular_name'       => __( 'To-Do', 'Post Type Singular Name', 'twentynineteen' ),
            'menu_name'           => __( 'To-Do', 'twentynineteen' ),
            'item_published'        => __( 'To-Do Saved', 'twentynineteen' ),
            'add_new_item'          => __( 'Add New To-Do', 'twentynineteen' ),
            'edit_item'         => __( 'Edit To-Do', 'twentynineteen' ),
            'new_item'          => __( 'New To-Do', 'twentynineteen' ),
            'view_item'         => __( 'View To-Do', 'twentynineteen' ),
            'view_items'            => __( 'View To-Dos', 'twentynineteen' ),
            'search_items'          => __( 'Edit To-Do', 'twentynineteen' ),
            'not_found_in_trash'    => __( 'No To-Dos found in Trash', 'twentynineteen' ),
            'not_found'         => __( 'No To-Dos found', 'twentynineteen' ),
            'all_items'         => __( 'All To-Dos', 'twentynineteen' ),
            'archives'          => __( 'To-Dos Archives', 'twentynineteen' ),
            'attributes'            => __( 'To-Do Attributes', 'twentynineteen' ),
            'insert_into_item'          => __( 'Insert into To-Do', 'twentynineteen' ),
            'uploaded_to_this_item'         => __( 'Uploaded to To-Do', 'twentynineteen' ),
            'item_published'            => __( 'To-Do published.', 'twentynineteen' ),
            'item_published_privately'          => __( 'To-Do published privately', 'twentynineteen' ),
            'item_reverted_to_draft'            => __( 'To-Do reverted to draft.', 'twentynineteen' ),
            'item_scheduled'            => __( 'To-Do scheduled.', 'twentynineteen' ),
            'item_updated'          => __( 'To-Do Updated', 'twentynineteen' ),
        );
        $args = array(
            'description'         => __( 'Things that I need to do!', 'twentynineteen' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author', 'comments' ),
            'taxonomies'          => array( 'category' ),
            'hierarchical'        => false,
            'public'              => true,
            'rewrite'               => array('slug' => 'to-do'),
            'query_var'          => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 20,
            'menu_icon'              => 'dashicons-welcome-write-blog',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            
        );

        Functions\expect( 'register_post_type' )
            ->once()
            ->with( 'ce-to-do', $args );

        // register post type
        $todo = new CE_To_Do();
        $todo->custom_post_type();
    }
}