<?php #261118 #271118 #281118

	

# Записывать в переменную "UN_YOUTUBE"



### Класс не нацелен на обеспечение инкапсуляции полей и методов

# При введении ссылки на закончившуюся прямую трансляцию он её не отловит и будет ошибка



class UNIV_PHP_YOUTUBE
{
		
	public $Get_Video_Info_URL_Pattern = "https://www.youtube.com/get_video_info?video_id="; # ОБЯЗАТЕЛЬНО протокол
	
	# Много дублирующихся данных в пееменых(Ужать все в 1 переменную ПОТОМ(когда все будет отлажено))
	public $Video_ID = "";
	
	public $Video_Info = "";
	public $Video_Info_Asoc = Array();
	public $Video_Status_Is_OK = "Default"; # Бесполезное опле, чисто для удобства дебага
	
	public $Player_Response_JSON_Full; # Кусок из Video_Info (копия)
	
	
	public $FIN_Video_Info_Asoc = array(); # 
	public $FIN_Video_Thimbnails_Arr = array(); # Ссылки и размеры
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
									"22",  # 720p = MP4 1280 x 720    багует размер файла(0мб)              нет веса видео
									"37",  # MP4 1920 x 1080
									"38",  # MP4 4096 x 1714
									
								#	"43",  # 360p = WEBM 640 x 360
								#	"44",  # WEBM 854 x 480
								#	"45",  # WEBM 1280 x 720
									
