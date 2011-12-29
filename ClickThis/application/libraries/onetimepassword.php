<?php
class OneTimePassword {
	
	function TOPT() {
		// Nothing
	}
	
	public static function GetPassword($Settings){
		//Get some settings from array
		
		$hash_hmac_algo=$Settings['Algorithm'];
		$hash_hmac_data_chr=0+$Settings['Timestamp'];;
		$hash_hmac_key=$Settings['Key'];
		$digits=0+$Settings['Digits'];
		//Code
		$current_unix_time=$hash_hmac_data_chr;
		$t_0=0+$Settings['InitialTime'];
		$time_step=0+$Settings['TimeStep'];
		$time_step_window=0+$Settings['TimeWindowSize'];
		$t=self::generate_t_value($current_unix_time,$t_0,$time_step);
		$t=str_pad(dechex($t),2,"0",STR_PAD_LEFT);
		while(strlen($t)<16)
		{
		$t="0".$t;
		}
		$hash_hmac_data_chr=hexdec($t);
		
		$hash_hmac_data=self::generate_hash_hmac_data($hash_hmac_data_chr);
		$hash_hmac=self::generate_hash_hmac($hash_hmac_algo,$hash_hmac_data,$hash_hmac_key);
		$truncated_data=self::generate_truncated_value($hash_hmac,$hash_hmac_algo);
		return self::generate_HOTP_TOTP_value($truncated_data,$digits);
		
	}
	
	private static function generate_hash_hmac_data($data){
		if(isset($data)){
			$data_temp="";
			$data_temp=str_pad(dechex($data),2,"0",STR_PAD_LEFT);
			$data="";
			while(strlen($data_temp)<16){
				$data_temp="0".$data_temp;
			}
			for($counter=0;$counter<(strlen($data_temp)/2);$counter=$counter+1){
				$data=$data.chr(hexdec(substr($data_temp,($counter*2),2)));
			}
		}
		return $data;
	}
	
	
	
	private static function generate_t_value($current_unix_time,$t_0,$time_step){
		if(isset($current_unix_time)&&isset($t_0)&&isset($time_step)){
			$t=floor(($current_unix_time-$t_0)/$time_step);
			$data=$t;
		}
		return $data;
	}
	
	
	
	private static function generate_hash_hmac($hash_hmac_algo,$hash_hmac_data,$hash_hmac_key){
		$data="";
		if(($hash_hmac_algo=="sha1")||($hash_hmac_algo=="sha256")||($hash_hmac_algo=="sha512")){
			if(($hash_hmac_data!="")&&($hash_hmac_key!="")){
				$data=hash_hmac($hash_hmac_algo,$hash_hmac_data,$hash_hmac_key,TRUE);
			}
		}
		return $data;
	}
	
	
	
	private static function generate_truncated_value($data,$hash_hmac_algo){
		if($data!=""){
			switch($hash_hmac_algo)
			{
				case"sha1":$counter_algo=19;break;
				case"sha256":$counter_algo=31;break;
				case"sha512":$counter_algo=63;break;
				default:$counter_algo=19;break;
			}
		
			$truncate_offset_bits="";
			$truncate_offset_bits=decbin(ord($data[$counter_algo]));
			
			while(strlen($truncate_offset_bits)<8){
				$truncate_offset_bits="0".$truncate_offset_bits;
			}
			
			$truncate_offset_bits_temp="";
			for($counter=4;$counter<8;$counter=$counter+1){
				$truncate_offset_bits_temp=$truncate_offset_bits_temp.$truncate_offset_bits[$counter];
			}
			$truncate_offset_bits=bindec($truncate_offset_bits_temp);
			$counter=0;
			$truncate_offset_data_temp="";
			for($counter=$truncate_offset_bits;$counter<($truncate_offset_bits+4);$counter=$counter+1){
				$truncate_offset_data_temp=$truncate_offset_data_temp.str_pad(dechex(ord($data[$counter])),2,"0",STR_PAD_LEFT);
			}
			$truncate_offset_data=decbin(hexdec($truncate_offset_data_temp));
		
			while(strlen($truncate_offset_data)<32){
				$truncate_offset_data="0".$truncate_offset_data;
			}
			
			$counter=0;
			$truncate_offset_data_temp="";
			while($counter<32){
				if($counter>0){
					$truncate_offset_data_temp=$truncate_offset_data_temp.$truncate_offset_data[$counter];
				}
				$counter=$counter+1;
			}
			$data=bindec($truncate_offset_data_temp);
		}
		return $data;
	}
	
	
	private static function generate_HOTP_TOTP_value($data,$digits){
		if(is_int($digits)&&($data!="")){
			if($digits>5){
				$divided_by=pow(10,$digits);
				$data=$data%$divided_by;
				while(strlen($data)<$digits){
					$data="0".$data;
				}
			}
		}
		return $data;
	}
}
?>