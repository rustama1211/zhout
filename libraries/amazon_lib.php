<?php if (! defined("BASEPATH")) die ("No Direct Script Access Allowed");
class Amazon_lib 
{
	private $CI = NULL;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('zhout/amazon_config');
		$this->CI->load->model('zhout/model_amazon');
	}
	
	function update_category_amazon()
	{
		if ( $_data = $this->CI->model_amazon->get_product_category_amazon())
		{
			foreach ($_data as $_index => $_value)
			{
				$_category_amazon = $this->aws_signed_request(array('Operation'=>'BrowseNodeLookup','BrowseNodeId'=>$_value['id_category']));
				var_dump($_category_amazon);
			}	
		}
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
       $response = @file_get_contents($request);
   
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
