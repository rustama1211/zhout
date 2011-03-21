<?php
   class Model_amazon extends Model
   {
   	 function __construct()
	 {
	 	parent::__construct();
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
		if($_data->num_rows())
		{
			return $_data->result_array();
		}
		
		return FALSE;
	 }
	 
	/*----------------------------------------------*
	 * 												*
	 * --Insert Ignore Category Amazon by Parent ---*
	 * 												*
	 *----------------------------------------------*/ 
	 function add_category_amazon_by_parent_id($_data = array())
	 {
	 	$this->db->ignore();
	 	$this->db->insert('tb_amazon_category',$_data);
	 }
	 
	 /*----------------------------------------------*
	 * 												*
	 * --- Get Category Level 2 Product Amazon -----*
	 * 												*
	 *----------------------------------------------*/		
	 function  get_product_category_amazon_level_2()
	 {
	 	$this->db->select('*');
		$this->db->where('parent_category IS NOT NULL');
		$_data = $this->db->get('tb_amazon_category');
		if($_data->num_rows())
		{
			return $_data->result_array();
		}
		
		return FALSE;
	 }
	 
	 /*----------------------------------------------*
	 * 												*
	 * --------- Insert Product Amazon  ------------*
	 * 												*
	 *----------------------------------------------*/		
	 function  add_product_amazon($_data = array())
	 {
	 	$this->db->ignore();
	 	$this->db->insert('tb_amazon_product',$_data);
	 }
	 
	 /*----------------------------------------------*
	 * 												*
	 * -------- Get Product Amazon By Id  ----------*
	 * 												*
	 *----------------------------------------------*/		
	 function  get_product_amazon_by_id($_id_product)
	 {
	 	$this->db->select('*');
		$this->db->where('id_product',$_id_product);
	 	$_data_amazon_product = $this->db->get('tb_amazon_product');
		if (count($_data_amazon_product->result_array()))
		{
			return array_shift($_data_amazon_product->result_array());
		}
		else
		{
			return FALSE;
		}
	 }
	 
   }
?>
