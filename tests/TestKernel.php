<?php
	namespace YetAnother\Tests;
	
	use Illuminate\Console\Scheduling\Schedule;
	use Orchestra\Testbench\Foundation\Console\Kernel;
	use YetAnother\Laravel\Console\TaskScheduler;
	
	class TestKernel extends Kernel
	{
		public static TaskScheduler $taskScheduler;
		
		protected function schedule(Schedule $schedule): void
		{
			self::$taskScheduler->scheduleAll($schedule);
		}
	}
