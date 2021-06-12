<?php

namespace mf\utils;

class ClassLoader extends AbstractClassLoader {
    protected function getFileName(string $classname): string
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        return $path . '.php';
    }

    protected function makePath(string $filename): string
    {
        return $this->prefix . DIRECTORY_SEPARATOR . $filename;
    }

    public function loadClass(string $classname)
    {
        $path = $this->getFileName($classname);
        $path = $this->makePath($path);
        if(file_exists($path)) {
            require_once $path;
        }
    }
}