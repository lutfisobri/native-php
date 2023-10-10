<?php

class RiyuLoader
{
    private static $loader;

    private $json;

    private $namespaces = [];
    
    public function __construct()
    {
        require_once __DIR__ . '/riyu_json.php';
        $this->json = new RiyuJson();
    }

    /**
     * @return RiyuLoader
     */
    public static function register()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require_once __DIR__ . '/riyu_namespace.php';

        return self::$loader = new self();
    }

    public function readFolder($path)
    {
        $folders = scandir($path);
        foreach ($folders as $folder) {
            if ($folder == '.' || $folder == '..') {
                continue;
            }
            if (is_dir($path . '/' . $folder)) {
                $this->readFolder($path . '/' . $folder);
            } else {
                if ($folder == 'riyu.json') {
                    $this->json->addAll($path, json_decode(file_get_contents($path . '/' . $folder), true));
                }
            }
        }
    }

    public function loadFiles()
    {
        $vendorDir = dirname(dirname(__FILE__));
        $baseDir = dirname($vendorDir);

        $this->readFolder($baseDir);

        foreach ($this->json->all() as $path => $json) {
            foreach ($json as $path => $value) {
                if (isset($value['autoload'])) {
                    if (isset($value['autoload']['files'])) {
                        foreach ($value['autoload']['files'] as $file) {
                            require_once $path . '/' . $file;
                        }
                    }
    
                    if (isset($value['autoload']['namespace'])) {
                        foreach ($value['autoload']['namespace'] as $namespace => $folder) {
                            $this->addNamespace($namespace, $path . '/' . $folder);
                        }
                    }
                }
            }
        }

        spl_autoload_register([$this, 'loadClass']);
    }

    public function addNamespace($namespace, $folder)
    {
        $namespace = trim($namespace, '\\') . '\\';
        $folder = rtrim($folder, '/') . '/';
        $this->namespaces[$namespace] = $folder;
    }

    public function loadClass($class)
    {
        $class = trim($class, '\\');
        foreach ($this->namespaces as $namespace => $folder) {
            if (strpos($class, $namespace) === 0) {
                $class = str_replace($namespace, '', $class);
                $file = $folder . str_replace('\\', '/', $class) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }
    }
}