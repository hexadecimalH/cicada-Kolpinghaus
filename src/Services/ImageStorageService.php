<?php

namespace Kolpinghaus\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Kolpinghaus\Libraries\ImageManipulationLibrary;

class ImageStorageService
{
    private $domain;
    private $protocol;
    private $basePath;
    /** @var  ImageManipulationLibrary $imageManipulationLibrary */
    private $imageManipulationLibrary;

    public function __construct($domain, $protocol, $basePath, $imageManipulationLibrary)
    {
        $this->domain = $domain;
        $this->protocol = $protocol;
        $this->basePath = $basePath.'Kolpinghaus';
        $this->imageManipulationLibrary = $imageManipulationLibrary;
    }
   
     /**
     * @param $title
     * @param $images UploadedFile[]
     * @return array
     */
    public function storeImages($title, $images){
        $urls = [];
        foreach($images as $image) {
            // Compose image name
            // until I come up with algorithm that generates unique names
            $imageName = str_replace("_", " ", $image->getClientOriginalName());

            // Resize
            // $image = $this->imageManipulationLibrary->resizePostImage(file_get_contents($image->getRealPath()));

            // Store to disk
            $path = $this->basePath.'/'.$title.'/'.$imageName;
            if(!file_exists($this->basePath.'/uploads\/'.$title)){
                mkdir($this->basePath.'/uploads'.'/'.$title, 0777, true);
            }
            $path = $this->storeImageContents('/uploads'.'/'.$title.'/'.$imageName, file_get_contents($image->getRealPath()));
            $url[] = $this->getFullUrl($path);
            // Add resized image to result set
        }
        return $url;
    }

    private function storeImageContents($path, $content){
        file_put_contents($this->basePath.$path, $content);
        return $path;
    }

    private function getFullUrl($path) {
        return $path;
    }

    public function removeContent($url){
        $deleted = unlink($this->basePath.$url);
    }
}