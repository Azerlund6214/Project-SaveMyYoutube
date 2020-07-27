<?php

	######################################################################################
	###########################################
	###################### Подготовка
	
	
	
	
	require_once "UNIV_PHP_CLASS_YOUTUBE.php";
	$UN_YOUTUBE = new UNIV_PHP_YOUTUBE();
	
	
	require_once "SF_CLASS.php";
	//SF::PRINTER(Array("123","12"));
	//SF::Print_Class_Func_and_Vars($UN_YOUTUBE);
	//SF::Memory_Usage_EchoGet("M","Echo");
	//SF::Echo_This_File_Path();
	//echo SF::Get_This_Server_Domain(true,true,"213");
	//SF::PRINTER( SF::Get_Server_Headers() );
	//SF::PRINTER( SF::Get_HTTP_Response() );
	
	
	
	//SF::PRINTER( SF::Get_Server_Headers($_POST["url"]) );
	//exit;
	
	
	echo "<hr>";
	######################
	###########################################
	######################################################################################
	
	# https://www.youtube.com/?gl=RU&hl=ru   Главная страница
	# https://www.youtube.com/watch?v=JNtw9Cppo8k     TBS
	
	$user_request_type = $_POST["radios"];
	$user_request_url  = $_POST["url"];
	
	
	
	#$cb1_Echo_POST = ( isset($_POST["cb1_Echo_POST"]) ) ? true : false ;
	

	if( isset($_POST["cb1_Echo_POST"]) )
		SF::PRINTER($_POST , "print_r", "ВСЕ пришедшее в POST");
	
	######################################################################################
	###########################################
	###################### Начало
	
	$UN_YOUTUBE -> Get_Video_ID( $user_request_url );
	if( isset($_POST["cb4_Echo_Video_ID"]) )
		SF::PRINTER($UN_YOUTUBE->Video_ID , "print_r" , "Итоговый Video_ID");
	
	######################
	############################################
	######################
	
	$UN_YOUTUBE -> Get_Video_Info(  );
	$UN_YOUTUBE -> Decode_Video_Info(  );
	
	if( isset($_POST["cb5_Echo_Video_Info_Asoc"]) )
		SF::PRINTER($UN_YOUTUBE->Video_Info_Asoc , "print_r" , "Video_Info_Asoc ВЕСЬ;  COUNT=".count($UN_YOUTUBE->Video_Info_Asoc));
		
	
	
	
	######################
	
	
	######################
	//$UN_YOUTUBE -> Check_Video_Response_Status(  );  # Дальше  либо exit в ERROR_HANDLER
	//if( isset($_POST["cb6_Echo_Video_Status_Is_OK"]) )
	//	SF::PRINTER($UN_YOUTUBE->Video_Status_Is_OK , "Video_Status_Is_OK = Видео можно смотреть");
	
	//echo count($UN_YOUTUBE->Video_Info_Asoc);
	
	$UN_YOUTUBE -> Player_Response_Convert_JSON_To_Asoc(  );  #
	if( isset($_POST["cb7_Echo_JSON_Full"]) )
		SF::PRINTER($UN_YOUTUBE->Player_Response_JSON_Full ,"print_r", "Player_Response_JSON_Full = Полный JSON");
	
	
	$UN_YOUTUBE -> Player_Response_JSON_Erase(  );  #
	if( isset($_POST["cb8_Echo_JSON_Erased"]) )
		SF::PRINTER($UN_YOUTUBE->Player_Response_JSON_Full , "print_r", "Player_Response_JSON_Erased = Урезанный JSON");
	
	
	$UN_YOUTUBE -> Check_Playability_Status(  );  #
	
	
	//exit("exit");
	
	
	
	
	######################
	
	######################
	############################################
	######################
	
	$UN_YOUTUBE -> Fill_FIN_Video_Info(  );  #
	if( isset($_POST["cb9_Echo_FIN_Video_Info_Asoc"]) )
		SF::PRINTER($UN_YOUTUBE->FIN_Video_Info_Asoc,"print_r" , "FIN_Video_Info_Asoc = ВСЯ Основная инфа о видео");
	
	######################
	
	$UN_YOUTUBE -> Fill_FIN_Video_Thimbnails_Arr(  );
	if( isset($_POST["cb10_Echo_FIN_Video_Thumb_Url_Arr"]) )
		SF::PRINTER($UN_YOUTUBE->FIN_Video_Thimbnails_Url_Arr,"print_r" , "FIN_Video_Thimbnails_Url_Arr = Все Thimbnails");
	
	######################
	
	$UN_YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_FULL(  );
	if( isset($_POST["cb11_Echo_FIN_Video_Itag_Info_Asoc_FULL"]) )
		SF::PRINTER($UN_YOUTUBE->FIN_Video_Itag_Info_Asoc_FULL,"print_r" , "FIN_Video_Itag_Info_Asoc_FULL = ВСЯ Инфа о форматах видео");
	
	######################
	
	$UN_YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_Video_MP4(  );
	
	if( isset($_POST["cb12_Echo_FIN_Video_Itag_Info_Asoc_Video_MP4"]) )
		SF::PRINTER($UN_YOUTUBE->FIN_Video_Itag_Info_Asoc_Video_MP4 ,"print_r", "FIN_Video_Itag_Info_Asoc_Video_MP4 = ВСЯ Инфа о форматах видео(Только MP4)");
	
	
	$UN_YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_Audio(  );
	if( isset($_POST["cb13_Echo_FIN_Video_Itag_Info_Asoc_Audio"]) )
		SF::PRINTER($UN_YOUTUBE->FIN_Video_Itag_Info_Asoc_Audio,"print_r" , "FIN_Video_Itag_Info_Asoc_Audio = ВСЯ Инфа о форматах AUDIO");
	
	
	######################
	############################################
	######################
	
	
	$UN_YOUTUBE -> Echo_Table(  );
	
	
	
	######################
	
	
	
	echo "<br>END";
	exit;
	
	
	
	#
?>