<?php
/**
 * Copy this configuration file info config/autoload/zfcdatagrid.local.php
 * Then it will override the default settings and you can use your own!
 */
return array(
    'ZfcDatagrid' => array(
        'settings' => array(
            'default' => array(
                'renderer' => array(
                    'http' => 'bootstrapTable',
                )
            ),
        ),
    )
);