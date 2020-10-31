<?php

namespace Iset\Event;

use Iset\Event\Event;

use Iset\Utils\IInitial;
use Iset\Utils\IParams;

class Manager implements IInitial, IDispatcher
{
  protected static $_shared_events;
  protected $_events = [];
  protected static $_propagation = true;

  public function init(IParams $params)
  {
    //...
  }

  /**
   * @param string|IEvent
   * @param callable
   * @param bool
   *
   * @return bool
   */
  public function attach($event, $callback, $priority = 0, $is_shared = false)
  {
    if ($is_shared) {
      if ($event && !is_object($event) && is_scalar($event)) {
        if (!isset(self::$_shared_events[$event])) {
          self::$_shared_events[$event] = new Event($event, $this);
        }
        if ($callback) {
          self::$_shared_events[$event]->addListner($callback, $priority);
        }
      } elseif ($event && is_object($event) && $event instanceof IEvent) {
        if ($callback) {
          $event->addListner($callback, $priority);
        }
        self::$_shared_events[$event->getName()] = $event;
      }
    } else {
      if ($event && !is_object($event) && is_scalar($event)) {
        if (!isset($this->_events[$event])) {
          $this->_events[$event] = new Event($event, $this);
        }
        if ($callback) {
          $this->_events[$event]->addListner($callback, $priority);
        }
      } elseif ($event && is_object($event) && $event instanceof IEvent) {
        if ($callback) {
          $event->addListner($callback, $priority);
        }
        $this->_events[$event->getName()] = $event;
      }
    }

    return true;
  }

  /**
   *
   * @param string
   * @param callable
   * @return bool
   */
  public function detach($event, $callback, $is_shared = false)
  {
    // find and kik select callback
    // self::$_shared_events[$event]
    // $this->_events[$event]
    return true;
  }


  /**
   * @param string
   * @param bool
   * @return void
   */
  public function clearListeners($event, $is_shared = false)
  {
    if (!$is_shared) {
      if (isset($this->_events[$event])) {
        unset($this->_events[$event]);
      }
    } else {
      self::$_shared_events = [];
    }

    return true;
  }

  /**
   * @param string|Event
   * @param object|string
   * @param array|object
   * @param bool
   * @return mixed
   */
  public function trigger($event, $target = null, $argv = [], $is_shared = false)
  {
    $eventItem = null;
    if ($event && !is_object($event) && is_scalar($event)) {
      if (isset($this->_events[$event])) {
        $eventItem = $is_shared ? self::$_shared_events[$event] : $this->_events[$event];
      }
    } elseif ($event && is_object($event) && $event instanceof Event) {
      $eventItem = $event;
    }

    if ($eventItem) {
      $eventItem->setTarget($target);
      $eventItem->trigger($argv);
      return $eventItem->getResults();
    }
  }

  /**
   * @return bool
   */
  public function isPropagationStopped()
  {
    return self::$_propagation;
  }

  /**
   *
   * @param bool
   */
  public function stopPropagation($flag = false)
  {
    self::$_propagation = $flag;
  }
}
