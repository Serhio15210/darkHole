<?php
$patterns=[
    'url'=>[
        'regex'=>'/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'
    ],
    'name'=>[
        'regex'=>'/^[a-zA-Z]{1,}\\s?([a-zA-Z]{1,})?/'
    ],
    'phone'=>[
        'regex'=>'((\+)?\b(38)?(0[\d]{2})([\d-]{5,8})([\d]{2}))',
        'callback'=>function($matches){
            printme($matches);
            if (empty($matches[1])){
                $matches[1]='+';
            }
            if(empty($matches[2])){
                $matches[2]='38';
            }
            $string='';
            for ( $i=1; $i<count($matches);$i++){
                $string.=$matches[$i];
            }
            return $string;
        }
    ],
    'email'=>[
        'regex'=>'/^(\w|\.|\!|\$|\%|\#|\&)+@(\w+\.)+\w{2,4}$/'
    ]
];