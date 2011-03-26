<?php if (! defined("BASEPATH")) die ("No Direct Script Access Allowed");
class Amazon_lib 
{
	private $CI = NULL;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('zhout/amazon_config');
		$this->CI->load->model('zhout/model_amazon');
		$this->CI->load->helper('form');
	}
	
	
	function update_category_amazon()
	{
		if ( $_data = $this->CI->model_amazon->get_product_category_amazon())
		{
			foreach ($_data as $_index => $_value)
			{
				$_category_amazon = $this->aws_signed_request(array('Operation'=>'BrowseNodeLookup','BrowseNodeId'=>$_value['id_category']));
				$_browse_node_children = get_object_vars($_category_amazon->BrowseNodes->BrowseNode->Children);
				$_browse_node_parent = $_category_amazon->BrowseNodes->BrowseNode->BrowseNodeId;
				
				foreach ($_browse_node_children as $_value_node_children)
				{
					//$this->CI->model_amazon->add_category_amazon_by_parent_id
					if (is_array($_value_node_children))
					{
						foreach ($_value_node_children as $_value_child)
						{
							$this->CI->model_amazon->add_category_amazon_by_parent_id(array('id_category'=>$_value_child->BrowseNodeId,'category_name'=>(string) $_value_child->Name,'parent_category'=>$_browse_node_parent));
						}
					}
					
				}
				
			}	
		}
	}
	
	function get_product_update_by_category_level_2()
	{
		//var_dump($this->amazon_lib->get_product_update_by_category_level_2());
		$_data = $this->CI->model_amazon->get_product_category_amazon_level_2();
		foreach ($_data as $_value_parent)
		{
			
		$_output =$this->aws_signed_request(array('Operation'=>'BrowseNodeLookup','BrowseNodeId'=>$_value_parent['id_category'] ,'ResponseGroup'=>'NewReleases'));
		$_data_object = get_object_vars( $_output->BrowseNodes->BrowseNode->TopItemSet);
		
			if (! empty($_data_object))
			{
			foreach ($_data_object as $_value)
				{
					if(is_array( $_value))
					{
						echo '<table>';
						foreach ($_value as $_sub_value)
						{
						
							//echo '<tr>';
							//echo 'ASIN : '.$_sub_value->ASIN.'\n';
			   				//echo 'Title : '.$_sub_value->Title.'\n';
							//echo  'Detail Page : '. $_sub_value->DetailPageURL.'\n';
							//echo 'Product_Group : '.$_sub_value->ProductGroup.'\n';
							//echo '</tr>';
							
							$_product_item_detail = $this->aws_signed_request(array('Operation'=>'ItemLookup','ItemId'=>$_sub_value->ASIN,'ResponseGroup'=>'Images,ItemAttributes,ItemIds'));
							
							//var_dump($_product_item_detail->Items->Item);
							
							$_data_product = array ( 'id_product' =>(string)$_product_item_detail->Items->Item->ASIN,
													 'product_brand'=>(''!=(string)$_product_item_detail->Items->Item->ItemAttributes->Label)?(string)$_product_item_detail->Items->Item->ItemAttributes->Label:'Not Available',
													 'product_detail_page_link'=>(string)$_product_item_detail->Items->Item->DetailPageURL,
													 'product_image_link'=>(string)$_product_item_detail->Items->Item->MediumImage->URL,
													 'product_date_add' => strtotime(date("Y-m-d H:i:s")),
													 'product_title' =>(string)$_product_item_detail->Items->Item->ItemAttributes->Title,
													 'product_price' => ('' != (string)$_product_item_detail->Items->Item->ItemAttributes->ListPrice->FormattedPrice)?(string)$_product_item_detail->Items->Item->ItemAttributes->ListPrice->FormattedPrice:'Not Available',
													 'id_category' =>$_value_parent['id_category']
													);
												
								//var_dump($_data_product);				
							$this->CI->model_amazon->add_product_amazon($_data_product);
							
							//var_dump($_product_item_detail->Items->Item->MediumImage->URL);
						}
						echo '</table>';
				
					}
				
				}
		
			}
		}
		
		//var_dump($_data);
	}
	      
    function aws_signed_request($params = array())
   {
        /*
        Copyright (c) 2009 Ulrich Mierendorff
     
       Permission is hereby granted, free of charge, to any person obtaining a
       copy of this software and associated documentation files (the "Software"),
       to deal in the Software without restriction, including without limitation
       the rights to use, copy, modify, merge, publish, distribute, sublicense,
       and/or sell copies of the Software, and to permit persons to whom the
       Software is furnished to do so, subject to the following conditions:
    
       The above copyright notice and this permission notice shall be included in
       all copies or substantial portions of the Software.
    
       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
       IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
       FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
       THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
       LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
       FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
       DEALINGS IN THE SOFTWARE.
       */
    
       /*
       Parameters:

           $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
                           "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
           $public_key - your "Access Key ID"
           $private_key - your "Secret Access Key"
       */
    
       // some paramters
       $method = "GET";
       $host = "ecs.amazonaws.".$this->CI->config->item('REGION');
       $uri = "/onca/xml";
    
       // additional parameters
       $params["Service"] = "AWSECommerceService";
       $params["AWSAccessKeyId"] = $this->CI->config->item('ACCESS_KEY_ID');
       // GMT timestamp
       $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
       // API version
       $params["Version"] = $this->CI->config->item('VERSION_AMAZON');
    
       // sort the parameters
       ksort($params);
    
       // create the canonicalized query
       $canonicalized_query = array();
       foreach ($params as $param=>$value)
       {
           $param = str_replace("%7E", "~", rawurlencode($param));
           $value = str_replace("%7E", "~", rawurlencode($value));
           $canonicalized_query[] = $param."=".$value;
       }
       $canonicalized_query = implode("&", $canonicalized_query);
    
       // create the string to sign
       $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
       // calculate HMAC with SHA256 and base64-encoding
       $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->CI->config->item('ACCESS_SECRET_KEY'), True));
    
       // encode the signature for the request
       $signature = str_replace("%7E", "~", rawurlencode($signature));
  
       // create request
       $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
   
       // do request
	   //Initialize the Curl session
		$ch = curl_init();
		//Set the URL
		curl_setopt($ch, CURLOPT_URL, $request);
		//Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);		
		//Execute the fetch
		$response = curl_exec($ch);
		//Close the connection
		curl_close($ch);	
		/*File Get Contents Lebih Lama*/
		//$response =@file_get_contents($request);
		
       if ($response === False)
       {
           return False;
       }
       else
       {
           // parse XML
           $pxml = simplexml_load_string($response);
           if ($pxml === False)
           {
               return False; // no xml
           }
           else
           {
               return $pxml;
           }
       }
   }
  
	
}
?>
