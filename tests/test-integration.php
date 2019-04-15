<?php
/**
 * Class CrispyEnigmaIntegrationTests
 *
 * @package Wp_Crispy_Enigma
 */

class CrispyEnigmaIntegrationTests extends WP_UnitTestCase {

	const POST_TYPE = 'ce-to-do';
	const PLUGIN = 'wp-crispy-enigma/wp-crispy-enigma.php';
	const DUE_DATE_FIELD = 'ce_to_do_due_date';
	const ERROR_TRANSIENT = 'ce_to_do_save_post_errors_';

	public function setUp()
    {
        parent::setUp();
       	activate_plugin(self::PLUGIN);
       	wp_set_current_user(1);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

	public function test_cpt_exists() {
		$this->assertTrue( post_type_exists(self::POST_TYPE) );
	}

	public function test_create_to_do_without_due_date() {
		
		//Spoof custom field default settings;
		$_POST['hidden_ce_to_do_clear'] = 0;
		$_POST['ce_to_do_month'] = date('n');
		$_POST['ce_to_do_day'] = '';
		$_POST['ce_to_do_year'] = date('Y');
		
		$factoryId = $this->factory()->post->create(['post_type' => self::POST_TYPE]);
	    
	    $this->assertNotWPError($factoryId);
	    
	    $factoryPost = get_post($factoryId);

	    $this->assertNotNull($factoryPost);
	    $this->assertEquals($factoryPost->post_type, self::POST_TYPE);

	    $meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);
	   	
	   	$this->assertEquals($meta, '');	    
	}

	public function test_create_to_do_with_valid_due_date() {
			
		//Spoof custom field settings;
        $_POST['ce_to_do_month'] = date('n');
		$_POST['ce_to_do_day'] = date('j');
		$_POST['ce_to_do_year'] = date('Y');
		$_POST['hidden_ce_to_do_clear'] = 0;

		$factoryId = $this->factory()->post->create(['post_type' => self::POST_TYPE]);                                    
           
        $meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);
        
        //test the meta value was updated correctly
        $this->assertEquals($meta, $_POST['ce_to_do_month'] . '-' . $_POST['ce_to_do_day'] . '-' . $_POST['ce_to_do_year']);
	}

	public function test_create_to_do_with_invalid_due_date() {
			
		//Spoof custom field settings;
        $_POST['ce_to_do_month'] = 1;
		$_POST['ce_to_do_day'] =  'ss';
		$_POST['ce_to_do_year'] = 2018;
		$_POST['hidden_ce_to_do_clear'] = 0;

		$factoryId = $this->factory()->post->create(['post_type' => self::POST_TYPE]);                                    
          
		$transient = self::ERROR_TRANSIENT .  $factoryId . '_' . get_current_user_id();

		$error = get_transient( $transient ); 
		$this->assertEquals($error, 'error');

        $meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);
 
        //test the meta value was not changed
        $this->assertEquals($meta, '');
	}

	public function test_to_do_clear_due_date_with_valid_date() {
			
		//Spoof custom field default settings;
        $_POST['ce_to_do_month'] = date('n');
		$_POST['ce_to_do_day'] = date('j');
		$_POST['ce_to_do_year'] = date('Y');
		$_POST['hidden_ce_to_do_clear'] = 0;

		$factoryId = $this->factory()->post->create(['post_type' => self::POST_TYPE]);                                    
           
        $meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);
        
        //test the meta value was updated correctly
        $this->assertEquals($meta, $_POST['ce_to_do_month'] . '-' . $_POST['ce_to_do_day'] . '-' . $_POST['ce_to_do_year']);

        $_POST['hidden_ce_to_do_clear'] = 1;
        $post = get_post($factoryId, 'ARRAY_A');
		$factoryId = $this->factory()->post->update_object($factoryId, $post);   

		$meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);

        //test the meta value was cleared
        $this->assertEquals($meta, '');

	}

	public function test_to_do_clear_due_date_with_invalid_date() {
			
		//Spoof custom field settings;
        $_POST['ce_to_do_month'] = date('n');
		$_POST['ce_to_do_day'] = date('j');
		$_POST['ce_to_do_year'] = date('Y');
		$_POST['hidden_ce_to_do_clear'] = 0;

		$factoryId = $this->factory()->post->create(['post_type' => self::POST_TYPE]);                                    
           
        $meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);
        
        //test the meta value was updated correctly
        $this->assertEquals($meta, $_POST['ce_to_do_month'] . '-' . $_POST['ce_to_do_day'] . '-' . $_POST['ce_to_do_year']);

        //Spoof custom field settings;
		$_POST['ce_to_do_year'] = date('2018');
        $_POST['hidden_ce_to_do_clear'] = 1;
        
        $post = get_post($factoryId, 'ARRAY_A');
		$factoryId = $this->factory()->post->update_object($factoryId, $post);   

		$meta = get_post_meta($factoryId, self::DUE_DATE_FIELD, true);

        //test the meta value was cleared
        $this->assertEquals($meta, '');

	}
}
