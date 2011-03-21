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
		 
		  /*----------------------------------------------*
		 * 												*
		 * ------Get Zappos Product By Id Product ------*
		 * 												*
		 *----------------------------------------------*/ 
		 function get_product_zappos_by_id($_id_product)
		 {
		 	$this->db->select('*');
			$this->db->where('id_product',$_id_product);
			$_data = $this->db->get('tb_zappos_product');
			return array_shift($_data->result_array());
		 }
	}
?>
