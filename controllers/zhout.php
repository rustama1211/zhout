<?php
   class Zhout extends Controller 
   {
		function __construct()
		{
			parent::__construct();
			// For Testing Wall
			$this->load->config('zhopie');
			
			/*setting aset Group*/
			$this->load->library('site/bep_assets','zhopie/assets_zhopie');
			$this->bep_assets->load_asset_group('ZHOPIE_INTERIOR');
			
			/*helper*/
			$this->load->helper('profile/profile');
			$this->load->helper('zhopie_poin/showpoin');
			$this->load->helper('search/mainsearch');
			$this->load->helper('categories/categories');
			$this->load->helper('zhopie_poin/poininfo');
			$this->load->helper('friends/friends');
			$this->load->helper('product/mywishlist');
			$this->load->helper('shop/myshop');
			$this->load->helper('zhout/validasi_comment');
			
			/*library*/
			$this->load->library('site/bep_site');
			
			
			/*Model*/
			$this->load->model('product/model_product');
		} 
		
		function index()
		{
			/* TES AMAZON GET_DATA_XML */
			$this->load->library('zhout/amazon_lib');
			$_output = $this->amazon_lib->aws_signed_request(array('Operation'=>'BrowseNodeLookup','BrowseNodeId'=>'1036682' ,'ResponseGroup'=>'NewReleases'));
			$this->amazon_lib->get_product_update_by_category_level_2();
			
			
			//$this->load->library('zhout/zhout_lib');
			//$data['_page'] 	= 'zhout/zhout_content';
			//var_dump($this->zhout_lib->get_zhout_content_by_id_member('dasda'));
		} 
		
		function update_category_amazon()
		{
			$this->load->library('zhout/amazon_lib');
			$this->amazon_lib->update_category_amazon();
		} 
		
		function show_zappos_product()
		{
			$this->load->library('zhout/zappos_lib');
			$this->zappos_lib->add();
		}
		
		function insert_comment()
		{
			$id_zhout 		= $this->uri->segment(3);
			$write_comment 	= checkValues(urldecode($this->uri->segment(4)));
			$date 			= strtotime(date("Y-m-d H:i:s"));
			if($write_comment =='')
				$write_comment ='/';
			
			$data = array(
				'id_member'			=> $this->session->userdata('id_member'),
				'id_zhout'			=> $id_zhout,
				'comment_content'	=> $write_comment,
				'userip'			=> '',
				'date'				=> $date,
				'active'			=> 1
			);
			
			$this->model_product->insert_comment($data);
		}
   }
?>
