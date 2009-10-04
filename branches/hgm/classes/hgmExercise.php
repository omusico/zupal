<?php

	class hgmExercise(){
	
		$input_array = array();
		$page_content = '';
	
		/* Sets the input array with the parameters needed to parse the data */
		public setInput($input_array){
			$this->input_array = $input_array;
		} /* end public setInput($input_array) */
		
		/* Sets a page's content based on the url, or if no URL present, off the HTML submitted */
		public setContent(){
			if(isset($this->input_array ['input_url'])){
				$this->page_content = file_get_contents($this->input_array['input_url']);
			} else {
				$this->page_content = file_get_contents($this->input_array['input_html']);
			}
		} /* end public setContent() */
		
		/* Format and returns the array with the processed data */
		public getReturn($format = 'json'){
		
			$return_array = $this->parseData();
			
			// For now just returning JSON objects:
			if($format == 'json'){
				return json_encode($return_array);
			} // else { other return options here...
		
		} /* end public getReturn($format = 'json') */
		
		/* Parse the data requested */
		private parseData(){
		
			// Get all links:
			$start_tag = "<img"
			$end_tag = ">";
    		preg_match_all("($start_tag(.*)$end_tag)siU", $this->page_content, $dataObj);
 
			return $dataObj[0];
		
		}
		

	
	} /* end hgmExercise() */


?>
