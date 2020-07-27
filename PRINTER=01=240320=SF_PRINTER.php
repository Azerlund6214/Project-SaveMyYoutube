<?php


		### print_r / var_dump
		function SF_PRINTER( $Traget, $MODE = "print_r", $Description = "Default" )
		{
			echo "<hr color=red>"; 
			echo "<pre>";
			
			if ( $Description != "Default" )
				echo "Описание: $Description<br>";
		
			switch( $MODE )
			{
				case "print_r": 
				case "P": 
								print_r( $Traget );
								break;
				
				case "var_dump":
				case "V":
								var_dump( $Traget );
								break;
								
				default:
						echo "SF_PRINTER: case-Дефолт (MODE=$MODE) (Валидные=P или V), Вывожу как var_dump(V) \n\n";
						var_dump( $Traget );
						break;
			
			}

			echo "</pre>";
			echo "<hr color=red>"; 			
		}
	
		# Сделать принтер массивов в таблицу(по сути тот же генератор)



?>