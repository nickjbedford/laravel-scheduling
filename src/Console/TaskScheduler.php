<?php
	
	namespace YetAnother\Laravel\Console;
	
	use Illuminate\Console\Scheduling\Schedule;
	use Illuminate\Support\Collection;
	use InvalidArgumentException;
	
	/**
	 * Represents the scheduler of multiple ScheduledTask classes.
	 */
	class TaskScheduler
	{
		/** @var ScheduledTask[] $tasks */
		private array $tasks = [];
		
		/**
		 * Adds one or more tasks to be scheduled.
		 * @param string|string[]|ScheduledTask|ScheduledTask[]|Collection $tasks A single, array or collection of
		 * ScheduledTask instances, ScheduledTask class names to instantiate to be scheduled.
		 */
		function add(string|ScheduledTask|array|Collection $tasks): static
		{
			if (is_string($tasks))
				$tasks = new $tasks();
			
			if ($tasks instanceof ScheduledTask)
			{
				$this->tasks[] = $tasks;
				return $this;
			}
			
			if (!is_array($tasks) && !($tasks instanceof Collection))
				throw new InvalidArgumentException("\$tasks parameter must be a string (ScheduledTask class name), ScheduledTask instance, array or Collection of either.");
			
			foreach($tasks as $task)
			{
				$this->add($task);
			}
			return $this;
		}
		
		/**
		 * Removes a task from being scheduled.
		 * @param ScheduledTask|string $task The task instance or a class name to remove all instances of.
		 */
		function remove(ScheduledTask|string $task): bool
		{
			foreach($this->tasks as $index=>$thisTask)
			{
				if ($task instanceof ScheduledTask)
				{
					if ($task === $thisTask)
					{
						unset($this->tasks[$index]);
						return true;
					}
				}
				else if ($task === get_class($thisTask))
				{
					unset($this->tasks[$index]);
					return true;
				}
			}
			return false;
		}
		
		/**
		 * Schedules all tasks with the Laravel schedule.
		 * @param Schedule $schedule The schedule to register the tasks with.
		 */
		function scheduleAll(Schedule $schedule): void
		{
			foreach($this->tasks as $task)
			{
				$task = is_string($task) ? new $task() : $task;
				
				if ($task->canExecute())
				{
					$task->schedule($schedule, function () use ($task)
					{
						$task->beforeExecution();
						$task->execute();
						$task->afterExecution();
					});
				}
			}
		}
	}
