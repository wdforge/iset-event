<?php

namespace Iset\Event;

use Iset\Event\IDispatcher;
use Iset\Event\IEventable;

class Event implements IEventable, IEvent
{
  protected $_target;
  protected $_name;
  protected $_manager;
  protected $_islock = false;
  protected $_result = [];
  protected $_listeners = [];

  public function __construct($name, IDispatcher $manager)
  {
    $this->setEventManager($manager);
    $this->setName($name);
  }

  /**
   * set listener result
   *
   * @param integer $position
   * @param mixed $result
   *
   * @return none
   **/
  public function setResult($position = 0, $result)
  {
    $this->_result[$position] = $result;
  }

  /**
   * get listener result
   *
   * @param integer $position
   *
   * @return mixed
   **/
  public function getResult($position = 0)
  {
    return $this->_result[$position];
  }


  /**
   * get all listener results
   *
   * @return mixed
   **/
  public function getResults()
  {
    return $this->_result;
  }

  /**
   * set listener
   *
   * @param callable $listener
   * @param integer $priority
   *
   * @return mixed
   **/
  public function addListner($listener, $priority = 0)
  {
    if (!$this->_islock) {
      if (isset($this->_listeners[$priority]) || empty($priority)) {
        $this->_listeners[] = $listener;
      } elseif ($priority && !isset($this->_callback[$priority])) {
        $this->_listeners[$priority] = $listener;
      }
    }
  }

  /**
   * get all listeners
   *
   * @param none
   *
   * @return callable[]
   **/
  public function getListeners()
  {
    return $this->_listeners;
  }

  /**
   * Indicate whether or not to stop propagating this event
   *
   * @param bool $flag
   */
  public function stopPropagation($flag = false)
  {
    $this->getEventManager()->stopPropagation($flag);
  }

  public function clearListenerList()
  {
    $this->_listeners = [];
  }

  function __invoke()
  {
    $this->_islock = true;
    $params = func_get_args();
    if ($this->getEventManager()->isPropagationStopped()) {
      foreach ($this->getListeners() as $position => $listener) {
        if ($this->getEventManager()->isPropagationStopped()) {
          $result = call_user_func_array($listener, [['params' => $params, 'event' => $this]]);
          $this->setResult($position, $result);
        }
      }
    }
  }

  public function trigger($params = [])
  {
    $this->_islock = true;
    if ($this->getEventManager()->isPropagationStopped()) {
      foreach ($this->getListeners() as $position => $listener) {
        if ($this->getEventManager()->isPropagationStopped()) {
          $result = call_user_func_array($listener, [['params' => $params, 'event' => $this]]);
          $this->setResult($position, $result);
        }
      }
    }
  }

  /**
   * Set the event target
   *
   * @param null|string|object $target
   * @return void
   */
  public function setTarget($target)
  {
    $this->_target = $target;
  }

  /**
   * Get target/context from which event was triggered
   *
   * @return null|string|object
   */
  public function getTarget()
  {
    return $this->_target;
  }

  /**
   * Get event manager
   *
   * @return EventManager
   */
  public function getEventManager()
  {
    return $this->_manager;
  }

  /**
   * Set the event name
   *
   * @param string $name
   * @return void
   */
  public function setName($name)
  {
    $this->_name = $name;
  }

  /**
   * Get event name
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }

  /**
   * Set event manager
   *
   * @param EventManager $manager
   */
  public function setEventManager(IDispatcher $manager)
  {
    $this->_manager = $manager;
  }
}
