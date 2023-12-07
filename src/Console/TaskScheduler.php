<?php
	
	namespace YetAnother\Laravel\Console;
	
	use Illuminate\Console\Scheduling\Schedule;
	
	/**
	 * Represents the scheduler of multiple ScheduledTask classes.
	 */
	class TaskScheduler
	{
		/** @var ScheduledTask[] $tasks */
		private array $tasks = [];
		
		/**
		 * Adds one or more tasks to be scheduled.
		 * @param ScheduledTask|ScheduledTask[] $tasks The task instance to schedule, or an array of instances to schedule.
		 */
		function add(ScheduledTask|array $tasks): static
		{
			if ($tasks instanceof ScheduledTask)
			{
				$this->tasks[] = $tasks;
				return $this;
			}
			
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
