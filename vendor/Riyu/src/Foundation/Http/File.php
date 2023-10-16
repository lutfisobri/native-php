<?php
namespace Riyu\Foundation\Http;

class File
{
    protected $file;

    /**
     * File name.
     * 
     * @var string
     */
    public string $name;

    /**
     * File type.
     * 
     * @var string
     */
    public string $type;

    /**
     * File size.
     * 
     * @var int
     */
    public int $size;

    /**
     * File error.
     * 
     * @var int
     */
    public int $error;

    /**
     * File temporary name.
     * 
     * @var string
     */
    public string $tmp_name;

    public function __construct($file)
    {
        $this->file = $file;

        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->size = $file['size'];
        $this->error = $file['error'];
        $this->tmp_name = $file['tmp_name'];
    }

    /**
     * Move the uploaded file to a new location.
     * 
     * @param string $destination
     * @param string $filename
     * @return bool
     */
    public function move($destination, $filename = null)
    {
        if ($filename === null) {
            $filename = $this->name;
        }

        return move_uploaded_file($this->tmp_name, $destination . '/' . $filename);
    }

    /**
     * Get the file extension.
     * 
     * @return string
     */
    public function extension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * Get the file name without extension.
     * 
     * @return string
     */
    public function name()
    {
        return pathinfo($this->name, PATHINFO_FILENAME);
    }

    /**
     * Get the file size in kilobytes.
     * 
     * @return int
     */
    public function size()
    {
        return $this->size / 1024;
    }

    /**
     * Get the file size in megabytes.
     * 
     * @return int
     */
    public function sizeInMB()
    {
        return $this->size() / 1024;
    }
}