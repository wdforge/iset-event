<?php

namespace Iset\EventManager;

class AbstractListner
{
  protected $_listners = [];
  protected $_eventmanager;

  public function __construct(EventManagerInterface $eventmanager)
  {
    $this->_eventmanager = $eventmanager;
  }

  public function attach($eventname, $callable)
  {
    $this->_listners[$eventname] = $callable;
    $this->_eventmanager->attach($eventname, $callable);
  }

  public function detach($eventname)
  {
    unset($this->_listners[$eventname]);
    $this->_eventmanager->detach($eventname);
  }

  public function trigger($eventname)
  {
    $this->_eventmanager->trigger($eventname);
  }

}