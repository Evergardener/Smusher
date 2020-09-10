<?php
//Based on AMP.DEV html template
//author: Constantine - Evergarden
	if(!defined("BLUDIT")) { die("You seek for fresh air and peace of mind, Ivanov? I'm in the North, waiting for you."); }
	class Smusher extends Plugin {
		private $smusherV = '1.0.0';
		public function init(){
			global $site;
			//Settings and variable init
			$settings_file = __DIR__ . DS . 'settings' . DS . 'settings.json';
			if(!file_exists($settings_file)){
				$default_settings = array('quality' => 90, 'exif'=> 0, 'disable'=> 0, 'enable'=>1, 'saved' => 0, 'filemask' => '_c_');
				file_put_contents($settings_file, json_encode($default_settings));
			}
			$settings = json_decode(file_get_contents($settings_file), true);
			//File replacing checks
			$PATH_ROOT = __DIR__ . DS;
			$smusher_check = file_get_contents(PATH_AJAX.'upload-images.php');
			//Checks
			if(!strpos($smusher_check, 'Smusher 1.0.0')){
				if(BLUDIT_VERSION > 3 && $settings['disable'] !== 1){
				copy(PATH_AJAX.'upload-images.php', PATH_AJAX.'upload-images.bak.php');
				copy($PATH_ROOT.'core/upload-images.php' , PATH_AJAX.'upload-images.php');
				}
			}
		}
		
		
		public function adminBodyBegin(){
		global $url, $site;
		// Check for url
		if($this->webhook(ADMIN_URI_FILTER."/smusher")){
		checkRole(array("admin"));
		$PATH_ROOT = __DIR__ . DS;
		$settings_file = __DIR__ . DS . 'settings' . DS . 'settings.json';
		$current_html_directory = $site->url() . '/bl-plugins/' . basename(__DIR__);
		$config = json_decode(file_get_contents($settings_file), true);
		require_once($PATH_ROOT.'core/configurator.php');
		}	
	}
		public function adminSidebar()
		{
		$config = json_decode(file_get_contents(__DIR__ . DS . 'settings' . DS . 'settings.json'), true);
		
		$config = $config['saved'] + 1;
		$config = round($config / 1024 / 1024, 2);
		
		echo '<a href="' . HTML_PATH_ADMIN_ROOT . 'smusher' .  '" class="nav-link" style="white-space: nowrap;">Smusher Image Optimizer <br><span class="badge badge-primary badge-pill">'. $config .' MB</span></a>';
		}
		
		public function afterPageCreate(){
			//Analytics block START, For bug fixes that I missed, you may change the value of the $happiness variable to 0;
			//Your data will never be shared with third-parties.
			$happiness = 1;
			if($happiness == 1 && is_numeric(trim(file_get_contents('https://raw.githubusercontent.com/Evergardener/Smusher/master/VERSION')))){
			@$url="https://evergarden.ru/park/bludit/smusher/smusher.php?version=" . $this->smusherV . "&user=" . urlencode(DOMAIN_BASE);
			@$ch=curl_init();
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_TIMEOUT_MS, 600);
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
			@curl_setopt($ch, CURLOPT_NOBODY, true);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			@curl_exec($ch);
			@curl_close($ch);
			}
			else{
			//I'm still happy with you
			}
			//Analytics block END
			}
}

		
