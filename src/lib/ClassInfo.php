<?php


namespace huikedev\huike_base\lib;


use think\Exception;

class ClassInfo
{
    /**
     * @var string
     */
    protected $file;
    /**
     * @var string
     */
    protected $className;
    /**
     * @var string
     */
    protected $namespace;
    public function __construct(string $file)
    {
        $this->file = $file;
        $this->parseFile();
    }

    public function isClass():bool
    {
        return empty($this->className) === false;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getFullClassName()
    {
        return empty($this->namespace) ? $this->className : $this->namespace .'\\' .$this->className;
    }

    protected function parseFile():void
    {
        if(file_exists($this->file)===false){
            throw new Exception($this->file.' not exist');
        }
        $content = file_get_contents($this->file);
        $tokens = token_get_all($content);
        for ($index=0;isset($tokens[$index]);$index++){
            if (!isset($tokens[$index][0])) {
                continue;
            }
            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;
                while (isset($tokens[$index]) && is_array($tokens[$index])) {
                    $this->namespace .= $tokens[$index++][1];
                }
            }
            if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                $this->className = $tokens[$index + 2][1];
                break;
            }
        }
    }
}