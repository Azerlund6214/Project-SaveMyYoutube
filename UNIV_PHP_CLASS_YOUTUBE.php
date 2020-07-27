<?php #261118 #271118 #281118

if (defined('PRINT_ECHO__UNIV_PHP_CLASSES'))
	echo "<br><font size=3 color=green> Объявлен класс </font> => <font color=blue>YOUTUBE = (UNIV_PHP_YOUTUBE).</font>";

	

# Записывать в переменную "UN_YOUTUBE"


### Сделать методы GET SET
### Класс не нацелен на обеспечение инкапсуляции полей и методов


class UNIV_PHP_YOUTUBE
{
	
	public $Class_Name = "UNIV_PHP_YOUTUBE";
	
	public $Get_Video_Info_URL_Pattern = "https://www.youtube.com/get_video_info?video_id="; # ОБЯЗАТЕЛЬНО протокол
	
	# Много дублирующихся данных в пееменых(Ужать все в 1 переменную ПОТОМ(когда все будет отлажено))
	public $Full_Target_Video_URL = "";
	public $Target_Video_URL = "";
	public $Video_ID = "";
	
	public $Video_Info = "";
	public $Video_Info_Asoc = Array();
	public $Video_Status_Is_OK = "Default"; # Бесполезное опле, чисто для удобства дебага
	
	public $Player_Response_JSON_Full; # Кусок из Video_Info (копия)
	public $Player_Response_JSON_Erased; #  ВЫРЕЗАТЬ!!!  # Кусок из всей инфы(еще копия)
	
	
	public $FIN_Video_Info_Asoc = array(); # 
	public $FIN_Video_Thimbnails_Url_Arr = array(); # Просто ссылки
	public $FIN_Video_Itag_Info_Asoc_FULL = array(); # [itag][ключи]
	public $FIN_Video_Itag_Info_Asoc_Video_MP4 = array(); # ТОЛЬКО MP$
	public $FIN_Video_Itag_Info_Asoc_Audio = array(); # ТОЛЬКО AUDIO
	
	
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
									"22",  # 720p = MP4 1280 x 720                  нет веса видео
									"37",  # MP4 1920 x 1080
									"38",  # MP4 4096 x 1714
									
								#	"43",  # 360p = WEBM 640 x 360
								#	"44",  # WEBM 854 x 480
								#	"45",  # WEBM 1280 x 720
									
