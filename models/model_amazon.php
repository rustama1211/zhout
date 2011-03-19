<?php
   class Model_amazon extends Model
   {
   	 function __construct()
	 {
	 	parent::__construct();
	 }
	 
	 function add_product_amazon($_data = array())
	 {
	 	$this->insert('tb_amazon_category',$_data);
	 }
	 
	 
	 
	/*----------------------------------------------*
	 * 												*
	 * --- Get Category Default Product Amazon -----*
	 * 												*
	 *----------------------------------------------*/		
	 function  get_product_category_amazon()
	 {
	 	$this->db->select('*');
		$this->db->where('parent_category IS NULL');
		$_data = $this->db->get('tb_amazon_category');
		if(count($_data->num_rows))
		{
			return $_data->result_array();
		}
		
		return FALSE;
	 }
	 
   }
?>
