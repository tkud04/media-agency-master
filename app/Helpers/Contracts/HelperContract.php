<?php
namespace App\Helpers\Contracts;

Interface HelperContract
{
        public function sendEmail($to,$subject,$data,$view,$type);
        public function getBrowser();
        public function getOS();
        public function getLatest();
        public function saveFile($filename, $content);
        public function getFile($filename);
        public function getOrCreateDirectory($directory);
        public function saveInDirectory($directory, $filename,$content);
}
 ?>