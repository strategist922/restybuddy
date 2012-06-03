<?php


class Common_Upload {

	public $maxSize = -1;

	public $supportMulti = false;

	public $allowExts = array();

	public $allowTypes = array();

	

	public $savePath = '';
	public $autoCheck = true;
	public $uploadReplace = false;

	// save rules
	// time uniqid com_create_guid
	public $saveRule = 'uniqid';

	public $hashType = 'md5_file';

	private $error = '';

	private $uploadFileInfo ;


	public function __construct($maxSize='',$allowExts='',$allowTypes='',$savePath='',$saveRule='') {
		if(!empty($maxSize) && is_numeric($maxSize)) {
			$this->maxSize = $maxSize;
		}
		if(!empty($allowExts)) {
			if(is_array($allowExts)) {
				$this->allowExts = array_map('strtolower',$allowExts);
			}else {
				$this->allowExts = explode(',',strtolower($allowExts));
			}
		}
		if(!empty($allowTypes)) {
			if(is_array($allowTypes)) {
				$this->allowTypes = array_map('strtolower',$allowTypes);
			}else {
				$this->allowTypes = explode(',',strtolower($allowTypes));
			}
		}
		if(!empty($saveRule)) {
			$this->saveRule = $saveRule;
		}else{
			$this->saveRule     =      array();
		}
		if(empty($savePath)) {
			$savePath = C('upload.savepath');
			if(empty($savePath)){
				throw new Exception("upload savePath is not define");
			}
		}
		$this->savePath = $savePath;
	}



	public function upload($savePath ='') {
		if(empty($savePath))
			$savePath = $this->savePath;
		if(!is_dir($savePath)) {
			if(is_dir(base64_decode($savePath))) {
				$savePath       =       base64_decode($savePath);
			}else{
				if(!mkdir($savePath)){
					$this->error  =  'upload dictionary '.$savePath.' no exist';
					return false;
				}
			}
		}else {
			if(!is_writeable($savePath)) {
				$this->error  =  'upload dictionary '.$savePath.' is readonly';
				return false;
			}
		}
		$fileInfo = array();
		$isUpload   = false;
		
		$files   =       $this->dealFiles($_FILES);
		foreach($files as $key => $file) {
			if(!empty($file['name'])) {
				$file['key']          =  $key;
				$file['extension']  = $this->getExt($file['name']);
				$file['savepath']   = $savePath;
				$file['savename']   = $this->getSaveName($file);

				if($this->autoCheck) {
					if(!$this->check($file))
						return false;
				}
				$updFile = $this->save($file);
				error_log("updfile:".$updFile);
				if(!$updFile) return false;

				unset($file['tmp_name'],$file['error']);
				$fileInfo[] = $file;
				//$isUpload   = true;
				$isUpload = $updFile;
			}
		}
		if($isUpload) {
			$this->uploadFileInfo = $fileInfo;
			return $isUpload;
		}else {
			$this->error  =  'no upload file';
			return false;
		}
	}

	public function getUploadFileInfo() {
		return $this->uploadFileInfo;
	}

	public function getErrorMsg() {
		return $this->error;
	}

	private function dealFiles($files) {
		$fileArray = array();
		$n = 0;
		foreach ($files as $file){
			if(is_array($file['name'])) {
				$keys = array_keys($file);
				$count =  count($file['name']);
				for ($i=0; $i<$count; $i++) {
					foreach ($keys as $key)
						$fileArray[$n][$key] = $file[$key][$i];
					$n++;
				}
			}else{
				$fileArray[$n] = $file;
				$n++;
			}
		}
		return $fileArray;
	}


	private function save($file) {
		$id = objid();

		if(in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png')))
		{
			$type = 'image';
		}else {
			$type = 'file';
		}
		
		$filename = $file['savepath'].'/'.$type.'/'.$id.'.'.$file['extension'];
		error_log("file:".$filename);

		if( in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png','swf')) && false === getimagesize($file['tmp_name'])) {
			$this->error = 'image error';
			return false;
		}
		
		if(!move_uploaded_file($file['tmp_name'], $filename)) {
			$this->error = 'file save error';
			return false;
		}
		
		$upyun = new Common_Upyun($type);
		$result = $upyun->putFile($filename,$id.'.'.$file['extension']);
		
		return $result ? C('upload.'.$type).'/'.$type.'/'.$id.'.'.$file['extension'] : false;
	}

	protected function error($errorNo) {
		switch($errorNo) {
			case 1:
				$this->error = 'unload size is so large';
				break;
			case 2:
				$this->error = 'so large file size than MAX_FILE_SIZE';
				break;
			case 3:
				$this->error = 'part of file upload,please retry';
				break;
			case 4:
				$this->error = 'no file upload';
				break;
			case 6:
				$this->error = 'not find temp dictionary';
				break;
			case 7:
				$this->error = 'write file error';
				break;
			default:
				$this->error = 'unknow upload error';
		}
		return ;
	}


	private function getSaveName($filename) {
		$rule = $this->saveRule;
		if(empty($rule)) {
			$saveName = $filename['name'];
		}else {
			if(function_exists($rule)) {
				$saveName = $rule().".".$filename['extension'];
			}else {
				$saveName = $rule.".".$filename['extension'];
			}
		}
		
		return $saveName;
	}


	private function check($file) {
		if($file['error']!== 0) {
			$this->error($file['error']);
			return false;
		}

		if(!$this->checkSize($file['size'])) {
			$this->error = 'file size error';
			return false;
		}

		if(!$this->checkType($file['type'])) {
			$this->error = 'file MIME type not allowed';
			return false;
		}


		if(!$this->checkExt($file['extension'])) {
			$this->error ='file extension is not allowed';
			return false;
		}

		if(!$this->checkUpload($file['tmp_name'])) {
			$this->error = 'File Error';
			return false;
		}
		return true;
	}

	private function autoCharset($fContents, $from='gbk', $to='utf-8') {
		$from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
		$to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
		if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
			return $fContents;
		}
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($fContents, $to, $from);
		} elseif (function_exists('iconv')) {
			return iconv($from, $to, $fContents);
		} else {
			return $fContents;
		}
	}

	private function checkType($type) {
		if(!empty($this->allowTypes))
			return in_array(strtolower($type),$this->allowTypes);
		return true;
	}


	private function checkExt($ext) {
		if(!empty($this->allowExts))
			return in_array(strtolower($ext),$this->allowExts,true);
		return true;
	}

	private function checkSize($size) {
		return !($size > $this->maxSize) || (-1 == $this->maxSize);
	}

	private function checkUpload($filename) {
		return is_uploaded_file($filename);
	}

	private function getExt($filename) {
		$pathinfo = pathinfo($filename);
		return $pathinfo['extension'];
	}



}