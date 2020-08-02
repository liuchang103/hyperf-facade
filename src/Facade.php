<?php

declare(strict_types=1);

namespace HyperfFacade;

use RuntimeException;
use Hyperf\Utils\ApplicationContext;

abstract class Facade
{
    // 门面
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
    
    // make 参数
    protected static function getResolveAccessor()
    {
        return [];
    }
    
    // 单例模式
    protected static function singleton()
    {
        return true;
    }

    // 获取实例
    public static function instance()
    {
        return static::singleton() ? 
            static::container()->get(static::getFacadeAccessor()) : 
            static::container()->make(static::getFacadeAccessor(), static::getResolveAccessor());
    }

    // 容器实例
    public static function container()
    {
        return ApplicationContext::getContainer();
    }

    // 静态访问
    public static function __callStatic($method, $args)
    {
        $instance = static::instance();

        if(!$instance)
        {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}