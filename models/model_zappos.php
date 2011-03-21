<?php
    class Model_zappos extends Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		/*----------------------------------------------*
		 * 												*
		 * --Insert Ignore Product Zappos By Category---*
		 * 												*
		 *----------------------------------------------*/ 
		 function add_product($_data= array())
		 {
		 	$this->db->ignore();
			$this->db->insert('tb_zappos_product',$_data);
		 }
		 
		 /*----------------------------------------------*
		 * 												*
		 * ----Get Zappos Category by children lvl 2----*
		 * 												*
		 *----------------------------------------------*/ 
		 function get_product_category()
		 {
		 	$this->db->select('*');
			$this->db->where('parent_id IS NOT NULL');
			$_data = $this->db->get('tb_zappos_category');
			return $_data->result_array();
		 }
	}
?>
