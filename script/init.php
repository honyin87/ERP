<?php

        $GLOBALS['path'] = "/newCMS";
        $GLOBALS['debug'] = false;
		$GLOBALS['language'] = 'en-US';
        
        if (!function_exists('showError'))   {
        function showError($errno, $errstr,$error_file,$error_line,$error_trace,$error_category){
                        $errNo = $errno;
                        $errMsg = $errstr;
                        $errorFile = $error_file;
                        $errorLine = $error_line;
                        $errorTrace = $error_trace;
                        $errorCategory = $error_category;
                        include dirname(__FILE__).'/../script/content/errorHandling.php';
        }
}
ini_set('display_errors', false);
if (!function_exists('handleShutdown'))   {
function handleShutdown() {
    if (($error = error_get_last())) {
        /*ob_start();
        echo "<pre>";
        var_dump($error);
        echo "</pre>";
        $message = ob_get_clean();
        //sendEmail($message);
        ob_start();
                echo $message;
        echo '{"status":"error","message":"Internal application error!"}';*/
                
                showError($error['type'],$error['message'],$error['file'],$error['line'],"","FATAL");
        ob_flush();
        exit();
    }
}
register_shutdown_function("handleShutdown");
}


if (!function_exists('myException'))   {
function myException($exception) {
        /*echo "myException<br />";
  echo $exception->getmessage();
  echo "<pre>";
  echo var_dump($exception);
  echo "</pre>";*/
  


        showError($exception->getCode(),$exception->getMessage(),$exception->getFile(),$exception->getLine(),$exception->getTraceAsString(),"EXCEPTION");

}

set_exception_handler('myException');


function checkNum($number) {
  if($number>1) {
    throw new Exception("Value must be 1 or below");
  }
  return true;
}
}
//trigger exception
//checkNum(2);

//error handler function
function handleError($errno, $errstr,$error_file,$error_line) {
      //echo "<b>Error:</b> [$errno] $errstr - $error_file:$error_line";
      //echo "<br />";
          showError($errno, $errstr,$error_file,$error_line,"","ERROR");
      //echo "Terminating PHP Script";
      
      //die();
   }

//set error handler
set_error_handler("handleError");

//trigger error
//echo($test);

/*	*
	*Get any language translation from DB langtext -> langtranslation
	*return $key if no result found.
	*/
function translate($key){
        $translateText = R::findOne('langtext',' text = ? ', 
                array( $key )
               );
                   $output = $translateText->withCondition('language = ?',array($GLOBALS['language']))->ownLangtranslation;

				   if(!empty($output)){
                        $translationValue = array_values($output)[0];
						//echo $translationValue->translation;
						return $translationValue->translation;
				   }else{
						//echo $key;
						return $key;
				   }
                
}


?>
<html>
  <head>
    <title></title>
  </head>
  <body></body>
</html>
