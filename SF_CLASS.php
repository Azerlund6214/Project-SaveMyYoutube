<?php


/**
 * Класс со множеством мелких, но полезных методов. Все статичное!!!
 * @method static потом()
 */
class SF {
	
	
	
	public static $alphabet_eng_big = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	public static $alphabet_eng_sml = "abcdefghijklmnopqrstuvwxyz";
	
	public static $alphabet_rus_big = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
	public static $alphabet_rus_sml = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	
	public static $alphabet_nums = "0123456789";
	
	
	
	/**
	 * 
	 * 
	 */
	
	/**
	 * Шаблон
	 * @param integer $ -
	 * @param string $ -
	 * @return string
	 */
	
	
	
	/**
	 * Выводит содержимое любой переменной в хорошо читаемом виде. Основная функция для дебага.
	 * @param string $Traget - Что выводим
	 * @param string $MODE - Тип вывода = print_r или var_dump
	 * @param string $Description - Описание
	 */
	public static function PRINTER( $Traget, $MODE = "print_r", $Description = "Default" )
	{
		#TODO Сделать еще принтер массивов в таблицу
		
		echo "<hr color=red>"; 
		echo "<pre>";
		
		if ( $Description != "Default" )
			echo "Описание: $Description<br>";
	
		switch( $MODE )
		{
			case "print_r": 
			case "print": 
			case "P": 
			case "p": 
							print_r( $Traget );
							break;
			
			case "var_dump":
			case "var":
			case "V":
			case "v":
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
	
	/**
	 * Выведет список переменных и метоодов класса(объекта)
	 * @param string $target - экземпляр класса для вывода
	 * @param string $mode - FUNC / VARS / anychar - Что выводим
	 */	
	public static function Print_Class_Func_and_Vars( $target , $mode="any char")
	{
		
		echo "<pre>";
		
		echo "<hr color=red>"; 
		echo "<hr color=red>"; 
		
		switch( $mode )
		{
			case "FUNC":
				echo "<hr>Все методы класса:";
				print_r( @get_class_methods( $target ) );
				break;
				
			case "VARS":
				echo "<hr>Все ПОЛЯ класса:"; 
				print_r( @get_object_vars( $target ) );
				break;
				
			default:
					echo "<hr>Все методы класса:";
					print_r( @get_class_methods( $target ) );
					
					echo "<hr color=blue>Все ПОЛЯ класса:"; 
					print_r( get_object_vars( $target ) );
		}
		
		echo "<hr color=red>"; 
		echo "<hr color=red>"; 

		echo "</pre>";
	}
		
	/**
	 * Выводит текущее использование памяти PHP
	 * @param string $Unit - Единица измерения - G M K B
	 * @param string $Action - Вывести или получить результат - (Echo echo E e)  или  (Get get G g)
	 * @param bool $Peak - Выводить ли пиковое значение
	 * @param bool $Real - Выводить ли реальное значение (например вместо 234 будет 256)
	 * @return bool, double
	 * 		True = Успешно вывел Echo
	 * 		False = Неправильные параметры
	 * 		double = Успешно выдал данные
	 */
	public static function Memory_Usage_EchoGet( $Unit = "M", $Action = "Echo", $Peak = false, $Real = false)
	{
		
		if( ! is_bool($Peak) || ! is_bool($Real) )
		{
			echo "<br>Не BOOL параметры Peak($Peak) или Real($Real).(Return false)";
			return false;
		}
				
		if( $Peak )
			$ram = memory_get_peak_usage($Real);
		else
			$ram = memory_get_usage($Real);
		
		switch($Action)
		{
			case "Echo":
			case "echo":
			case "E":
			case "e":
					$MSG = "Сейчас занято памяти: ";
					switch( $Unit )
					{
						case "G": 	echo "<br>$MSG".(double)$ram/1024/1024/1024 . " ГБайт"; return true;
						case "M": 	echo "<br>$MSG".(double)$ram/1024/1024      . " МБайт"; return true;
						case "K": 	echo "<br>$MSG".(double)$ram/1024           . " КБайт"; return true;
						case "B": 	echo "<br>$MSG".(double)$ram                . "  Байт"; return true;	
						default: echo "<br>Ошибка в единице измерения.(Return false)"; return false;
					}
		
			case "Get":
			case "get":
			case "G":
			case "g":
					switch( $Unit )
					{
						case "G": 	return (double)$ram/1024/1024/1024; break;
						case "M": 	return (double)$ram/1024/1024; break;
						case "K": 	return (double)$ram/1024; break;
						case "B": 	return (double)$ram; break;
						default: echo "<br>Ошибка в единице измерения(Return false)."; return false;
					}
		
			default: 
					echo "<br>Неправильная команда(EchoGet)(Return false)."; 
					return false;
		
		}# End switch
		
	}#End Func
	
	
	/**
	 * Выводит путь до файла, который вызвал функцию (путь от корня САЙТА (НЕ Файловой системы))
	 * @param string $TARGET = FILE / FOLDER
	 * @param string $ACTION = ECHO / RETURN
	 * @param string $DIR = Переменная __DIR__ из места вызова функции (прямо так и писать)
	 * @return string 
	 */
	public static function Echo_This_File_Path( $TARGET = "FILE", $ACTION="ECHO", $DIR = "")
	{
		# Старый рабочий вариант:  (PATH = __FILE__ при вызове этой функции)
		#$PATH = str_replace ( "\\", "/", $PATH); # Нужно только для локалки, на хосте все слеши сразу правильные
		#echo str_replace ( $_SERVER['DOCUMENT_ROOT'] , "" , $PATH );
		
		if ( $TARGET === "FILE" )
		{
			$file_caller = debug_backtrace()[0]['file']; # ПОЛНЫЙ путь до вызвавшего ФАЙЛА
			
			$file_caller = str_replace ( "\\", "/", $file_caller); # Нужно только для локалки, на хостинге все слеши сразу правильные
			$result = str_replace ( $_SERVER['DOCUMENT_ROOT'] , "" , $file_caller );
			
		}
		else
		{
			# Если нужна папка
			$file_dir = str_replace ( "\\", "/", $DIR); # Нужно только для локалки, на хосте все слеши сразу правильные
			$result = str_replace ( $_SERVER['DOCUMENT_ROOT'] , "" , $file_dir );
		}
		
		
		$result = substr($result,1); # Обрезаем слеш в начале (Чтоб не ругался хостинг)
		
		
		if ( $ACTION === "ECHO" )
			echo $result;
		else
			return $result;
		
	}
	
	
	/**
	 * Получить строку c доменом этого сервера - "https://www.yandex123.ru:80", "http://localhost:80"
	 * @param bool $Protocol = Добавлять ли протокол
	 * @param bool $Port = Добавлять ли порт
	 * @return string
	 */
	public static function Get_This_Server_Domain( $Protocol=true, $Port=false)
	{
		$Domain = $_SERVER['HTTP_HOST'];
		if ( $Protocol === true)
		{
			$prot = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http://' : 'https://';
			$Domain = $prot.$Domain;
		}
		
		
		if ( $Port === true)
			$Domain = $Domain.":".$_SERVER['SERVER_PORT'];
		
		
		return $Domain;
	}
	
	
	/**
	 * Получить заголовки с любого сервера
	 * @param string $URL = адрес сайта, Обязательно с протоколом!
	 * @param integer $Arr_type = Тип массива на выходе = 1-Асоциативный 0-Одномерный
	 * @return asoc_array []=>[] , integer -1 (если ошибка)
	 */
	public static function Get_Server_Headers( $URL = "https://yandex.ru" , $Arr_type = 1 )
	{
		$Answer = @get_headers( $URL , $Arr_type ); # Без 1 будет не асоциативный(Все в кучу)
		
		if( empty($Answer) )
			return -1;
		
		return $Answer; 	
	}
	
	
	/**
	 * Получить заголовки с любого сервера
	 * @param string $URL = адрес сайта, Обязательно с протоколом!
	 * @return string = 3-значный код ответа "404" и тд ,  bool = false (если ошибка)
	 */
	public static function Get_HTTP_Response( $URL = "https://yandex.ru" )
	{
		/*
		$ch = curl_init('http://yoururl/');
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$c = curl_exec($ch);
		return curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// */
		
		$Answer = @get_headers( $URL , 1 ); # Без 1 будет не асоциативный(Все в кучу)
		
		if( empty($Answer) )
			return false;
		
		return substr($Answer[0], 9, 3 ); // HTTP/1.1 404 Not Found
		
	}
	
	
	/**
	 * Возвращает случайную строку заданной длинны состоящую из заданного алфавита.
	 * @param integer $length - Длина желаемой строки
	 * @param string $alphabet - Алфавит для генерации (есть дефолтный)
     * @return string
	 */
	public static function Get_Random_String( $length = 10, $alphabet = "Default" )
	{
		
		if ( $alphabet === "Default" )
			$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		
		srand((double)microtime()*1000000); # Увеличиваем рандомность
		
		$strlength = strlen($alphabet);
		
		$random = '';
		
		# Альтернатива - $random = substr(str_shuffle($alphabet), 0, $length);
		for ($i = 0; $i < $length; $i++)
			$random .= $alphabet[rand(0, $strlength - 1)];
		
		return $random;
	}
	
	
	/**
	 * Возвращает случайную удобночитаемую строку заданной длинны. (алфавит - маленькие англ буквы)
	 * @param integer $length - Длина желаемой строки
	 * @param bool $Big_first_char - Делать ли первую букву заглавной
     * @return string
	 */
	public static function Get_Random_String_Readable( $length = 10 , $Big_first_char = true )
	{
		$c = array('b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','x','y','z');
		$v = array('a','e','i','o','u');
		
		srand((double)microtime()*1000000); # Увеличиваем рандомность
		
		$max = $length / 2;
		
		$random = '';
		
		for ($i = 1; $i <= $max; $i++)
		{
			$random .= $c[rand(0,19)];
			$random .= $v[rand(0,4)];
		}
		
		if( $Big_first_char )
				$random[0] = strtoupper( $random[0] );
		
		return $random; 
	}


    /**
     * Выводит полный стек вызовов функций
     * @param bool $Exit_after_echo - Завершать ли работу после вывода
     */
    public static function Echo_Call_Stack( $Exit_after_echo = true )
    {
        # http://php.net/manual/ru/function.debug-backtrace.php


        SF::PRINTER(debug_backtrace());


        $result = array();
        foreach( debug_backtrace() as $one )
        {
            $text  = @$one['class'];
            $text .= @$one['type'];
            $text .= @$one['function'];
            $text .= "( ";


            //$text .= count(@$one['args'])." Аргумента";

            /*
            foreach ( @$one['args'] as $arg )
            {
                //echo "<br>".print_r($arg);


                //$text .= var_dump($arg);
                //$text .= "";

            }*/


            $text .= " )";

            //$text .= @$one[''];


            $result []= $text;




        }

        SF::PRINTER($result);


        # обратный фор с выводом и отступами


        if( $Exit_after_echo )
            exit("<hr>Выход из Echo_Call_Stack");
    }





    public static function Dir_Create( $path )
    {
        if ( is_dir($path) )
            return true; // если есть такая папка

        if( mkdir( $path, "777") )
            return true;

        return false;
    }


    public static function Dir_Exist( $path )
    {
        if ( is_dir($path) )
            return true; // если есть такая папка

        return false;
    }

    /**
     * Рукурсивно удаляет папку со всеми файлами внутри!
     * Удаляет в том числе скрытые файлы (начинающиеся с точки)
     * Удаляет ссылочные файлы не трогая файлы куда ведет ссылка
     * @param $path - ФОРМАТ: "Папка1/Папка2" - МОЖНО слеш в конце
     */
    public static function Dir_Delete( $path )
    {
        $includes = glob($path.'/{,.}*', GLOB_BRACE);
        $systemDots = preg_grep('/\.+$/', $includes);

        foreach ($systemDots as $index => $dot) {

            unset($includes[$index]);
        }

        foreach ($includes as $include) {

            if(is_dir($include) && !is_link($include)) {

                SF::Dir_Delete($include);
            }

            else {

                unlink($include);
            }
        }

        rmdir($path);


    }

    /**
     * Рукурсивно удаляет папку со всеми файлами внутри!
     * Удаляет в том числе скрытые файлы (начинающиеся с точки)
     * Удаляет ссылочные файлы не трогая файлы куда ведет ссылка
     * @param $path - ФОРМАТ: "Папка1/Папка2" - БЕЗ слешей по краям
     */
    public static function Dir_Delete_2( $path )
    {

        if( ! SF::Dir_Exist($path) )
            return;

        $includes = new FilesystemIterator($path); // Встроенная библиотека

        foreach ($includes as $include)
        {

            if(is_dir($include) && !is_link($include))
            {

                SF::Dir_Delete($include);
            }

            else
            {
                unlink($include);
            }
        }

        rmdir($path);

    }



    public static function File_Create( $file_name )
    {
        if ( file_exists($file_name) ) //
            return;

        $fp = fopen($file_name, 'w+'); // Создаем файл
        fclose($fp);

    }

    public static function File_Exist( $file_name )
    {
        if ( file_exists( $file_name) )
            return true; // если есть такая папка

        return false;

    }

    public static function File_Clear( $file_name )
    {
        $fp = fopen($file_name, 'w+'); //
        fclose($fp);
    }

    public static function File_Put( $file_name , $text , $new_line = true)
    {
        $fp = fopen($file_name, 'a+'); //


        fwrite($fp, $text);

        if($new_line)
            fwrite($fp, PHP_EOL);

        fclose($fp);

        /*
            fwrite ($fp, "\n");
            fwrite ($fp, "\r\n");
            fwrite ($fp, chr(0x0a));
            fwrite ($fp, PHP_EOL);
        */
    }

    public static function File_Delete( $file_name )
    {
        if ( file_exists($file_name) ) //
            unlink( $file_name );
    }


    /* 'r'  - Чтение ; Начало файла
     * 'r+' - Чт/Зап ; Начало файла
     * 'w'  - Запись ; Начало файла ; Создаем если нет ; Очистить файл.
     * 'w+' - Чт/Зап ; Начало файла ; Создаем если нет ; Очистить файл.
     * 'a'  - Запись ; Конец файла  ; Создаем если нет.
     * 'a+' - Чт/Зап ; Конец файла  ; Создаем если нет. */






} # End class

?>