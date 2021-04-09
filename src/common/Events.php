<?php

namespace Empathy\Engine;

use VoidCore;
use Empathy\Engine\Additions;
use Empathy\Engine\Wrappers\NetObject;

class Events
{
    public static function setEvent (int $selector, string $eventName, callable $callback): void
    {
        VoidCore::setEvent ($selector, $eventName, function ($sender, ...$args) use ($callback)
		{
            foreach ($args as $id => $arg)
                $args[$id] = Additions::coupleSelector ($arg);

            try
			{
                return $callback (new NetObject ($sender), ...$args);
            }
            
			catch (\Throwable $e)
			{
                message ([
                    'type'  => get_class ($e),
                    'text'  => $e->getMessage (),
                    'file'  => $e->getFile (),
                    'line'  => $e->getLine (),
                    'code'  => $e->getCode (),
                    'trace' => $e->getTraceAsString ()
                ], 'PHP Critical Error');
            }
        });
    }

    public static function removeEvent (int $selector, string $eventName): void
    {
        VoidCore::removeEvent ($selector, $eventName);
    }
}
