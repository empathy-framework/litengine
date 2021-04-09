<?php

namespace Empathy\Engine\Async;

use VoidCore;
use Empathy\Engine\Wrappers\NetObject;

require 'async/Task.php';
require 'async/EventLoop.php';

function thread (callable $callable): NetObject
{
    return new NetObject (VoidCore::createThread ($callable));
}
