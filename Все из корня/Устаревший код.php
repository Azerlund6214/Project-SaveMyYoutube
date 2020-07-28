<?php



	# Трансляция=проверить когда кончится   https://www.youtube.com/watch?v=0whQO3r0wwA

	
	#https://www.youtube.com/channel/UCUIaaPF0KlrED0DfQYwyJYA
	#https://www.youtube.com/playlist?list=PLms4zBSLSqcIhLKJfKILSGAr-oV1C_pb4
	
	#Удаленное   https://www.youtube.com/watch?v=w4zVncsZ1Vo&list=PL7s-aH85QwI8PcutmFgFMZNGSWeWiFiR2&index=22
	#Огранич дост https://www.youtube.com/watch?v=z9rsm6zPcXw&list=PLy8tICjJjlRjUMSRo243O4lA_gHa3MJkO&index=6
	
	
	#livestream = 1 у стрима   у видео параметра нет
	
	#status = UNPLAYABLE  хз откуда взял    только в player_response
	#status = ok
	#status = fail   у удаленного  player_response[status]=ERROR   PR[reason]=Видео удалено пользователем, который его добавил.
	#status = fail   у платного  player_response[status]=UNPLAYABLE   PR[reason]=Это видео недоступно.
	#status = fail   у огранич  player_response[status]=ERROR   PR[reason]=Это видео недоступно.
	#status = fail  У несуществующего  + [errorcode] => 2
	
	#pltype = contentlive  у стрима     есть в player_response
	#pltype = contentugc   у видоса     есть в player_response
	#pltype - У  удал и огранич  его нет




	/*
	function Fill_FIN_Video_Itag_Info_Asoc_VIDEO_MP4( )
	{
		foreach($this->FIN_Video_Itag_Info_Asoc_FULL as $Key => $Val)
		{
			if( $this->FIN_Video_Itag_Info_Asoc_FULL[$Key]['mimeType'] === "video/mp4" )
			{	
				$this->FIN_Video_Itag_Info_Asoc_Video_MP4[$Key] = $Val;			
			}	
		}
	}
	
	
	function Fill_FIN_Video_Itag_Info_Asoc_Audio()
	{
		foreach($this->FIN_Video_Itag_Info_Asoc_FULL as $Key => $Val)
		{
			if( $this->FIN_Video_Itag_Info_Asoc_FULL[$Key]['mimeType'] === "audio/mp4" )
			{	
				$this->FIN_Video_Itag_Info_Asoc_Audio[$Key] = $Val;			
			}	
		}
	}
	*/
	/*
	$YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_Video_MP4(  );
	if( isset($_POST["cb12_Echo_FIN_Video_Itag_Info_Asoc_Video_MP4"]) )
		SF::PRINTER($YOUTUBE->FIN_Video_Itag_Info_Asoc_Video_MP4 ,"print_r", "FIN_Video_Itag_Info_Asoc_Video_MP4 = ВСЯ Инфа о форматах видео(Только MP4)");
	
	$YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_Audio(  );
	if( isset($_POST["cb13_Echo_FIN_Video_Itag_Info_Asoc_Audio"]) )
		SF::PRINTER($YOUTUBE->FIN_Video_Itag_Info_Asoc_Audio,"print_r" , "FIN_Video_Itag_Info_Asoc_Audio = ВСЯ Инфа о форматах AUDIO");
	*/






		public $Valid_Itags = array(
								#	"5",   # FLV 320 x 240
								#	"34",  # FLV 640 x 360
								#	"35",  # FLV 854 x 480
									
									
									"160", # 160p = MP4  256 x 144 # Добавлял сам = есть вес видео
									"133", # 240p = MP4  426 x 240 # Добавлял сам = есть вес видео
									"18",  # 360p = MP4  640 x 360                  есть вес видео
									"134", # 360p = MP4  640 x 360 # Добавлял сам = есть вес видео
									"135", # 480p = MP4  480 x 854 # Добавлял сам
									"136", # 720p = MP4 1280 x 720 # Добавлял сам = есть вес видео
									"22",  # 720p = MP4 1280 x 720    багует размер файла(0мб)              нет веса видео
									"37",  # MP4 1920 x 1080
									"38",  # MP4 4096 x 1714
									
								#	"43",  # 360p = WEBM 640 x 360
								#	"44",  # WEBM 854 x 480
								#	"45",  # WEBM 1280 x 720
									
									"140"  # 140 = аудио (только один итаг)
								);



								
								
								
	############# Очень кривое, убрал все комменты
	# Работает
	# Запись в поле класса ИЛИ echo ERROR
	# Сейчас есть ТОЛЬКО через регулярки
	function Get_Video_ID( $URL )
	{
		# Убрать ВСЕ пробелы
		$URL = preg_replace( '/ {1,}/'  ,  ''  , $URL ) ; # Робит
		
		
		
		
		/*
		РАБОТАЕТ ДЛЯ:
		https://www.youtube.com/v/VIDEOID
		https://www.youtube.com/v/VIDEOID?fs=1&hl=en_US
		https://www.youtube.com/v/VIDEOID?feature=autoshare&version=3&autohide=1&autoplay=1
		https://www.youtube.com/?v=VIDEOID
		https://www.youtube.com/watch?v=VIDEOID
		https://www.youtube.com/watch?v=VIDEOID&feature=featured
		https://www.youtu.be/VIDEOID
		https://www.youtube.com/embed/VIDEOID
		https://www.youtube.com/ytscreeningroom?v=VIDEOID
		
		НЕ РАБОТАЕТ ДЛЯ:  (Этот формат ссылок устарел => Ютуб делает редирект на главную)
		https://www.youtube.com/vi/VIDEOID
		https://www.youtube.com/?vi=VIDEOID
		https://www.youtube.com/watch?vi=VIDEOID
		*/
		
		# Сам переделывал
		$Pattern = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v/)[^&\n]+|(?<=embed/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#";
		
		//Тут косяк - прокатывает даже вариант с тупо "v=VIDEOID"
		//Лучше сделать много последовательных проверок
		
		preg_match( $Pattern , $URL , $Buf_Video_ID );
		
		
		#echo "<br>1111111111=".count($Buf_Video_ID);
		#echo "<br>1111111111=".$Buf_Video_ID[0];
		
		
		
		if( count($Buf_Video_ID) === 0 )
		{
			$this->ERROR_HANDLER( "NOT_SOLVED_VIDEO_ID" , "Get_Video_ID: Не нашел ни одного VIDEO_ID= ". count($Buf_Video_ID) ." => ".$URL );
		}
		
		
		$this->Video_ID = $Buf_Video_ID[0];
		
		return;
		
		
		
		#parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		#echo $my_array_of_vars['v'];  
		
	}
	
	
	
	# УБРАТЬ СОВСЕМ
	# Доделать регулярки
	# Проверка на strstr(youtube.com/)  ,  один ?   ,  одно =
	function Verify_URL( )
	{
		# Формат ТОЛЬКО  http://www.youtube.com/watch?v=JYATEN_TzhA
		# Обязательно .../watch? ... & v=123 & ...
	
	
		# Проверка на очень длинную строку(вдруг специально вбросят)
		
		# Проверить strstr(youtube.com/)
		
		
		
		
		
		
		//$arr = array();
		
		#$reg1 = "^.*((youtu.be"  .  "\\/)"  .  "|(v\\/)|(\\/u\\/w\\/)|(embed\\/)|(watch\\?))\\??v?=?([^#\\&\\?]*).*";
		#$reg1 = "^.*((youtu.be"  .  "\/)"  .  "|(v\/)|(\/u\/w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*";
		
		#$reg1= "(?:youtube(?:-nocookie)?\\.com\\/(?:[^\\/\\n\\s]+\\/\\S+\\/|(?:v|e(?:mbed)?)\\/|\\S*?[?&]v=)|youtu\\.be\\/)([a-zA-Z0-9_-]{11})";
		#$reg1 = "/(youtu\.be\/|youtube\.com\/(watch\?(.*&)?v=|(embed|v)\/))([^\?&\"'>]+)/";
		
		#прошел $reg1 = "/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})\W/";
		
		//ошибка $reg1 = "/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*)";
		
		/* ошибка $reg1 = "v=([a-zA-Z0-9\_\-]+)&?")[1]"; */
		/* ошибка $reg1 = "list=([a-zA-Z0-9\-\_]+)&?"; */
		

		///* 
		
		echo $this->Target_Video_URL."<br>";
		

		
		
		#echo $reg1."<br>";
		
		
		#echo preg_quote($reg1, '/') ;
		#$reg1 = preg_quote($reg1, '/') ;
		
		
		
		#exit;
		
		$a = preg_match("", $this->Target_Video_URL , $arr );
		
		#$arr = preg_split ( $reg1 , $this->Target_Video_URL  );
		#$a = preg_match( $reg1 , $this->Target_Video_URL , $arr  );
		
		echo "<pre>";
		print_r($a);
		print_r($arr);
		echo "</pre>";
		
		// */
		
		exit;
		
		
		
		

		#$this->Target_Video_URL
		
		
		
		$URL_Parametres_ALL = explode( "?" , $user_request_url ); # [0]=не нужное  [1]=ВСЕ параметры ссылки
	
	
		# Проверка есть ли ? и сколько их (ифы внизу)
		if( count($URL_Parametres_ALL) === 1 )
		{
			echo "<br>Нецелевой формат URL (Нет ? => нет параметров) = $user_request_url";
			exit;
		}
		
		# Проверка есть ли = и сколько их
		if( count($URL_Parametres_ALL) <= 1 )
		{
			echo "<br>Непровильный формат URL (Несколько ?) = $user_request_url";
			exit;
		}
		
		
		
		
		
		
		# Делаем хттп запрос 200
	
	}


