<?php

declare(strict_types=1);

namespace HyperfFacade;

use Hyperf\Framework\Event\AfterWorkerStart;
use Hyperf\Event\Contract\ListenerInterface;

class Listener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            AfterWorkerStart::class,
        ];
    }

    public function process(object $event)
    {
        // 注解处理
        Annotation::controller();
    }
}