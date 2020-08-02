<?php

declare(strict_types=1);

namespace HyperfFacade\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Alias extends AbstractAnnotation
{
    public $name;

    public function __construct($value = null)
    {
        $this->bindMainProperty('name', $value);
    }
}