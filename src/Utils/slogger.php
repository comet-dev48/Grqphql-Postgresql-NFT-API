<?php 
    $debugEnabled = true;

    class Slogger {

        //opens file in append mode.

        function slog($class, $level, $message){
            global $debugEnabled;
            if($debugEnabled){
                try {
                    $fp = fopen('rsapi.log', 'a');
                } catch (\Throwable $th) {
                    //Do nothing
                }
    
                //TODO add debug level logic
                if($fp != NULL){
                    //removing all newline from the message
                    $message = preg_replace("/\r|\n/", "", $message);
                    $message = preg_replace('/\s+/', ' ', $message);
    
                    $ms =  "[RS-$level] [".date("Y-m-d H:i:s")."] [$class]:  $message \n";
                    fwrite($fp, $ms);
    
                    fclose($fp); 
                }
            }
        }

        function debug($class, $message){
            $this->slog($class, "DEBUG", $message);
        }

        function info($class, $message){
            $this->slog($class, "INFO", $message);
        }

        function warn($class, $message){
            $this->slog($class, "WARN", $message);
        }

        function arrayToString(array $array){
            $result = "";
            foreach ($array as $key => $value){
                $result .= $key."->".$value.", ";
            }
            return rtrim($result, ", ");
        }
    }
?>