									"140"  # 140 = аудио (только один итаг)
								);
	

	
	
	#############################################################
	
	function __construct()
	{
		if (defined('PRINT_ECHO__UNIV_PHP_CLASSES_CONSTRUCT'))
			echo "<br> Создан класс ".$this->Class_Name; 
	}
	
	function __destruct() 
	{	
		if (defined('PRINT_ECHO__UNIV_PHP_CLASSES_DESTRUCT'))
			echo "<br> Уничтожается класс ".$this->Class_Name ;
	}
	
	#############################################################
	
	# Тут используется define(показать мои комменты для ошибок)
	# Тут Вывод текста ошибки и EXIT
	function ERROR_HANDLER( $ERR_NAME , $Description_My = "Default" )
	{
		
		# Мне для дебага
		if( defined("SHOW_MY_ERROR_HANDLER_MSG") )
		{
			echo "<hr color=yellow>";		
			echo "<br>$ERR_NAME";
			echo "<br>$Description_My";
		}
		
		switch( $ERR_NAME )
		{
			case "NOT_SOLVED_VIDEO_ID":
						
						# Не удалось извлечь Video_Id из предоставленной ссылки
						# Сообщения брать из define
						
						break;
			
			case "VIDEO_TYPE__LIVESTREAM":
			
						break;
						
			case "VIDEO_TYPE__NOT_EXIST":
			
						break;
						
			case "VIDEO_TYPE__FORBIDDEN_TO_WATCH":
			
						break;	
						
			case "VIDEO_TYPE__DELETED":
						break;		
						
			case "VIDEO_TYPE__NO_ACCESS":
						break;	
						
			case "PARSE_STR__MORE_THAN_1000_VARS":
						break;	
						
			case "2":
						break;						
						
			default:
					echo "<br>ERROR_HANDLER: switch default => $ERR_NAME => $Description_My";
					break;
		
		}
		
		# Мне для дебага
		if( defined("SHOW_MY_ERROR_HANDLER_MSG") )
		{
			echo "<hr color=yellow>";
			echo "EXIT";
		}
		
		exit;
		
	}
	
	
	#############################################################
	
	function Set_URL( $URL )
	{
		$this -> Full_Target_Video_URL = $URL;
	}
	
	# Уборка ВСЕХ пробелов (и с боков)
	function Prepare_URL( )
	{
		# Убрать ВСЕ пробелы
		$this -> Target_Video_URL = preg_replace( '/ {1,}/'  ,  ''  , $this->Full_Target_Video_URL ) ; # Робит
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
	
	
	function Get_Playlist_ID( )
	{ // Копипаст из нижнего    list=123
	}
	
	# Работает
	# Запись в поле класса ИЛИ echo ERROR
	# Сейчас есть ТОЛЬКО через регулярки
	function Get_Video_ID( )
	{
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
		
		#9из12
		# Сам переделывал
		$Pattern = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v/)[^&\n]+|(?<=embed/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#";

		preg_match( $Pattern , $this->Target_Video_URL , $Buf_Video_ID );
		
		
		#echo "<br>1111111111=".count($Buf_Video_ID);
		#echo "<br>1111111111=".$Buf_Video_ID[0];
		
		
		
		if( count($Buf_Video_ID) === 1 )
		{
			$this->Video_ID = $Buf_Video_ID[0];
			return;
		}
		else
		{
			$this->ERROR_HANDLER( "NOT_SOLVED_VIDEO_ID" , "Get_Video_ID:Нашел 0 или 2+ VIDEO_ID= ". count($Buf_Video_ID) ." => ".$this->Target_Video_URL );
		}
		
		
		# $this->Target_Video_URL
		# $this->Video_ID
		
		#query
		
		#parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		#echo $my_array_of_vars['v'];  
		
	}
	
	
	
	
	
	#############################################################
	
	
	function Get_Video_Info(  )
	{
	
		$Responce_url = $this->Get_Video_Info_URL_Pattern . $this->Video_ID; # ОБЯЗАТЕЛЬНО протокол
		
		$this -> Video_Info = file_get_contents( $Responce_url );
		# Написать проверку = Что если не получилось(например нет интернета или 403(забанили))
	}
	
	#############################################################

	function Decode_Video_Info(  )
	{		
		
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		
	}
	
	
	#############################################################
	
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
		
		# Сделать отлов ключевого слова в JS и тогда из JS выводить текст о неверной ссылке
		# $this->Video_Type
		
		
		# NOTICE: Такие муторные if, чтобы не приходилось прямо тут парсить JSON(в нем есть уникальные коды ошибок и типов видео)
		
		# Работает
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
		
		
		
		# Именно после всех других if  (т к в некоторых тоже может стоять "ok")
		if( $this->Video_Info_Asoc['status'] === "ok" )
		{

			$this->Video_Status_Is_OK = "Video Status Is OK";
			return;
		}
		
		
		$this->ERROR_HANDLER( "VIDEO_TYPE__SOMETHING_NEW" , "Check_Video_Response_Status: Ни один if не сработал(Сорее всего это новый тип статуса(надо дописать))");
		
		
	}
	
	
	
	
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
	
	
	#############################################################
	
	
	
		
	function Player_Response_Convert_JSON_To_Asoc()
	{
		$this->Player_Response_JSON_Full = json_decode($this->Video_Info_Asoc['player_response'], true);
	}
	
	
	#############################################################
	
	

	
	#############################################################
	
	
	#  ВЫРЕЗАТЬ!!!
	# Убирает лишние ключи
	function Player_Response_JSON_Erase()
	{
		# Копипаст массива и обрезка копии(Для удобства отладки)
		$this->Player_Response_JSON_Erased = $this->Player_Response_JSON_Full;
		
		unset( $this->Player_Response_JSON_Erased['playbackTracking'] );
		unset( $this->Player_Response_JSON_Erased['videoDetails']['keywords'] );
		unset( $this->Player_Response_JSON_Erased['playerConfig'] );
		unset( $this->Player_Response_JSON_Erased['storyboards'] );
		unset( $this->Player_Response_JSON_Erased['attestation'] );
		unset( $this->Player_Response_JSON_Erased['messages'] );
		unset( $this->Player_Response_JSON_Erased['adSafetyReason'] );
		
	}

	
		
	#############################################################
	
	# Решил не проверять ибо все ошибки отловлены в Check_Video_Response_Status
	function Check_Playability_Status()
	{
		
		### Тут условия и если косяк, то вывод текста ошибки и exit
		
		$this->Playability_Status = $this->Player_Response_JSON['playabilityStatus']['status'];
		
		if( $this->Playability_Status != "OK" )
		{
			echo "<br>  Playability_Status != OK => exit : " . $this->Playability_Status;
			exit;
		}
		
		# "OK"
	
	}
	
	
	#############################################################
	
	
	function Fill_FIN_Video_Info( )
	{
		$this->FIN_Video_Info_Asoc['videoId']       = $this->Player_Response_JSON_Full['videoDetails']['videoId'];
		$this->FIN_Video_Info_Asoc['title']         = $this->Player_Response_JSON_Full['videoDetails']['title'];
		$this->FIN_Video_Info_Asoc['lengthSeconds'] = $this->Player_Response_JSON_Full['videoDetails']['lengthSeconds'];
		
		$this->FIN_Video_Info_Asoc['viewCount']     = $this->Player_Response_JSON_Full['videoDetails']['viewCount'];
		$this->FIN_Video_Info_Asoc['author']        = $this->Player_Response_JSON_Full['videoDetails']['author'];
		#$this->FIN_Video_Info_Asoc[''] = $this->Player_Response_JSON_Full['videoDetails'][''];
		#$this->FIN_Video_Info_Asoc[''] = $this->Player_Response_JSON_Full['videoDetails'][''];
		#$this->FIN_Video_Info_Asoc[''] = $this->Player_Response_JSON_Full[''][''];
		#$this->FIN_Video_Info_Asoc[''] = $this->Player_Response_JSON_Full[''][''];

	}
	
	
	function Fill_FIN_Video_Thimbnails_Url_Arr()
	{
		# Сделать черех форич
		$this->FIN_Video_Thimbnails_Url_Arr[] = $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'][0]['url'];
		$this->FIN_Video_Thimbnails_Url_Arr[] = $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'][1]['url'];
		$this->FIN_Video_Thimbnails_Url_Arr[] = $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'][2]['url'];
		$this->FIN_Video_Thimbnails_Url_Arr[] = $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'][3]['url'];
	
	}
	
	function Fill_FIN_Video_Itag_Info_Asoc_FULL()
	{
		# $Arr[24][Значение]
		foreach( $this->Player_Response_JSON_Full['streamingData']['formats'] as $One_Set )
		{
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['itag']          = @$One_Set['itag'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['url']           = @$One_Set['url'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['mimeType']      = @explode(";",@$One_Set['mimeType'])[0]; #Отбрасываем кодеки
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['width']         = @$One_Set['width'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['height']        = @$One_Set['height'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['contentLength'] = @$One_Set['contentLength'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['quality']       = @$One_Set['quality'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['qualityLabel']  = @$One_Set['qualityLabel'];
		}
		
		foreach( $this->Player_Response_JSON_Full['streamingData']['adaptiveFormats'] as $One_Set )
		{
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['itag']          = @$One_Set['itag'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['url']           = @$One_Set['url'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['mimeType']      = @explode(";",@$One_Set['mimeType'])[0]; #Отбрасываем кодеки
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['width']         = @$One_Set['width'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['height']        = @$One_Set['height'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['contentLength'] = @$One_Set['contentLength'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['quality']       = @$One_Set['quality'];
			$this->FIN_Video_Itag_Info_Asoc_FULL[ $One_Set['itag'] ] ['qualityLabel']  = @$One_Set['qualityLabel'];

		}
	
	}
	
	#############################################################
	
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
	

	
	
	#############################################################
	
	function Echo_Table( )
	{
	
		echo "<table border=2px>
					<thead>
						<tr >
							<td><strong>ITAG</strong></td>
							<td><strong>mimeType</strong></td>
							<td><strong>qualityLabel</strong></td>
							<td><strong>Размер</strong></td>
							<td><strong>contentLength</strong></td>
							<td><strong>URL</strong></td>
						</tr>
					</thead>
					<tbody>
					";

						
						
		#foreach($this->FIN_Video_Itag_Info_Asoc_Audio as $One_Set)
		foreach($this->FIN_Video_Itag_Info_Asoc_Video_MP4 as $One_Set)
		{
			echo "<tr>";
			
			echo 	"<td>". $One_Set['itag'] ."</td>";
			echo 	"<td>". $One_Set['mimeType'] ."</td>";
			echo 	"<td>". $One_Set['qualityLabel'] ."</td>";
			echo 	"<td>". $One_Set['width'] . "x" . $One_Set['height'] ."</td>";
			echo 	"<td>". (int)($One_Set['contentLength']/1024/1024) ."Мб</td>";
			echo 	"<td>". 
							#"<a download=\"". $One_Set['itag'] ."\" target=\"_blank\" href=\"". $One_Set['url'] ."\"> 	<span> Скачать </span>	</a>"
							"<a 
								download   
								onclick=\"return confirm('Скачать?');\"
								href=\"". $One_Set['url'] ."\"
								>
								
								<span> Скачать </span>	
							</a>"
					."</td>";


			//echo 	"<td>".  ."</td>";
			
			echo "</tr>";
		}
		
		echo 	"</tbody>";
		echo "</table>";
	
	
	
	
	
	}
	
	
	#############################################################
	
	
	#############################################################
	
	
	#############################################################
	
	
	#############################################################

	function Echo_Some_Chars_Video_Info( $Count=100 , $Begin_Pos=0 )
	{
		echo "<br>".substr( $this->Video_Info , $Begin_Pos , $Count);
	}
	
	#############################################################
	
	function Save_Video_Info_In_File( $Path_And_Name )
	{
	
		file_put_contents( $Path_And_Name , $this->Video_Info );
	
	}	
	
	
	#############################################################



	#############################################################
	
	
	/*
	function Save_Stream_Map_In_File( $Path_And_Name )
	{
		file_put_contents( $Path_And_Name , $this->Stream_Map );
	}
	*/
	
	#############################################################
	
	
	
	#############################################################
	
	
	
	#############################################################
	
	
	
	#############################################################
	
	
	
	#############################################################
	
	
	
	
	
	
}#END CLASS

		
		
?>