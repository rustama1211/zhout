<?php if (! defined ('BASEPATH')) die ('No Direct scrip access allowed');

	class Ajax_zhout extends Cintroller
	{
		function __construct()
		{
			parent::__construct;
			$this->load->model('zhout/model_zhout');
		}
		
		function get_dropdown_comment($_id_zhout)
		{
			$this->model_zhout->
		}
		
	}
?>
