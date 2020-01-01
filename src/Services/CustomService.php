<?php

namespace App\Services;

class CustomService
{
    private $random_array;
    
    public function __construct($random_array, $upload_director)
    {
        // var_dump($upload_director);
        // var_dump($random_array);
        $this->random_array = $random_array;        
    }
    public function get($url)
    {
        
        return "Get from  API : ". $this->random_array[array_rand($this->random_array)];
    }
}