<?php

/**
* 
*/
class N3Parser
{
		

	public function getChannelInfo($channelText){
		$delimiter = '#';
		$startTag = '###################################
		# Channel definition
		###################################';
		$endTag = '###################################
		# Events definition
		###################################';
		$regex = $delimiter . preg_quote($startTag, $delimiter)
		                    . '(.*?)'
		                    . preg_quote($endTag, $delimiter)
		                    . $delimiter
		                    . 's';
		preg_match($regex,$channelText,$matches);

		return $matches[0];
	}
	
}
?>