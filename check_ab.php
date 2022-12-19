#!/bin/php
<?php

// https://github.com/righter83/checkmk-synology-activebackup
// v1.1

// open DBs
$dbt=new SQLite3("/volume1/@ActiveBackup/config.db");
$dbj=new SQLite3("/volume1/@ActiveBackup/activity.db");

// configs
$runtime=86500;
$runtimehuman=gmdate("H:i:s", $runtime);
$runtimecheck=True;
$tsnow=Time();
$now=date("d.m.Y H:m", $tsnow);
$exit_error=0;

// get configure tasks
$task=$dbt->query("select task_id,task_name from task_table");
while($tasks=$task->fetchArray())
{
	$error=0;
	// get last job of each task
	$job=$dbj->query("select * from result_table where task_id=$tasks[task_id] and job_action=1 order by result_id desc limit 1");
	$jobs=$job->fetchArray();
	$start=date("d.m.Y H:m", $jobs['time_start']);

	// check if job started < runtime
	if (($tsnow-$runtime) > $jobs['time_start'] && $runtimecheck)
	{
		$out.="ERROR: $jobs[task_name] was not running inside runtime window (Now: $now, Last Start: $start, Not older as:. $runtimehuman ) -- ";
		$error=2;
                $exit_error=2;
	}

	// check if job is running
	if ($jobs['status'] == 1)
	{
		$out.="OK: $jobs[task_name] is running -- ";
		continue;
	}

	//warnings
	if ($jobs['status'] == 3)
	{
		$out.="WARN: $jobs[task_name] had Warnings -- ";
		$error=1;
                $exit_error=1;
	}

	// errors
	if ($jobs['error_count'] > 0)
        {
                $out.="ERROR: $jobs[task_name] had an Error -- ";
                $error=2;
                $exit_error=2;
        }
	if ($error == 0)
	{
		$out.="OK: $jobs[task_name] ran successfully -- ";
	}

}

echo $out;
exit($exit_error);
