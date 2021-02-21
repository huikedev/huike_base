<?php


namespace huikedev\huike_base\utils;


use think\helper\Str;

class UtilsTools
{
    protected static $snakeCache = [];
    /**
     * @desc 将格式不规范的路径整理为规范的路径 开头不带\
     * @param string $path
     * @param string $separator
     * @return string|string[]|null
     */
    public static function replaceSeparator(string $path,string $separator=DIRECTORY_SEPARATOR)
    {
        $path =  preg_replace('/\\\\+/',$separator,$path);
        return  preg_replace('/\/+/',$separator,$path);
    }

    public static function removeRootPath(string $path,string $separator=DIRECTORY_SEPARATOR)
    {
        return self::replaceSeparator(str_replace(app()->getRootPath(),'',$path),$separator);
    }

    /**
     * @desc 将格式不规范的命名空间整理为规范的命名空间 开头不带\
     * @param string $namespace
     * @return string
     */
    public static function replaceNamespace(string $namespace): string
    {
        $namespace = preg_replace('/\/+/','\\',$namespace);
        $namespace = preg_replace('/\\\\+/','\\',$namespace);
        $namespace = trim($namespace,'\\');
        return $namespace;
    }


    /**
     * @desc 清除字符串所有空格
     * @param string $str
     * @return string
     */
    public static function trimAll(string $str):string
    {
        return preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", $str);
    }

    public static function getClassName(string $class)
    {
        $class = self::replaceNamespace($class);
        return current(array_reverse(explode('\\',$class)));
    }

    /**
     * @desc 获取单个类文件内的方法及访问修饰符
     * @param string $file
     * @param int $methodFilter
     * @return array
     */
    public static function getMethodsFromClassFile(string $file,int $methodFilter = 0):array
    {
        if(file_exists($file) === false){
            return [];
        }
        // 是否指定方法修饰符，默认不指定
        $filters = [T_PUBLIC,T_PROTECTED,T_PRIVATE];
        if(in_array($methodFilter,$filters)){
            $filters = [$methodFilter];
        }
        $content = php_strip_whitespace($file);
        $tokens = token_get_all($content);
        $array = [];
        for ($index = 0;isset($tokens[$index]);$index++){
            if(isset($tokens[$index][0]) === false || in_array($tokens[$index][0],$filters) === false || $tokens[$index + 2][0] !== T_FUNCTION){
                continue;
            }
            array_push($array,['access'=>$tokens[$index][1],'method'=>$tokens[$index + 4][1]]);
        }
        return $array;
    }




}