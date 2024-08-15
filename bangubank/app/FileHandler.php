<?php
namespace App;

class FileHandler
{
    private $filePath;
    public function __construct($filePath) {
        $this->filePath = $filePath;
    }
    public function loadData() : array
    {
        if (file_exists($this->filePath)) {
            $jsonData = file_get_contents($this->filePath);
            return json_decode($jsonData, true) ? json_decode($jsonData, true) : [];
        }
        return [];
    }
    public function saveData($data) 
    {
        $existingData = $this->loadData();
        $existingData[] = $data;

        return file_put_contents($this->filePath, json_encode($existingData,JSON_PRETTY_PRINT));
    }
    
}
