<?php

class UploadAudioFile{

	private $upload_dir;
	private $max_file_size;
	private $files_type;
	private $max_size;
	
	public function __construct(){
		//set 5MB file size
		$this->max_size = 5242880; 
	
		//common audio file type
		$ext_str = "acc,alac,ai,aif,caf,doc,iff,m3u,m4a,mid,midi,mp3,mpa,png,jpg,jpeg,ra,ram,wav,wma";
		$this->files_type = explode(",",$ext_str);

	}
	
	public function upload_file($upload_dir="", $file_name=""){
		if(!$_FILES[error])
			foreach($_FILES as $form_name=>$file_arr)
				$this->file = $file_arr;
		
		if($file_name)
			$this->name = $file_name;
		else
			$this->name = $this->file['name'];
		
		if($upload_dir){
			if(is_dir($upload_dir))
				$this->upload_dir = $upload_dir;
			else
				return "Invalid Directory!";
		}
		else
			$this->upload_dir = "./";
		
		
		$ext = substr($this->file['name'], strrpos($this->file['name'], '.') + 1);
		if (in_array($ext, $this->files_type) ) {
			if($this->file['size']<=$this->max_size){
			if (move_uploaded_file($this->file['tmp_name'],$this->upload_dir ."/". $this->name)) {
					return "true";
			}else {
					return "false";
			}}else{
				return "size";
			}
		}else{
			return "invalid";
		}
	}
}
?>
