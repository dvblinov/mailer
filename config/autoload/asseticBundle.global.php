<?php

return array(
	'service_manager' => array(
		'invokables' => array(
			'AsseticBundle\CacheBuster' => 'AsseticBundle\CacheBuster\Null',
		),
	),
	'assetic_configuration' => array(
		'debug'        => false,
		'cacheEnabled' => false,
		'cachePath'    => __DIR__ . '/../../data/cache',
		'webPath'      => __DIR__ . '/../../public/assets',
		'basePath'     => 'assets',
		'routes'       => array(
		),
	)
);