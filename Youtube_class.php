<?php

# Записывать в переменную "UN_YOUTUBE"


/**
 * Описание  написать
 * @param string $ERR_NAME - Код ошибки
 * @param array[] $Arr_Error_Data - Не всегда, данные для вывода в описании ошибки.
 */
class YouTube
{
		
	public $Get_Video_Info_URL_Pattern = "https://www.youtube.com/get_video_info?video_id="; # ОБЯЗАТЕЛЬНО протокол
	
	public $Video_ID = "";
	
	public $Video_Info = "";
	public $Video_Info_Asoc = Array();
	
	public $Player_Response_JSON_Full; # Кусок из Video_Info (копия)
	
	public $FIN_Video_Info_Asoc = array(); # 
	public $FIN_Video_Thimbnails_Arr = array(); # Ссылки и размеры
	public $FIN_Video_Itag_Info_Asoc_FULL = array(); # [itag][ключи]
	
	
	##########
	
	function __construct()	{  }	
	function __destruct() 	{  }
	
	##########
	
	/**
	 * Вывод пользователю всех сообщений об ошибках.
	 * @param string $ERR_NAME - Код ошибки
	 * @param array[] $Arr_Error_Data - Не всегда, данные для вывода в описании ошибки.
	 */
	function Youtube_Error_Handler( $ERR_NAME , $Arr_Error_Data = array() )
	{
		
		echo "<hr color=yellow>";
		
		
		switch( $ERR_NAME )
		{
			case "NOT_SOLVED_VIDEO_ID":
												
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Не удалось извлечь Video_Id из предоставленной ссылки";
						echo "</br>";
						var_dump($Arr_Error_Data[0]);			
						break;
			
			
			case "BAD_JSON":
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Youtube прислал поломанный JSON, который нельзя разобрать. Скорее всего это видео удалено или с ограниченным доступом.";
						
						break;
						
			case "BAD_JSON-LOGIN":
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Youtube прислал поломанный JSON, который нельзя разобрать. Скорее всего это видео удалено или с ограниченным доступом.";
						echo '</br>Проверили - "status":"LOGIN_REQUIRED","messages":["Это видео с ограниченным доступом. Войдите в аккаунт."]';
						break;
			
			
			case "PLAYABILITY_STATUS_NOT_OK":
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Playability_Status != OK";
						echo "</br>Playability_Status: ". $Arr_Error_Data[0] ;
						echo "</br>Reason: ". $Arr_Error_Data[1] ;
						break;
			
			case "VIDEO_TYPE__LIVESTREAM":
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Это идущий стрим.";
						break;


			case "VIDEO_TYPE__LIVESTREAM_FINISHED":
						echo "ERROR: $ERR_NAME";
						echo "</br>Описание: Это запись уже закончившегося стрима.";
						break;
			
			/* case "VIDEO_TYPE__NOT_EXIST":
			case "VIDEO_TYPE__FORBIDDEN_TO_WATCH":
			case "VIDEO_TYPE__DELETED":
			case "VIDEO_TYPE__NO_ACCESS": */

			default:
					echo "<br>Youtube_Error_Handler: switch default => $ERR_NAME";
					break;
		
		}
		
		echo "<hr color=yellow>";
		
		exit("EXIT in ERROR handler");
		
	}
	
	
	#############################################################
	
	
	/**
	 * Подготовка ссылки и извлечение video_id через регулярные выражения.
	 * Есть обработка ошибки
	 * @param string
	 */
	function Get_Video_ID( $URL )
	{
		# Убрать все пробелы
		$URL = preg_replace( '/ {1,}/'  ,  ''  , $URL ) ;
		
		
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
				
		if( count($Buf_Video_ID) === 0 )
			$this->Youtube_Error_Handler( "NOT_SOLVED_VIDEO_ID" , array( $URL ) );

		$this->Video_ID = $Buf_Video_ID[0];
		
		//echo $this->Video_ID; exit;
		
	}
	
	
	/**
	 * Скачиваем JSON об этом видео с ютуба.
	 */
	function Get_Video_Info(  )
	{
		$Responce_url = $this->Get_Video_Info_URL_Pattern . $this->Video_ID; # ОБЯЗАТЕЛЬНО протокол
		
		$this -> Video_Info = file_get_contents( $Responce_url );
	}
	
	
	/**
	 * Декодируем JSON в асоциативный массив.
	 * Возможна ошибка при более 1000 элементов.
	 */
	function Decode_Video_Info(  )
	{		
		// Декодируем дважды, БЕЗ буферной переменной ибо файл огромный
		
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		$this -> Video_Info = urldecode( $this -> Video_Info ) ;
		
		parse_str( $this->Video_Info , $this->Video_Info_Asoc );
		
		unset( $this->Video_Info );
		
		
		/* Convert_Video_Info_To_Asoc()
			Все упадет если сткрока с JSON инфой окажется на 1000+ позиции
			
			Warning: parse_str(): Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini
			max_input_vars
			
			http://php.net/manual/ru/info.configuration.php
			Если входных переменных больше, чем задано директивой, выбрасывается предупреждение E_WARNING, а все последующие переменные в запросе игнорируются.
		*/
		
		# Тут может выскочить Warning => Тогда дальнейшая логика проги работать не будет(50на50,как повезет)
		
	}
	
	
	/**
	 * Пытаемся раззобрать JSON['player_response']
	 * Есть обработка ошибки если не удалось.
	 * TODO: Добавить другие типы ошибок.
	 */
	function Player_Response_Convert_JSON_To_Asoc()
	{	
		$buf_only_player_response = @$this->Video_Info_Asoc['player_response'];
		
		$this->Player_Response_JSON_Full = json_decode($buf_only_player_response , true);
		
		# Если приходит JSON со сломанной структурой, то его не удастся распарсить.
		if ( $this->Player_Response_JSON_Full  == "" )
		{	
			
			# Полный кусок = "playabilityStatus":{"status":"LOGIN_REQUIRED","messages":["Это видео с ограниченным доступом. Войдите в аккаунт."],"errorScreen":{"playerErrorMessageRenderer":{"subreason":{"simpleText":"Чтобы посмотреть это видео, войдите в аккаунт"},"reason":{"simpleText":"Видео с ограниченным доступом"}
			if( strpos( $buf_only_player_response , "LOGIN_REQUIRED" ) )
				$this->Youtube_Error_Handler( "BAD_JSON-LOGIN" );
			
			#TODO: Добавить другие типы ошибок
			
			$this->Youtube_Error_Handler( "BAD_JSON" );
			
		}
		
		unset($this->Video_Info_Asoc);
		
	}
	
	
	/**
	 * Просто делаем unset лишних ключей в JSON['player_response']
	 */
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

	
	/**
	 * Проверка статуса и доступности видео
	 * Есть отлов ошибок
	 * TODO: Добавить другие типы ошибок.
	 */
	function Check_Playability_Status()
	{
				
		$Playability_Status = $this->Player_Response_JSON_Full['playabilityStatus']['status'];
		
		if( $Playability_Status != "OK" )
			$this->Youtube_Error_Handler( "PLAYABILITY_STATUS_NOT_OK", array( $Playability_Status , $this->Player_Response_JSON_Full['playabilityStatus']['reason'] ) );
		
		# Выявление идущего или закончившегося стрима
		if( @$this->Player_Response_JSON_Full['videoDetails']['isLiveContent'] == 1 )
		{
			if ( @$this->Player_Response_JSON_Full['videoDetails']['isLive'] == 1 )
				$this->Youtube_Error_Handler( "VIDEO_TYPE__LIVESTREAM" );
			
			$this->Youtube_Error_Handler( "VIDEO_TYPE__LIVESTREAM_FINISHED" );	
		}
	
	}
	