### РАЗОБРАТЬСЯ !!!!!!
	function Convert_Video_Info_To_Asoc()
	{
		
		set_error_handler(
							function ($severity, $message, $file, $line) 
							{				
								$this->ERROR_HANDLER( "PARSE_STR__MORE_THAN_1000_VARS" , "Convert_Video_Info_To_Asoc: Ошибка=Больше 1000 переменных в parse_str");	
								#throw new \ErrorException($message, $severity, $severity, $file, $line);	
							}
						 );
		/*
			Все упадет если сткрока с JSON инфой окажется на 1000+ позиции
			
			Warning: parse_str(): Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini
			max_input_vars
			
			http://php.net/manual/ru/info.configuration.php
			Если входных переменных больше, чем задано директивой, выбрасывается предупреждение E_WARNING, а все последующие переменные в запросе игнорируются.
		*/
		# Тут может выскачить Warning => Тогда дальнейшая логика проги работать не будет(50на50,как повезет)
		parse_str( $this->Video_Info , $this->Video_Info_Asoc );
		
		echo count($this->Video_Info_Asoc);
		
		restore_error_handler();

		
		return;
		
		# Попытка разбить массив на 2 части => не прокатило
		$Buf = array_chunk($this->Video_Info, 2, TRUE);
		
		$Buf_Asoc_1 = array();
		$Buf_Asoc_2 = array();
		
		parse_str( $Buf[0] , $Buf_Asoc_1 );
		parse_str( $Buf[1] , $Buf_Asoc_2 );
		
		
		$this->Video_Info_Asoc = $Buf_Asoc_1;
		$this->Video_Info_Asoc += $Buf_Asoc_2;
		
		# unset $this->Video_Info
		
		# Проверка   что если элементов больше 1000 (будет warning)
	}


	
	function Check_Video_Response_Status()
	{
		
		# $this->Video_Type
		
		
		
		# Именно после всех других if  (т к в некоторых тоже может стоять "ok")
		if( $this->Video_Info_Asoc['status'] != "ok" )
		{
			
			exit("Статус не ок");
		}
		
		$this->Video_Status_Is_OK = "Video Status Is OK";
		
		
		
		# NOTICE: Такие муторные if, чтобы не приходилось прямо тут парсить JSON(в нем есть уникальные коды ошибок и типов видео)
		
		# НЕ Работает
		if( @$this->Video_Info_Asoc['livestream'] === "1" )
		{		
			$this->ERROR_HANDLER( "VIDEO_TYPE__LIVESTREAM" , "Check_Video_Response_Status: Ссылка ведет на прямую трнсляцию");	
		}
		
		# Работает
		# Можно оставить только последенее условие
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && strstr(@$this->Video_Info_Asoc['reason'] , "По требованию владельца это видео не воспроизводится на других сайтах."))
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__FORBIDDEN_TO_WATCH" , "Check_Video_Response_Status: Можно смотреть только на Youtube");
			# В player_response есть рабочая ссылка(походу можно обойти запрет)
		}
		
		# Работает
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "2" )
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__NOT_EXIST" , "Check_Video_Response_Status: Видео не существует(Неверная ссылка)");
		}
		
		# Работает
		# Можно оставить только последенее условие
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && @$this->Video_Info_Asoc['reason'] === "Видео удалено пользователем, который его добавил.")
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__DELETED" , "Check_Video_Response_Status: Видео было удалено");
		}
		
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && @$this->Video_Info_Asoc['reason'] === "Это видео недоступно.")
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__NO_ACCESS" , "Check_Video_Response_Status: Видео с ограниченным доступом");
		}
		
		
		

		
		
		$this->ERROR_HANDLER( "VIDEO_TYPE__SOMETHING_NEW" , "Check_Video_Response_Status: Ни один if не сработал(Сорее всего это новый тип статуса(надо дописать))");
		
		
	}
	
	
	









?>