<?php
	namespace YetAnother\Tests;
	
	use Closure;
	use Illuminate\Console\Scheduling\Schedule;
	use YetAnother\Laravel\Console\ScheduledTask;
	
	class TestScheduledTask extends ScheduledTask
	{
		private Closure $closure;
		
		public bool $enabled;
		
		function __construct(Closure $closure, bool $enabled = true)
		{
			$this->closure = $closure;
			$this->enabled = $enabled;
		}
		
		public function canExecute(): bool
		{
			return $this->enabled;
		}
		
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
			call_user_func($this->closure, $this);
		}
	}
