<?php

namespace Iset\Event;


interface IDispatcher
{
  /**
   * Attaches a listener to event
   *
   * @param string|Event $event the event to attach too
   * @param callable $callback a callable function
   * @param bool $is_shared global event
   *
   * @return bool true on success false on failure
   */
  public function attach($event, $callback, $priority = 0, $is_shared = false);

  /**
   * Detaches a listener from an event
   *
   * @param string $event the event to attach too
   * @param callable $callback a callable function
   * @return bool true on success false on failure
   */
  public function detach($event, $callback, $is_shared = false);

  /**
   * Clear all listeners for a given event
   *
   * @param string $event
   * @param bool $is_shared global event
   * @return void
   */
  public function clearListeners($event, $is_shared = false);


  /**
   * Trigger an event
   *
   * Can accept an EventInterface or will create one if not passed
   *
   * @param string|Event $event
   * @param object|string $target
   * @param array|object $argv
   * @param bool $is_shared global event
   * @return mixed
   */
  public function trigger($event, $target = null, $argv = [], $is_shared = false);

  /**
   * Has this event indicated event propagation should stop?
   *
   * @return bool
   */
  public function isPropagationStopped();

  /**
   * Indicate whether or not to stop propagating all events
   *
   * @param bool $flag
   */
  public function stopPropagation($flag = false);

}