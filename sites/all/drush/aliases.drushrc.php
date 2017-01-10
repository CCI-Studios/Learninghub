<?php

$aliases['dev'] = array(
	'uri'=> 'staging.learninghub.ca',
	'root' => '/home/learni11/subdomains/dev/public_html',
	'remote-host'=> 'learninghub.ca',
	'remote-user'=> 'learni11',
	'path-aliases'=> array(
		'%files'=> 'sites/default/files',
	),

	'ssh-options' => '-p 27'
);

$aliases['live'] = array(
	'uri'=> 'learninghub.ca',
	'root' => '/home/learni11/public_html',
	'remote-host'=> 'learninghub.ca',
	'remote-user'=> 'learni11',
	'path-aliases'=> array(
		'%files'=> 'sites/default/files',
	),

	'ssh-options' => '-p 27'
);