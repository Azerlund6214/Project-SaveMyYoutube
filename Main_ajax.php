<?php

	require_once "SF_CLASS.php";
	
	require_once "Youtube_class.php";
	$YOUTUBE = new YouTube();
	
	
	######################
	
	$user_request_type = $_POST["radios"];
	$user_request_url  = $_POST["url"];

	if( isset($_POST["cb1_Echo_POST"]) )
		SF::PRINTER($_POST , "print_r", "ВСЕ пришедшее в POST");
	
	###################### Начало
	
	$YOUTUBE -> Get_Video_ID( $user_request_url );
	
	if( isset($_POST["cb4_Echo_Video_ID"]) ) SF::PRINTER($YOUTUBE->Video_ID , "print_r" , "Итоговый Video_ID");
	
	######################
	
	$YOUTUBE -> Get_Video_Info(  );
	$YOUTUBE -> Decode_Video_Info(  );
	
	if( isset($_POST["cb5_Echo_Video_Info_Asoc"]) )
		SF::PRINTER($YOUTUBE->Video_Info_Asoc , "print_r" , "Video_Info_Asoc ВЕСЬ;  COUNT=".count($YOUTUBE->Video_Info_Asoc));
		
	######################
	
	$YOUTUBE -> Player_Response_Convert_JSON_To_Asoc(  );  #
	if( isset($_POST["cb7_Echo_JSON_Full"]) )
		SF::PRINTER($YOUTUBE->Player_Response_JSON_Full ,"print_r", "Player_Response_JSON_Full = Полный JSON");
	
	$YOUTUBE -> Player_Response_JSON_Erase(  );  #
	if( isset($_POST["cb8_Echo_JSON_Erased"]) )
		SF::PRINTER($YOUTUBE->Player_Response_JSON_Full , "print_r", "Player_Response_JSON_Erased = Урезанный JSON");
	
	$YOUTUBE -> Check_Playability_Status(  );  #
	
	######################
	
	$YOUTUBE -> Fill_FIN_Video_Info(  );  #
	if( isset($_POST["cb9_Echo_FIN_Video_Info_Asoc"]) )
		SF::PRINTER($YOUTUBE->FIN_Video_Info_Asoc,"print_r" , "FIN_Video_Info_Asoc = ВСЯ Основная инфа о видео");
	
	######################
	
	$YOUTUBE -> Fill_FIN_Video_Thimbnails_Arr(  );
	if( isset($_POST["cb10_Echo_FIN_Video_Thumb_Url_Arr"]) )
		SF::PRINTER($YOUTUBE->FIN_Video_Thimbnails_Url_Arr,"print_r" , "FIN_Video_Thimbnails_Url_Arr = Все Thimbnails");
	
	######################
	
	$YOUTUBE -> Fill_FIN_Video_Itag_Info_Asoc_FULL(  );
	if( isset($_POST["cb11_Echo_FIN_Video_Itag_Info_Asoc_FULL"]) )
		SF::PRINTER($YOUTUBE->FIN_Video_Itag_Info_Asoc_FULL,"print_r" , "FIN_Video_Itag_Info_Asoc_FULL = ВСЯ Инфа о форматах видео");
	
	######################
	

	
	######################	
	
	$YOUTUBE -> Echo_Table(  );
	
	######################
	
	echo "<br>END";
	exit;
	
	
	
	
	
	
	
?>