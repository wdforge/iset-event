<?php

namespace Iset\Event;


interface IEventable
{
  public function getEventManager();

  public function setEventManager(\Iset\Event\IDispatcher $dispatcher);
}