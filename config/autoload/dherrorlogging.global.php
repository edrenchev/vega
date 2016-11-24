<?php
/**
 * DhErrorLogging Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */

$config = [

    /**
     * Set if error logging should be enabled.
     */
    'enabled' => true,

    /**
     * Set what error types should be logged
     */
    'error_types' => [
        // Exceptions (those other than within dispatch or render phase)
        'exceptions' => true,
        // Native PHP errors (those other than within dispatch or render phase)
        'errors' => true,
        // Dispatch errors, triggered in case of a problem or exception anywhere during dispatch lifecycle (unknown controller, exception thrown inside of controller,...)
        'dispatch' => true,
        // Router no match  (route not found = 404).
        'dispatch\router_no_match' => true,
        // Render errors, triggered in case of a problem during the render process (no renderer found...).
        'render' => true,
        // Fatal errors that halt execution of further code
        'fatal' => true,
    ],
    /**
     * filter out some of the exception types (i.e. \Exception\UnauthorizedException)
     */
    'exception_filter' => [
        '\Exception\UnauthorizedException' // 403
    ],

    /**
     * Levels of errors which will show the nice error view defined in template under [templates][error] section
     */
    'displayable_error_levels' => E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT,

   /**
    * Set writers to be used.
    * You can either add new config array for some of the the standard writers that don't need injection of other objects
    * (stream, chromephp, 'fingerscrossed', 'firephp', 'mail', 'mock', 'null', 'syslog', 'zendmonitor')
    * or identifier of registered log writer factory (registered in general config section ['log_writers']).
    */
    'log_writers' => [

        /**
         * Writing logs into file
         *
         * Make sure the specified file exists and it is writable
         */
        'stream' => [
            'name' => 'stream',
            'options' => [
                'stream' => 'data/log/error.log', // make sure you create this file and it is writable.
                'log_separator' => "\n"
            ],
        ],

        /**
         * Recording logs into database
         *
         * Make sure and have table "error_log" with correct schema in your database
         */
         'db' => [
           'name' => 'DhErrorLogging\DbWriter',
           'options' => array(
               'table_name' => 'error_log',
               'table_map' => array(
                   'timestamp' => 'creation_time',
                   'type' => 'type',
                   'priorityName' => 'priority',
                   'message' => 'message',
                   'reference'  => 'reference',
                   'file'  => 'file',
                   'line'  => 'line',
                   'trace' => 'trace',
                   'xdebug' => 'xdebug',
                   'uri' => 'uri',
                   'request' => 'request',
                   'ip' => 'ip',
                   'session_id' => 'session_id'
                )
             )
            ],

        /**
         * Reporting logs via email
         */
//        'mail' => [
//            'name' => 'mail',
//            'options' => [
//                /*
//                 * Subject Prepend Text
//                 */
//                'subject_prepend_text' => 'Error Log',
//                /*
//                 * Mail Options
//                 * @see Zend\Mail\Message
//                 */
//                'mail' => [
//                    'from' => 'vega@vega.drenchev.com',
//                    'to' => 'edrenchev@gmail.com',
//                ],
//                /*
//                 * Transport
//                 *       - sendmail  => Zend\Mail\Transport\Sendmail,
//                 *       - smtp      => Zend\Mail\Transport\Smtp,
//                 *
//                 * Default transport is smtp
//                 */
//                'transport' => [
//                    'type' => 'smtp',
//
//                    /*
//                     * Transport Options
//                     * @see Zend\Mail\Transport\SmtpOptions
//                     */
//                    'options' => [
//                        'host' => 'mail.vega.drenchev.com',
//                        'connection_class' => 'login',
//                        'connection_config' => [
//                            'username' => 'vega@vega.drenchev.com',
//                            'smtp_password' => '123456',
//                            'ssl' => '',
//                            'port' => 26
//                        ]
//                    ],
//                ]
//            ]
//        ]
        /*
         * Your own log writer here
         */

    ],

    /**
     * Paths of templates to be used for output of errors
     */

    'templates' => array(
         // error during dispatch lifecycle. comment out if you want to use the default zend skeleton one.
         'dispatch' => __DIR__ . '/../../module/Application/view/error/error.html',
         // error during render lifecycle. comment out if you want to use the default zend skeleton one.
         'render' => __DIR__ . '/../../module/Application/view/error/error.html',
         // exceptions outside of mvc (i.e. while in module bootstrap,..)
         'exception' => __DIR__ . '/../../module/Application/view/error/error.html',
         // native errors while outside of mvc (i.e. while in module bootstrap,..)
         'error' => __DIR__ . '/../../module/Application/view/error/error.html',
         // fatal errors while outside of mvc (i.e. while in module bootstrap,..)
         'fatal' => __DIR__ . '/../../module/Application/view/error/error.html',
    ),


    /**
     * Zend\Db\Adapter\Adapter DI Alias
     *
     * Please specify the DI alias for the configured Zend\Db\Adapter\Adapter
     * instance that DhErrorLogging should use. Applicable only if you want to log into database via Zend Db.
     */
     //'zend_db_adapter' => 'Zend\Db\Adapter\Adapter',

];


if($_SERVER['APPLICATION_ENV'] == 'development') {
	$config['enabled'] = false;
}

return [
    'dherrorlogging' => $config,

	'view_helpers' => [
		'invokables' => [
			'translate' => \Zend\I18n\View\Helper\Translate::class
		]
	],

    'service_manager' => [
        'aliases' => [
            // set db adapter
            'dherrorlogging_zend_db_adapter' => (isset($config['zend_db_adapter'])) ? $config['zend_db_adapter']: 'Zend\Db\Adapter\Adapter',
        ],
    ]
];
