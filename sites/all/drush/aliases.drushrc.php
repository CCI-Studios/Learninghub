<?php

$aliases['dev'] = array(
	'uri'=> 'learninghub.ccistaging.com',
	'root' => '/home/staging/subdomains/learninghub/public_html',
	'remote-host'=> 'host.ccistudios.com',
	'remote-user'=> 'staging',
	'path-aliases'=> array(
		'%files'=> 'sites/default/files',
	),

	'ssh-options' => '-p 37241'
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