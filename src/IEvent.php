<?php

namespace Iset\Event;


interface IEvent
{
  public function setResult($position = 0, $result);

  public function getResult($position = 0);

  public function getResults();

  public function addListner($listener, $priority = 0);

  public function getListeners();

  public function stopPropagation($flag = false);

  public function clearListenerList();

  public function trigger($params = []);

  public function setTarget($target);

  public function getTarget();

  public function setName($name);

  public function getName();
}
