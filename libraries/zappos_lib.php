<?php if (! defined ("BASEPATH")) die ("No Direct Script Access Allowed");
  
  class Zappos_lib
  {
  	private $CI = NULL;
  	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('zhout/zappos_config');
		$this->CI->load->model('zhout/model_zappos');
		
	}
	
	function add()
	{
		
		foreach (($_data_category = $this->CI->model_zappos->get_product_category()) as $_value_category)
		{
			
			
			 $_url = 'http://api.zappos.com/Search?term='.str_replace(' ','+',$_value_category['category_name']).'&includes=["isNew","onSale"]&filters={"isNew":["true"],"onSale":["true"]}&sort={"goLiveDate":"desc"}&limit=100&key='.$this->CI->config->item('ZAPPOS_KEY');
			 $_curl = curl_init();
			 curl_setopt($_curl,CURLOPT_URL,$_url);
			 curl_setopt($_curl,CURLOPT_RETURNTRANSFER,1);
			 curl_setopt($_curl,CURLOPT_CONNECTTIMEOUT,10);
			 $_response = curl_exec($_curl);
			 curl_close($_curl);
			 
			$_data_product = get_object_vars(json_decode($_response));
			
			if(isset($_data_product['results']) && is_array($_data_product['results']) && count($_data_product['results']))
			{
				foreach ($_data_product['results'] as $_value_product)
				{
					$_val_product = (is_object($_value_product))? get_object_vars($_value_product) : $_value_product;
				
					$_data_product_zappos = array ( 	 'id_product' =>$_val_product['productId'],
														 'product_brand'=>$_val_product['brandName'],
														 'product_detail_page_link'=>$_val_product['productUrl'],
														 'product_image_link'=>$_val_product['thumbnailImageUrl'],
														 'product_date_add' =>strtotime(date("Y-m-d H:i:s")),
														 'product_title' =>$_val_product['productName'],
														 'product_price' =>$_val_product['price'],
														 'id_category' =>$_value_category['id_zappos_category']
														);
					$this->CI->model_zappos->add_product($_data_product_zappos);
														
				}
			}
			
		}
	}
	
  }
  
?>
