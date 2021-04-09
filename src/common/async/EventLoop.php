<?php

namespace Empathy\Engine\Async;

class EventLoop
{
    protected array $events = [];

    public function __construct (array $tasks)
    {
        foreach ($tasks as $task)
        {
            if (is_callable ($task))
                $task = Task::create ($task);

            $task->onSleep (function ()
            {
                foreach ($this->events as $event)
                    if (!$event->executed ())
                    {
                        $event->run ();
    
                        break;
                    }
            });

            $this->events[] = $task;
        }
    }

    public function run (): EventLoop
    {
        $thread = thread (function ()
        {
            foreach ($this->events as $event)
                if (!$event->executed ())
                    $event->run ()->wait ();
        });

        $thread->isBackground = true;
        $thread->start ();

        return $this;
    }

    public function completed (): bool
    {
        foreach ($this->events as $event)
            if (!$event->completed ())
                return false;

        return true;
    }

    public function wait (int $time = null): EventLoop
    {
        if ($time !== null)
            timeout ($time);

        else do
        {
            $continue = false;

            foreach ($this->events as $event)
                if (!$event->completed ())
                {
                    $continue = true;

                    break;
                }
        }

        while ($continue);

        return $this;
    }

    public function getEvents (): array
    {
        return $this->events;
    }
}
