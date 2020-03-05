<?php

namespace think\permission\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class PermissionPublish extends Command
{
	protected function configure()
	{
		$this->setName('permission-publish')
			 ->setDescription('Publish Permission Files');
	}

	protected function execute(Input $input, Output $output)
	{
		$output->write(sprintf('permission config file publish %s' . PHP_EOL, $this->publishConfig() ? 'successfully' : 'failed'));

		$output->write(sprintf('permission migrations publish %s',$this->publishMigrations() ? 'successfully' : 'failed'));
	}

	/**
	 * publish config
	 *
	 * @return bool
	 */
	protected function publishConfig()
	{
		$permissionConfigPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'permission.php';

		return copy($permissionConfigPath, app()->getConfigPath() . 'permission.php');
	}

	/**
	 * publish migrations
	 *
	 * @return bool
	 */
	protected function publishMigrations()
	{
		$migrationsPath = app()->getRootPath() . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
		if (!is_dir($migrationsPath)) {
			mkdir($migrationsPath, 0777, true);
		}
		$databasePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR;
		$handle = opendir($databasePath);
		$status = true;
		while ( ($file = readdir($handle))!= false) {
			if ($file != '.' && $file != '..') {
				if (!copy($databasePath . basename($file), $migrationsPath . basename($file))) {
					$status = false;
					break;
				}
			}
		}
		return $status;
	}
}