									"140"  # 140 = аудио (только один итаг)
								);
	

	
	
	#############################################################
	
	function __construct()	{  }	
	function __destruct() 	{  }
	
	#############################################################
	
	# Тут используется define(показать мои комменты для ошибок)
	# Тут Вывод текста ошибки и EXIT
	function ERROR_HANDLER( $ERR_NAME , $Description_My = "Default" )
	{
		
		# Мне для дебага
		//if( defined("SHOW_MY_ERROR_HANDLER_MSG") )
		//{
			echo "<hr color=yellow>";		
			echo "<br>$ERR_NAME";
			echo "<br>$Description_My";
		//}
		
		
		// Сделать это асоц массивом
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
		
		exit("ERROR handler");
		
	}
	
	
	#############################################################
	

	

	
	

	
	
	function Get_Playlist_ID( )
	{ // Копипаст из нижнего    list=123
	}
	
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
		// Декодируем дважды, БЕЗ буферной переменной ибо файл огромный
		
		//echo count($this->Video_Info)."<br>";
		
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		
		
		
		
		/* Convert_Video_Info_To_Asoc()
			Все упадет если сткрока с JSON инфой окажется на 1000+ позиции
			
			Warning: parse_str(): Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini
			max_input_vars
			
			http://php.net/manual/ru/info.configuration.php
			Если входных переменных больше, чем задано директивой, выбрасывается предупреждение E_WARNING, а все последующие переменные в запросе игнорируются.
		*/
		
		# Тут может выскачить Warning => Тогда дальнейшая логика проги работать не будет(50на50,как повезет)
		
		parse_str( $this->Video_Info , $this->Video_Info_Asoc );
		
		
		
	}
	
	
	#############################################################
	

	
	
	

	
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
		
		$buf_only_player_response = $this->Video_Info_Asoc['player_response'];
		
		//echo $buf_only_player_response;
		//exit;
		
		# Если приходит JSON со сломанной структурой, то его не удастся распарсить.
		$this->Player_Response_JSON_Full = json_decode($buf_only_player_response , true);
		
		if ( $this->Player_Response_JSON_Full  == "" )
		{
			echo "Youtube прислал поломанный JSON, который нельзя разобрать. Скорее всего это видео удалено или с ограниченным доступом.";
			
			//Полный кусок = "playabilityStatus":{"status":"LOGIN_REQUIRED","messages":["Это видео с ограниченным доступом. Войдите в аккаунт."],"errorScreen":{"playerErrorMessageRenderer":{"subreason":{"simpleText":"Чтобы посмотреть это видео, войдите в аккаунт"},"reason":{"simpleText":"Видео с ограниченным доступом"}
			if( strpos( $buf_only_player_response , "LOGIN_REQUIRED" ) )
				echo '<br>Проверили - "status":"LOGIN_REQUIRED","messages":["Это видео с ограниченным доступом. Войдите в аккаунт."]';
			
			
			
			exit("Exit");
		}
		
		//echo "<br>JSON нормальный";
		//echo $this->Player_Response_JSON_Full;
		
		unset($this->Video_Info_Asoc);
		
		
		
	}
	
	
	#############################################################
	
	

	
	#############################################################
	
	
	# Убирает лишние ключи
	function Player_Response_JSON_Erase( )
	{

		unset( $this->Player_Response_JSON_Full['playbackTracking'] );
		unset( $this->Player_Response_JSON_Full['videoDetails']['keywords'] );
		unset( $this->Player_Response_JSON_Full['playerConfig'] );
		unset( $this->Player_Response_JSON_Full['storyboards'] );
		unset( $this->Player_Response_JSON_Full['attestation'] );
		unset( $this->Player_Response_JSON_Full['messages'] );
		unset( $this->Player_Response_JSON_Full['adSafetyReason'] );
		unset( $this->Player_Response_JSON_Full['captions'] );
		unset( $this->Player_Response_JSON_Full['responseContext'] );
		unset( $this->Player_Response_JSON_Full['endscreen'] );
		unset( $this->Player_Response_JSON_Full['annotations'] );
		unset( $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['availableCountries'] );
		
	}

	
		
	#############################################################
	
	
	function Check_Playability_Status()
	{
		
		### Тут условия и если косяк, то вывод текста ошибки и exit
		
		$this->Playability_Status = $this->Player_Response_JSON_Full['playabilityStatus']['status'];
		
		
		if( $this->Playability_Status != "OK" )
		{
			echo "<br>  Playability_Status != OK";
			echo "<br>  Playability_Status : " . $this->Playability_Status;
			echo "<br>  reason : " . $this->Player_Response_JSON_Full['playabilityStatus']['reason'];
			
			echo "<br> Тут будет переброс на функцию с выводом красивых ошибок";
			exit("<br>Exit");
		}
		
		
		# Выявление идущего или закончившегося стрима
		if( @$this->Player_Response_JSON_Full['videoDetails']['isLiveContent'] == 1 )
		{
			
			//if( @$this->Player_Response_JSON_Full['playabilityStatus']['liveStreamability'] )  Работает
			if ( @$this->Player_Response_JSON_Full['videoDetails']['isLive'] == 1 )
			{
				echo "<br>Это идущий стрим.";
				echo "<br> Тут будет переброс на функцию с выводом красивых ошибок";
				exit ("Exit");
			}
			
			echo "<br>Это запись уже закончившегося стрима.";
			echo "<br> Тут будет переброс на функцию с выводом красивых ошибок";
			exit ("Exit");
			
		}
		
		
		
		# "OK"
	
	}
	
	
	#############################################################
	
	
	function Fill_FIN_Video_Info( )
	{
		$this->FIN_Video_Info_Asoc['videoId']       = $this->Player_Response_JSON_Full['videoDetails']['videoId'];
		$this->FIN_Video_Info_Asoc['title']         = $this->Player_Response_JSON_Full['videoDetails']['title'];
		$this->FIN_Video_Info_Asoc['lengthSeconds'] = $this->Player_Response_JSON_Full['videoDetails']['lengthSeconds'];
		$this->FIN_Video_Info_Asoc['channelId']     = $this->Player_Response_JSON_Full['videoDetails']['channelId'];
		$this->FIN_Video_Info_Asoc['shortDescription'] = $this->Player_Response_JSON_Full['videoDetails']['shortDescription'];
		
		$this->FIN_Video_Info_Asoc['viewCount']     = $this->Player_Response_JSON_Full['videoDetails']['viewCount'];
		$this->FIN_Video_Info_Asoc['author']        = $this->Player_Response_JSON_Full['videoDetails']['author'];
		$this->FIN_Video_Info_Asoc['ownerProfileUrl'] = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['ownerProfileUrl'];
		$this->FIN_Video_Info_Asoc['category']      = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['category'];
		$this->FIN_Video_Info_Asoc['publishDate']   = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['publishDate'];

		//SF::PRINTER($this->FIN_Video_Info_Asoc); exit;
	}
	
	
	function Fill_FIN_Video_Thimbnails_Arr( )
	{
		$this->FIN_Video_Thimbnails_Arr   = $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'];
		
		foreach( $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['thumbnail']['thumbnails'] as $one_arr)
			$this->FIN_Video_Thimbnails_Arr []= $one_arr;
	
		//SF::PRINTER($this->FIN_Video_Thimbnails_Arr); exit;
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
							<td><strong>quality</strong></td>
							<td><strong>qualityLabel</strong></td>
							<td><strong>Размер</strong></td>
							<td><strong>contentLength</strong></td>
							<td><strong>URL</strong></td>
						</tr>
					</thead>
					<tbody>
					";

						
						
		#foreach($this->FIN_Video_Itag_Info_Asoc_Audio as $One_Set)
		#foreach($this->FIN_Video_Itag_Info_Asoc_Video_MP4 as $One_Set)
		foreach($this->FIN_Video_Itag_Info_Asoc_FULL as $One_Set)
		{
			echo "<tr>";
			
			echo 	"<td>". $One_Set['itag'] ."</td>";
			echo 	"<td>". $One_Set['mimeType'] ."</td>";
			echo 	"<td>". $One_Set['quality'] ."</td>";
			echo 	"<td>". $One_Set['qualityLabel'] ."</td>";
			echo 	"<td>". $One_Set['width'] . "x" . $One_Set['height'] ."</td>";
			echo 	"<td>". (int)($One_Set['contentLength']/1024/1024) ."Мб</td>";
			echo 	"<td>". 
							#"<a download=\"". $One_Set['itag'] ."\" target=\"_blank\" href=\"". $One_Set['url'] ."\"> 	<span> Скачать </span>	</a>"
							//	onclick=\"return confirm('Скачать?');\"
							'<a href="'.$One_Set['url'].'" download>
								
								<span> Скачать </span>	
							</a>'
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