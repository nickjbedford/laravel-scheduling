<?php
	namespace YetAnother\Tests;
	
	use Illuminate\Console\Scheduling\Schedule;
	use Illuminate\Support\Collection;
	use Orchestra\Testbench\Foundation\Console\Kernel;
	use YetAnother\Laravel\Console\ScheduledTask;
	use YetAnother\Laravel\Console\TaskScheduler;
	
	class TestKernel extends Kernel
	{
		public static ?TaskScheduler $taskScheduler = null;
		public static string|ScheduledTask|array|Collection|null $autoSchedule = null;
		
		protected function schedule(Schedule $schedule): void
		{
			self::$taskScheduler?->scheduleAll($schedule);
			
			if (self::$autoSchedule)
				TaskScheduler::scheduleTasks($schedule, self::$autoSchedule);
		}
	}
