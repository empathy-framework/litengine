<?php

namespace Empathy\Engine\Wrappers;

use VoidCore;

use Empathy\Engine\{
    Additions,
    Events
};

class NetObject implements \ArrayAccess
{
    protected int $selector;

    public function __construct ($name, $assembly = false, ...$args)
    {
        if (is_int ($name))
            $this->selector = $name;

        elseif (is_string ($name))
            $this->selector = VoidCore::createObject ($name, $assembly,
                ...array_map ('Empathy\Engine\Additions::uncoupleSelector', $args));

        else throw new \Exception ('Incorrect NetObject name passed');
    }

    public function __get (string $name)
    {
        if (method_exists ($this, $method = 'get_'. $name))
            try
            {
                return $this->$method ();
            }

            catch (\WinformsException $e) {}

        if (isset ($this->$name))
            return $this->$name;

        try
        {
            return Additions::coupleSelector ($this->getProperty ($name));
        }

        catch (\WinformsException $e)
        {
            return Additions::coupleSelector ($this->getField ($name));
        }
    }

    public function __set (string $name, $value): void
    {
        if (method_exists ($this, $method = 'set_'. $name))
            $this->$method ($value);
        
        else try
        {
            $this->setProperty ($name, Additions::uncoupleSelector ($value));
        }

        catch (\WinformsException $e)
        {
            try
            {
                $this->setField ($name, Additions::uncoupleSelector ($value));
            }

            catch (\WinformsException $t)
            {
                throw $e;
            }
        }
    }

    public function __call (string $name, array $args)
    {
        return method_exists ($this, $name) ?
            call_user_method ($name, $this, ...$args) :
            Additions::coupleSelector ($this->callMethod ($name, array_map ('Empathy\Engine\Additions::uncoupleSelector', $args)));
    }

    public function on (string $eventName, callable $eventClosure): self
    {
        Events::setEvent ($this->selector, $eventName, $eventClosure);

        return $this;
    }

    public function dispose (): void
    {
        VoidCore::removeObjects ($this->selector);
    }

    protected function get_list ()
    {
        try
        {
            $size = $this->count;
        }

        catch (\WinformsException $e)
        {
            $size = $this->length;
        }

        $list = [];
        
        for ($i = 0; $i < $size; ++$i)
            $list[] = Additions::coupleSelector (VoidCore::getArrayValue ($this->selector, $i));
        
        return $list;
    }
    
    public function offsetExists ($offset): bool
    {
        try
        {
            VoidCore::getArrayValue ($this->selector, $offset);
        }

        catch (\WinformsException $e)
        {
            return false;
        }

        return true;
    }

    public function offsetGet ($offset)
    {
        return Additions::coupleSelector (VoidCore::getArrayValue ($this->selector, $offset));
    }

    public function offsetSet ($offset, $value): void
    {
        if ($offset === null)
            try
            {
                $offset = $this->count;
            }

            catch (\WinformsException $e)
            {
                $offset = $this->length;
            }

        VoidCore::setArrayValue ($this->selector, $offset, Additions::uncoupleSelector ($value));
    }

    public function offsetUnset ($offset): void
    {
        $this->callMethod ('RemoveAt', $offset);
    }

    protected function getProperty ($name)
    {
        return VoidCore::getProperty ($this->selector, $name);
    }

    protected function setProperty (string $name, $value): void
    {
        VoidCore::setProperty ($this->selector, $name, $value);
    }

    protected function getField ($name)
    {
        return VoidCore::getField ($this->selector, $name);
    }

    protected function setField (string $name, $value): void
    {
        VoidCore::setField ($this->selector, $name, $value);
    }

    protected function callMethod (string $name, array $args = [])
    {
        return VoidCore::callMethod ($this->selector, $name, ...$args);
    }
}

class NetClass extends NetObject
{
    public function __construct ($name, $assembly = false)
    {
        if (is_int ($name))
            $this->selector = $name;

        elseif (is_string ($name))
            $this->selector = VoidCore::getClass ($name, $assembly);

        else throw new \Exception ('Incorrect NetClass name passed');
    }
}
