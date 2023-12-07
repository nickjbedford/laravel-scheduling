<?php
	namespace YetAnother\Tests;
	
	use Closure;
	use Illuminate\Console\Scheduling\Schedule;
	use YetAnother\Laravel\Console\ScheduledTask;
	use YetAnother\Tests\Console\ScheduledTaskTestCase;
	
	class TestScheduledAssertingTask extends ScheduledTask
	{
		public static ?ScheduledTaskTestCase $testCase = null;
		public static bool $ran = false;
		
		/**
		 * @inheritDoc
		 */
		function schedule(Schedule $schedule, Closure $callback): void
		{
			$schedule->call($callback)
			         ->everyMinute();
		}
		
		/**
		 * @inheritDoc
		 */
		function execute(): void
		{
			self::$testCase->assertTrue(true);
			self::$ran = true;
		}
	}
