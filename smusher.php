<?php
		$filemask = $config['filemask'];
		if($config['exif'] == 0){
			$exif = 'false';
		} else {	$exif = 'true';	}
		//Sending file to resmush.it
	
	$file_smush = PATH_TMP.$filename;
    $mime_smush = mime_content_type($file_smush);
    $info_smush = pathinfo($file_smush);
    $name_smush = $info_smush['basename'];
    $output_smush = new CURLFile($file_smush, $mime_smush, $name_smush);
    $data_smush = array(
        "files" => $output_smush,
    );

    $ch_smush = curl_init();
    curl_setopt($ch_smush, CURLOPT_URL, 'http://api.resmush.it/?qlty=' . (int)$config['quality'] . '&exif=' . $exif);
    curl_setopt($ch_smush, CURLOPT_POST,1);
    curl_setopt($ch_smush, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_smush, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch_smush, CURLOPT_POSTFIELDS, $data_smush);
    $result_smush = json_decode(curl_exec($ch_smush), true);
    curl_close ($ch_smush);
	if(isset($result_smush['dest'])){
		$image_smush = file_get_contents($result_smush['dest']);
		$src_size = $result_smush['src_size'];
		$dest_size = $result_smush['dest_size'];
		unlink($file_smush);
		file_put_contents(PATH_TMP. $filemask . $filename , $image_smush);
		$filename = $filemask . $filename;
		if($dest_size < $src_size){
			$final_compression = $src_size - $dest_size;
			$config['saved'] = $config['saved'] + abs($final_compression);
			file_put_contents($smusher_directory . DS . 'settings' . DS . 'settings.json', json_encode($config));
			}
		
		}
	
	//Smush end