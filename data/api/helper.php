<?php

    class Helper {

        public static function generateKey($length = 16){
            return base64_encode(random_bytes($length));
        }
    }

?>