<?php
	
	namespace YetAnother\Laravel\Console;
	
	use Closure;
	use Illuminate\Console\Scheduling\Schedule;
	
	/**
	 * Represents a scheduled task to be executed by the Laravel scheduler.
	 */
	abstract class ScheduledTask
	{
		/**
		 * Determines if the task can be executed. If true, then the schedule()
		 * method will be called to register the task with the scheduler.
		 */
		function canExecute(): bool
		{
			return true;
		}

		/**
		 * Schedules the task with the Laravel scheduler.
		 * @param Closure $callback The callback used to execute scheduled task.
		 * @param Schedule $schedule The schedule to register the task with.
		 */
		abstract function schedule(Schedule $schedule, Closure $callback): void;
		
		/**
		 * This is called before the task has been executed. This may be overridden to provide additional
		 * pre-execution functionality outside each individual task.
		 */
		function beforeExecution(): void { }

		/**
		 * Executes the task if it has been scheduled.
		 */
		abstract function execute(): void;
		
		/**
		 * This is called after the task has been executed. This may be overridden to provide additional
		 * post-execution functionality outside each individual task.
		 */
		function afterExecution(): void { }
	}
