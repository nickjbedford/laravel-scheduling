<?php
	namespace YetAnother\Tests;
	
	use Closure;
	use Illuminate\Console\Scheduling\Schedule;
	
	class TestMidnightTask extends TestScheduledTask
	{
		/**
		 * @inheritDoc
		 */
		function schedule(Schedule $schedule, Closure $callback): void
		{
			$schedule->call($callback)
			         ->at('00:00');
		}
	}
