<?php 
spl_autoload_register(function($className){
    $file = __DIR__."/../";

    if(str_ends_with($className,"Controller")){
        $file.="controllers/$className.php";
    }elseif(str_ends_with($className,"Database")){
        $file.="core/db/$className.php";
    }else{
        $file.="models/$className.php";
    }

    if(file_exists($file)){
        require_once $file;
    }
});

?>