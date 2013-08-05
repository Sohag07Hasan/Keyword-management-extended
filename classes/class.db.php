<?php 

/**
 * Handles database
 * */
class JfKeywordDb{
	
	private $keyword;
	private $keyword_meta;
	public $db;
	
	//constructor
	function __construct(){
		global $wpdb;
		$this->db = $wpdb;
		$this->keyword = $this->db->prefix . 'keywords';
		$this->keyword_meta = $this->db->prefix . 'keyword_meta';
	}
	
	//create db table
	function sync_db(){
		$sql = array();
		
		$sql[] = "create table if not exists $this->keyword(
			ID bigint not null auto_increment,
			keyword varchar(255) not null,
			priority int not null,
			post_id bigint not null default 0,
			status tinyint not null default 1,
			primary key(ID),
			unique(keyword)
		)";
		
		$sql[] = "create table if not exists $this->keyword_meta(
			meta_id bigint not null auto_increment,
			keyword_id bigint not null,
			meta_key text not null,
			meta_value text not null,
			primary key(meta_id)
		)";
		
		
		foreach($sql as $s){
			$this->db->query($s);
		}
	}
	
	
	//create a new keyword and retun the keyword id
	function create_keyword($posted){
				
		if(isset($posted['id']) && !empty($posted['id'])) return $this->update_keyword($posted);
		
		extract($posted, EXTR_SKIP);
		
		//var_dump($keyword); exit;
		
		$inserted = $this->db->insert($this->keyword, array('keyword' => $keyword, 'priority' => $priority), array('%s', '%d'));
		
		if($inserted){
			return $this->db->insert_id;
		}
		else{
			return false;
		}
	}
	
	//update exising keyword and return teh keyword id
	function update_keyword($posted){
		extract($posted, EXTR_SKIP);
		$updated = $this->db->update($this->keyword, array('keyword' => $keyword, 'priority' => $priority), array('ID' => $id), array('%s', '%d'), array('%d'));

		if($updated){
			return $posted['id'];
		}
		else{
			return false;
		}
	}

	
	//get a keyword return an object
	function get_keyword($keyword_id = null){
		return $this->db->get_row("select * from $this->keyword where ID = '$keyword_id'");
	}
	
	//get all the keywords for csv
	function get_keywords_for_csv(){
		return $this->db->get_results("select * from $this->keyword");
	}

	
	//get total keywords
	function get_total_keywords($search = null){
		$sql = "select count(ID) from $this->keyword";
		if($search){
			$sql .= " where keyword like '%$search%'";
		}
		return $this->db->get_var($sql);
	}
	
	
	//return table names
	function get_keyword_table(){
		return $this->keyword;
	}
	
	
	//delete a keyword
	function delete_keyword($keyword_id){
		$sql = array();
		$sql[] = "delete from $this->keyword where ID = '$keyword_id'";
		$sql[] = "delete from $this->keyword_meta where keyword_id = '$keyword_id'";
		foreach($sql as $s){
			$this->db->query($s);
		}
	}
}
