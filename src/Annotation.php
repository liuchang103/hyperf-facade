<?php

declare(strict_types=1);

namespace HyperfFacade;

use Hyperf\Di\Annotation\AnnotationCollector;

// 注解处理器
class Annotation
{
    // 收集控制器类
    public static function controller()
    {
        $annotation = AnnotationCollector::getClassByAnnotation(Annotation\Alias::class);

        foreach($annotation as $class => $annotation)
        {
            class_alias($class, $annotation->name);
        }
    }
}