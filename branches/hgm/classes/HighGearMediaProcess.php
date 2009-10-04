<?php

class HighGearMediaProcess{
	
		private $input_array;
		private $page_content;
	
		/* Sets the input array with the parameters needed to parse the data */
		public function setInput($input_array){
			$this->input_array = $input_array;
		} /* end public setInput($input_array) */
		
		/* Sets a page's content based on the url, or if no URL present, off the HTML submitted */
		public function setContent(){
			if(isset($this->input_array ['input_url']) && $this->input_array ['input_url'] != ''){
				$this->page_content = file_get_contents($this->input_array['input_url']);
			} else {
				$this->page_content = $this->input_array['input_html'];
			}
		} /* end public setContent() */
		
		/* Format and returns the array with the processed data */
		public function getResults($format = 'json'){
		
			$return_array = $this->parseData();
			
			// For now just returning JSON objects:
			if($format == 'json'){
				return json_encode($return_array);
			} // else { other return options here...
		
		} /* end public getReturn($format = 'json') */
		
		/* Parse the data requested */
		private function parseData(){
		
			$return_array = array();
		

			// Get all links:
			if($this->input_array['input_checkbox_links'] == 'on'){
				$start_tag = "<a";
				$end_tag = "/a>";
				preg_match_all("($start_tag(.*)$end_tag)siU", $this->page_content, $dataObj);
				$return_array['d_links'] = $dataObj[0];
			}
			
			// Get all images:
			if($this->input_array['input_checkbox_images'] == 'on'){
				$start_tag = "<img";
				$end_tag = ">";
				preg_match_all("($start_tag(.*)$end_tag)siU", $this->page_content, $dataObj);
				$return_array['d_images'] = $dataObj[0];
			}
 
			return $return_array;
		
		}
		

	
} /* end hgmExercise() */


?>