	#############################################################
	
	
	/**
	 * Переносим общую информацию о видео из JSON в отдельный массив
	 */
	function Fill_FIN_Video_Info( )
	{
		$this->FIN_Video_Info_Asoc['videoId']          = $this->Player_Response_JSON_Full['videoDetails']['videoId'];
		$this->FIN_Video_Info_Asoc['title']            = $this->Player_Response_JSON_Full['videoDetails']['title'];
		$this->FIN_Video_Info_Asoc['lengthSeconds']    = $this->Player_Response_JSON_Full['videoDetails']['lengthSeconds'];
		$this->FIN_Video_Info_Asoc['channelId']        = $this->Player_Response_JSON_Full['videoDetails']['channelId'];
		$this->FIN_Video_Info_Asoc['shortDescription'] = $this->Player_Response_JSON_Full['videoDetails']['shortDescription'];
		
		$this->FIN_Video_Info_Asoc['viewCount']       = $this->Player_Response_JSON_Full['videoDetails']['viewCount'];
		$this->FIN_Video_Info_Asoc['author']          = $this->Player_Response_JSON_Full['videoDetails']['author'];
		$this->FIN_Video_Info_Asoc['ownerProfileUrl'] = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['ownerProfileUrl'];
		$this->FIN_Video_Info_Asoc['category']        = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['category'];
		$this->FIN_Video_Info_Asoc['publishDate']     = $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['publishDate'];
	}
	
	
	/**
	 * Переносим общую информацию о эскизах видео из JSON в отдельный массив
	 */
	function Fill_FIN_Video_Thimbnails_Arr( )
	{
		$this->FIN_Video_Thimbnails_Arr   =  $this->Player_Response_JSON_Full['videoDetails']['thumbnail']['thumbnails'];
		
		# Убираем последний эскиз тк он дублируется с нижним, но при этом у него не правильно выставлено разрешение
		unset( $this->FIN_Video_Thimbnails_Arr[ count($this->FIN_Video_Thimbnails_Arr)-1 ] );
		
		foreach( $this->Player_Response_JSON_Full['microformat']['playerMicroformatRenderer']['thumbnail']['thumbnails'] as $one_arr)
			$this->FIN_Video_Thimbnails_Arr []= $one_arr;
	}
	
	
	/**
	 * Переносим ссылки на видео из JSON в отдельный массив
	 */
	function Fill_FIN_Video_Itag_Info_Asoc_FULL()
	{
		
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
	
	
	/**
	 * Выводим все данные в виде общей таблицы
	 */
	function Echo_Table( )
	{
	
		echo '<table border=2px class="result_table">
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
					';

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
							'<a href="'.$One_Set['url'].'" target="_blank" download>
								<span> Скачать </span>	
							</a>'
					."</td>";
			echo "</tr>";
		}
		
		echo 	"</tbody>";
		echo "</table>";
	
	}
	
	
	
	
	
}#End class



?>