<?php

	# ������ ������
	# �������� ���������
	# �������� �� strstr(youtube.com/)  ,  ���� ?   ,  ���� =
	function Verify_URL( )
	{
		# ������ ������  http://www.youtube.com/watch?v=JYATEN_TzhA
		# ����������� .../watch? ... & v=123 & ...
	
	
		# �������� �� ����� ������� ������(����� ���������� �������)
		
		# ��������� strstr(youtube.com/)
		
		
		
		
		
		
		//$arr = array();
		
		#$reg1 = "^.*((youtu.be"  .  "\\/)"  .  "|(v\\/)|(\\/u\\/w\\/)|(embed\\/)|(watch\\?))\\??v?=?([^#\\&\\?]*).*";
		#$reg1 = "^.*((youtu.be"  .  "\/)"  .  "|(v\/)|(\/u\/w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*";
		
		#$reg1= "(?:youtube(?:-nocookie)?\\.com\\/(?:[^\\/\\n\\s]+\\/\\S+\\/|(?:v|e(?:mbed)?)\\/|\\S*?[?&]v=)|youtu\\.be\\/)([a-zA-Z0-9_-]{11})";
		#$reg1 = "/(youtu\.be\/|youtube\.com\/(watch\?(.*&)?v=|(embed|v)\/))([^\?&\"'>]+)/";
		
		#������ $reg1 = "/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})\W/";
		
		//������ $reg1 = "/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*)";
		
		/* ������ $reg1 = "v=([a-zA-Z0-9\_\-]+)&?")[1]"; */
		/* ������ $reg1 = "list=([a-zA-Z0-9\-\_]+)&?"; */
		

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
		
		
		
		$URL_Parametres_ALL = explode( "?" , $user_request_url ); # [0]=�� ������  [1]=��� ��������� ������
	
	
		# �������� ���� �� ? � ������� �� (��� �����)
		if( count($URL_Parametres_ALL) === 1 )
		{
			echo "<br>��������� ������ URL (��� ? => ��� ����������) = $user_request_url";
			exit;
		}
		
		# �������� ���� �� = � ������� ��
		if( count($URL_Parametres_ALL) <= 1 )
		{
			echo "<br>������������ ������ URL (��������� ?) = $user_request_url";
			exit;
		}
		
		
		
		
		
		
		# ������ ���� ������ 200
	
	}


### ����������� !!!!!!
	function Convert_Video_Info_To_Asoc()
	{
		
		set_error_handler(
							function ($severity, $message, $file, $line) 
							{				
								$this->ERROR_HANDLER( "PARSE_STR__MORE_THAN_1000_VARS" , "Convert_Video_Info_To_Asoc: ������=������ 1000 ���������� � parse_str");	
								#throw new \ErrorException($message, $severity, $severity, $file, $line);	
							}
						 );
		/*
			��� ������ ���� ������� � JSON ����� �������� �� 1000+ �������
			
			Warning: parse_str(): Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini
			max_input_vars
			
			http://php.net/manual/ru/info.configuration.php
			���� ������� ���������� ������, ��� ������ ����������, ������������� �������������� E_WARNING, � ��� ����������� ���������� � ������� ������������.
		*/
		# ��� ����� ��������� Warning => ����� ���������� ������ ����� �������� �� �����(50��50,��� �������)
		parse_str( $this->Video_Info , $this->Video_Info_Asoc );
		
		echo count($this->Video_Info_Asoc);
		
		restore_error_handler();

		
		return;
		
		# ������� ������� ������ �� 2 ����� => �� ���������
		$Buf = array_chunk($this->Video_Info, 2, TRUE);
		
		$Buf_Asoc_1 = array();
		$Buf_Asoc_2 = array();
		
		parse_str( $Buf[0] , $Buf_Asoc_1 );
		parse_str( $Buf[1] , $Buf_Asoc_2 );
		
		
		$this->Video_Info_Asoc = $Buf_Asoc_1;
		$this->Video_Info_Asoc += $Buf_Asoc_2;
		
		# unset $this->Video_Info
		
		# ��������   ��� ���� ��������� ������ 1000 (����� warning)
	}


	
	function Check_Video_Response_Status()
	{
		
		# $this->Video_Type
		
		
		
		# ������ ����� ���� ������ if  (� � � ��������� ���� ����� ������ "ok")
		if( $this->Video_Info_Asoc['status'] != "ok" )
		{
			
			exit("������ �� ��");
		}
		
		$this->Video_Status_Is_OK = "Video Status Is OK";
		
		
		
		# NOTICE: ����� �������� if, ����� �� ����������� ����� ��� ������� JSON(� ��� ���� ���������� ���� ������ � ����� �����)
		
		# �� ��������
		if( @$this->Video_Info_Asoc['livestream'] === "1" )
		{		
			$this->ERROR_HANDLER( "VIDEO_TYPE__LIVESTREAM" , "Check_Video_Response_Status: ������ ����� �� ������ ���������");	
		}
		
		# ��������
		# ����� �������� ������ ���������� �������
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && strstr(@$this->Video_Info_Asoc['reason'] , "�� ���������� ��������� ��� ����� �� ��������������� �� ������ ������."))
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__FORBIDDEN_TO_WATCH" , "Check_Video_Response_Status: ����� �������� ������ �� Youtube");
			# � player_response ���� ������� ������(������ ����� ������ ������)
		}
		
		# ��������
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "2" )
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__NOT_EXIST" , "Check_Video_Response_Status: ����� �� ����������(�������� ������)");
		}
		
		# ��������
		# ����� �������� ������ ���������� �������
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && @$this->Video_Info_Asoc['reason'] === "����� ������� �������������, ������� ��� �������.")
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__DELETED" , "Check_Video_Response_Status: ����� ���� �������");
		}
		
		if( @$this->Video_Info_Asoc['status'] === "fail" && @$this->Video_Info_Asoc['errorcode'] === "150" && @$this->Video_Info_Asoc['reason'] === "��� ����� ����������.")
		{
			$this->ERROR_HANDLER( "VIDEO_TYPE__NO_ACCESS" , "Check_Video_Response_Status: ����� � ������������ ��������");
		}
		
		
		

		
		
		$this->ERROR_HANDLER( "VIDEO_TYPE__SOMETHING_NEW" , "Check_Video_Response_Status: �� ���� if �� ��������(����� ����� ��� ����� ��� �������(���� ��������))");
		
		
	}
	
	
	









?>