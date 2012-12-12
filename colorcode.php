<?php

class Colorcode {

    /**
     *
     * @var DirectoryIterator
     */
    protected $_directoryIterator;

    protected $_backgroundColor;

    public function __construct($path, $backgroundColor = 0) {
        $this->_directoryIterator = new DirectoryIterator($path);
        $this->_backgroundColor = $backgroundColor;
    }

    public function run() {
        foreach ($this->_directoryIterator as $item) {
            if (!$item->isDot()) {
                $fileinfo = $item->getFileInfo();
                $imageResource = $this->_imageCreateFrom($fileinfo);
                list($width, $height) = $this->_imageGetSize($fileinfo);
                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        $rgb = imagecolorat($imageResource, $x, $y);
                        if($rgb != $this->_backgroundColor){
                            echo $rgb . "\n\r";
                        }
                    }
                }

                
            }
        }
    }

    private function _imageCreateFrom(SplFileInfo $fileinfo) {
        $extension = $this->_getExtension($fileinfo);
        switch ($extension) {
            case 'jpg':
                return imagecreatefromjpeg($this->_getFullPath($fileinfo));
                break;
            case 'png':
                return imagecreatefrompng($this->_getFullPath($fileinfo));
                break;

            default:
                throw new Exception("Extension {$extension} is not supported");
                break;
        }
    }

    private function _imageGetSize(SplFileInfo $fileinfo) {
        return getimagesize($this->_getFullPath($fileinfo));
    }

    private function _getExtension(SplFileInfo $fileinfo) {
        $path_parts = pathinfo($fileinfo->getFilename());
        return $path_parts['extension'];
    }

    private function _getFullPath(SplFileInfo $fileinfo) {
        return $fileinfo->getPath() . '/' . $fileinfo->getFilename();
    }

}