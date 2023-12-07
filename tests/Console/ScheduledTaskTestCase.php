<?php
	namespace YetAnother\Tests\Console;
	
	use Exception;
	use Illuminate\Contracts\Console\Kernel;
	use Illuminate\Support\Facades\Artisan;
	use Orchestra\Testbench\TestCase;
	use YetAnother\Laravel\Console\TaskScheduler;
	use YetAnother\Tests\TestKernel;
	use YetAnother\Tests\TestMidnightTask;
	use YetAnother\Tests\TestScheduledAssertingTask;
	use YetAnother\Tests\TestScheduledTask;
	
	class ScheduledTaskTestCase extends TestCase
	{
		protected function setUp(): void
		{
			TestScheduledAssertingTask::$testCase = $this;
			TestScheduledAssertingTask::$ran = false;
			
			parent::setUp();
			$this->app->singleton(Kernel::class, TestKernel::class);
		}
		
		function testScheduledTaskIsScheduled()
		{
			$scheduler = new TaskScheduler();
			$scheduler->add([
				new TestScheduledTask(function(TestScheduledTask $task)
				{
					$this->assertTrue($task->enabled);
				}),
				new TestScheduledTask(function()
				{
					throw new Exception('This task should not be executed.');
				}, enabled: false)
			]);
			
			TestKernel::$taskScheduler = $scheduler;
			
			Artisan::call('schedule:run');
		}
		
		function testScheduledTaskIsScheduledUsingClassName()
		{
			$scheduler = new TaskScheduler();
			$scheduler->add(TestScheduledAssertingTask::class);
			
			TestKernel::$taskScheduler = $scheduler;
			
			Artisan::call('schedule:run');
			
			$this->assertTrue(TestScheduledAssertingTask::$ran);
		}
		
		function testScheduledTaskIsNotScheduledAtOtherTimesThanMidnight()
		{
			$value = 1;
			
			$scheduler = new TaskScheduler();
			$scheduler->add(new TestMidnightTask(function() use(&$value)
			{
				$value = "MIDNIGHT";
			}));
			
			TestKernel::$taskScheduler = $scheduler;
			
			$time = date('H:i:s');
			Artisan::call('schedule:run');
			
			if ($time !== '00:00:00')
				$this->assertSame(1, $value);
			else
				$this->assertSame("MIDNIGHT", $value);
		}
	}
