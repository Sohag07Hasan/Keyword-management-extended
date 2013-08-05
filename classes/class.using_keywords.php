<?php 
/*
 * This class is to use the keywords in posts pages titles
 * */
class JfKeywordUsing{
	
	static $metaboxes = array();
	
	//constructor
	static function init(){
		add_action('add_meta_boxes', array(get_class(), 'add_new_meta_boxes'));
		add_action('admin_enqueue_scripts', array(get_class() , 'admin_enqueue_scripts'));
	}
	
	
	//metabox addition
	static function add_new_meta_boxes(){
		self::$metaboxes[] = array(
			'id' => 'add_key_word',
			'title' => 'Use Keyword',
			'callback' => array(get_class(), 'key_word_field'),
			'post_type' => 'post',
			'context' => 'advanced',
			'priority' => 'high'				
		);
		
		foreach(self::$metaboxes as $mbox){
			add_meta_box($mbox['id'], $mbox['title'], $mbox['callback'], $mbox['post_type'], $mbox['context'], $mbox['priority']);
		}
	}
	
	
	//metabox content
	static function key_word_field($post){
		include JfKeywordManagement::abspath_for_script('includes/metaboxes/key_word_field.php');
	}
	
	
	//enqueue script and 
	static function admin_enqueue_scripts(){
		wp_enqueue_script('jquery');
		wp_register_script('keywords_auto_complete_js', self::get_url('asset/autocompleteui/jquery-ui-1.10.3.custom.min.js'), array('jquery'));
		wp_enqueue_script('keywords_auto_complete_js');
		
		//controller script
		wp_register_script('keywords_auto_complete_controller_js', self::get_url('js/controller.js'), array('jquery'));
		wp_enqueue_script('keywords_auto_complete_controller_js');
		wp_localize_script('keywords_auto_complete_controller_js', 'AjaxAutoComplete', array('ajax_url'=>self::get_url('ajax/autocomplete.php')));
		
		wp_register_style('keywords_auto_complete_css', self::get_url('asset/autocompleteui/jquery-ui-1.10.3.custom.min.css'));
		wp_enqueue_style('keywords_auto_complete_css'); 
		
		
	}
	
	
	//get the url of the scripts
	static function get_url($script = ''){
		return JFKEYWORDMANAGEMENT_URL . $script;
	}
}
