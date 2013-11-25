<?php
	class amazonModel extends Model{
		
		// acl
		const AMAZON_S3_ACL_PUBLIC = 'public-read';
		
		public function getFileUrlFromS3($type, $key, $is_uniq = false) {
			
			$environment = Registry::get('environment');
			$bucket = Registry::get('amazon.s3.bucket');
			
			switch($type) {
				case 'video': 
								$s3_key = $environment.'/'.Registry::get('amazon.s3.dir.videos').'/'.$key;
					break;
			}
			
			if($is_uniq) {
				
			}
			
			return isset($s3_key) ? '//'.$bucket.'.'.Registry::get('amazon.s3.host').'/'.$s3_key : '';
			
		}
		
		public function putFileToS3($type, $file, $flDelete = false, $acl = self::AMAZON_S3_ACL_PUBLIC) {
			
			// AMAZON PHP LIB
			include_once(DOCROOT.'class/AmazonWebServices.phar');
			
			$success = false;
			
			$environment = Registry::get('environment');
			$bucket = Registry::get('amazon.s3.bucket');
			
			switch($type)
			{
				case 'video':
								$s3_key = $environment.'/'.Registry::get('amazon.s3.dir.videos').'/'.basename($file);
					break;
					
				default: 
								return $success;
										
			}
			
			if(isset($s3_key) && @is_file($file))	{
				
				// create client
				$clientS3 =  Aws\S3\S3Client::factory(array('key' => Registry::get('amazon.access_key_id'), 'secret' => Registry::get('amazon.secret_access')));
			
				// delete old S3 file
				$clientS3->deleteObject(array(
				    'Bucket'     	=> $bucket,
				    'Key'        	=> $s3_key
				));	
			
				// add new S3 file
				$clientS3->putObject(array(
				    'Bucket'     	=> $bucket,
				    'Key'        	=> $s3_key,
				    'SourceFile' 	=> $file,
				    'ACL'			=> $acl
				));	
				
				if($flDelete) {
					@unlink($file);
				}
				
				$success = true;
			}
			
			return $success;
			
		}
		
	}