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
			$this->load->library('site/bep_site');
		} 
		
		function index()
		{
			/* TES AMAZON GET_DATA_XML */
			$this->load->library('zhout/amazon_lib');
			$_output = $this->amazon_lib->aws_signed_request(array('Operation'=>'BrowseNodeLookup','BrowseNodeId'=>'1036682' ,'ResponseGroup'=>'NewReleases'));
			
			$_data_object = get_object_vars( $_output->BrowseNodes->BrowseNode->TopItemSet);
			
			foreach ($_data_object as $_value)
			{
				if(is_array( $_value))
				{
					echo '<table>';
					foreach ($_value as $_sub_value)
					{
					
						/*echo '<tr>';
						echo 'ASIN : '.$_sub_value->ASIN.'\n';
		   				echo 'Title : '.$_sub_value->Title.'\n';
						echo  'Detail Page : '. $_sub_value->DetailPageURL.'\n';
						echo 'Product_Group : '.$_sub_value->ProductGroup.'\n';
						echo '</tr>';*/
						
						$_product_item_detail = $this->amazon_lib->aws_signed_request(array('Operation'=>'ItemLookup','ItemId'=>$_sub_value->ASIN,'ResponseGroup'=>'Images,ItemAttributes,ItemIds'));
						var_dump($_product_item_detail);
					}
					
					echo '\n';
				
				
				}
			
			}
			
			//$this->load->library('zhout/zhout_lib');
			//$data['_page'] 	= 'zhout/zhout_content';
			//var_dump($this->zhout_lib->get_zhout_content_by_id_member('dasda'));
		} 
		
		function update_category_amazon()
		{
			$this->load->library('zhout/amazon_lib');
			$this->amazon_lib->update_category_amazon();
		} 	
   }
?>
