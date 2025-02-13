<?php return array (
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:bVYGhqgyqdfIygLl84tFGtaDT0yTzyOok2tc5pp8cNs=',
    'cipher' => 'AES-256-CBC',
    'maintenance' => 
    array (
      'driver' => 'file',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Intervention\\Image\\ImageServiceProvider',
      23 => 'Spatie\\Permission\\PermissionServiceProvider',
      24 => 'App\\Providers\\AppServiceProvider',
      25 => 'App\\Providers\\AuthServiceProvider',
      26 => 'App\\Providers\\BroadcastServiceProvider',
      27 => 'App\\Providers\\EventServiceProvider',
      28 => 'App\\Providers\\RouteServiceProvider',
      29 => 'plugins\\MenuBuilder\\Providers\\MenuBuilderServiceProvider',
      30 => 'plugins\\FormBuilder\\Providers\\FormBuilderServiceProvider',
      31 => 'plugins\\WidgetBuilder\\Providers\\WidgetBuilderServiceProvider',
      32 => 'Riskihajar\\Terbilang\\TerbilangServiceProvider',
      33 => 'Paytabscom\\Laravel_paytabs\\PaypageServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
      'LogActivity' => 'App\\Helper\\LogActivity',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'Terbilang' => 'Riskihajar\\Terbilang\\Facades\\Terbilang',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'admin' => 
      array (
        'driver' => 'session',
        'provider' => 'admins',
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
      'admins' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\Admin',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'host' => 'api-mt1.pusher.com',
          'port' => '443',
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\Users\\yassi\\planb-backend\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
    ),
    'prefix' => 'laravel_cache_',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'plamb',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'plamb',
        'username' => 'yassin',
        'password' => '5254747Deno',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'plamb',
        'username' => 'yassin',
        'password' => '5254747Deno',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'plamb',
        'username' => 'yassin',
        'password' => '5254747Deno',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'laravel_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\yassi\\planb-backend\\storage\\app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\yassi\\planb-backend\\storage\\app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      'CustomUploader' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\yassi\\planb-backend\\storage\\../../assets/uploads/',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => '',
        'bucket' => '',
        'url' => '',
        'endpoint' => '',
        'use_path_style_endpoint' => false,
      ),
      'wasabi' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => '',
        'bucket' => '',
        'endpoint' => '',
      ),
      'cloudFlareR2' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => '',
        'visibility' => 'private',
        'endpoint' => '',
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\Users\\yassi\\planb-backend\\public\\storage' => 'C:\\Users\\yassi\\planb-backend\\storage\\app/public',
    ),
  ),
  'firebase' => 
  array (
    'default' => 'app',
    'projects' => 
    array (
      'app' => 
      array (
        'credentials' => NULL,
        'auth' => 
        array (
          'tenant_id' => NULL,
        ),
        'firestore' => 
        array (
        ),
        'database' => 
        array (
          'url' => NULL,
        ),
        'dynamic_links' => 
        array (
          'default_domain' => NULL,
        ),
        'storage' => 
        array (
          'default_bucket' => NULL,
        ),
        'cache_store' => 'file',
        'logging' => 
        array (
          'http_log_channel' => NULL,
          'http_debug_log_channel' => NULL,
        ),
        'http_client_options' => 
        array (
          'proxy' => NULL,
          'timeout' => NULL,
          'guzzle_middlewares' => 
          array (
          ),
        ),
      ),
    ),
  ),
  'google2fa' => 
  array (
    'enabled' => true,
    'lifetime' => 0,
    'keep_alive' => true,
    'auth' => 'auth',
    'guard' => '',
    'session_var' => 'google2fa',
    'otp_input' => 'one_time_password',
    'window' => 1,
    'forbid_old_passwords' => false,
    'otp_secret_column' => 'google2fa_secret',
    'view' => 'google2fa.index',
    'error_messages' => 
    array (
      'wrong_otp' => 'The \'One Time Password\' typed was wrong.',
      'cannot_be_empty' => 'One Time Password cannot be empty.',
      'unknown' => 'An unknown error has occurred. Please try again.',
    ),
    'throw_exceptions' => true,
    'qrcode_image_backend' => 'svg',
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'installer' => 
  array (
    'app_name' => 'Xilancer',
    'super_admin_role_id' => 1,
    'admin_model' => 'App\\Models\\Admin',
    'admin_table' => 'admins',
    'multi_tenant' => false,
    'author' => 'xgenious',
    'product_key' => 'bd972f30bc7af2f42f667e2682db42207f62d81f',
    'php_version' => '8.1',
    'extensions' => 
    array (
      0 => 'BCMath',
      1 => 'Ctype',
      2 => 'JSON',
      3 => 'Mbstring',
      4 => 'OpenSSL',
      5 => 'PDO',
      6 => 'pdo_mysql',
      7 => 'Tokenizer',
      8 => 'XML',
      9 => 'cURL',
      10 => 'fileinfo',
    ),
    'website' => 'https://xgenious.com',
    'email' => 'support@xgenious.com',
    'env_example_path' => 'C:\\Users\\yassi\\planb-backend\\public\\env-sample.txt',
    'broadcast_driver' => 'log',
    'cache_driver' => 'file',
    'queue_connection' => 'sync',
    'mail_port' => '587',
    'mail_encryption' => 'tls',
    'model_has_roles' => true,
    'bundle_pack' => false,
    'bundle_pack_key' => 'dsfasd',
  ),
  'invoices' => 
  array (
    'date' => 
    array (
      'format' => 'Y-m-d',
      'pay_until_days' => 7,
    ),
    'serial_number' => 
    array (
      'series' => 'AA',
      'sequence' => 1,
      'sequence_padding' => 5,
      'delimiter' => '.',
      'format' => '{SERIES}{DELIMITER}{SEQUENCE}',
    ),
    'currency' => 
    array (
      'code' => 'eur',
      'fraction' => 'ct.',
      'symbol' => '€',
      'decimals' => 2,
      'decimal_point' => '.',
      'thousands_separator' => '',
      'format' => '{VALUE} {SYMBOL}',
    ),
    'paper' => 
    array (
      'size' => 'a4',
      'orientation' => 'portrait',
    ),
    'disk' => 'local',
    'seller' => 
    array (
      'class' => 'LaravelDaily\\Invoices\\Classes\\Seller',
      'attributes' => 
      array (
        'name' => 'Towne, Smith and Ebert',
        'address' => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
        'code' => '41-1985581',
        'vat' => '123456789',
        'phone' => '760-355-3930',
        'custom_fields' => 
        array (
          'SWIFT' => 'BANK101',
        ),
      ),
    ),
    'dompdf_options' => 
    array (
      'enable_php' => true,
      'logOutputFile' => '/dev/null',
    ),
  ),
  'laravel-translatable-string-exporter' => 
  array (
    'directories' => 
    array (
      0 => 'app',
      1 => 'resources/views',
      2 => 'plugins',
      3 => 'Modules',
    ),
    'excluded-directories' => 
    array (
    ),
    'patterns' => 
    array (
      0 => '*.php',
      1 => '*.js',
    ),
    'allow-newlines' => false,
    'functions' => 
    array (
      0 => '__',
      1 => '_t',
      2 => '@lang',
    ),
    'sort-keys' => true,
    'add-persistent-strings-to-translations' => false,
    'exclude-translation-keys' => false,
    'put-untranslated-strings-at-the-top' => false,
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\Users\\yassi\\planb-backend\\storage\\logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\yassi\\planb-backend\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\Users\\yassi\\planb-backend\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => 'mailhog',
        'port' => '1025',
        'encryption' => NULL,
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Laravel',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\Users\\yassi\\planb-backend\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'role_pivot_key' => NULL,
      'permission_pivot_key' => NULL,
      'model_morph_key' => 'model_id',
      'team_foreign_key' => 'team_id',
    ),
    'register_permission_check_method' => true,
    'teams' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '24 hours',
      )),
      'key' => 'spatie.permission.cache',
      'store' => 'default',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'localhost',
      1 => 'localhost:3000',
      2 => '127.0.0.1',
      3 => '127.0.0.1:8000',
      4 => '::1',
      5 => 'localhost',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'verify_csrf_token' => 'App\\Http\\Middleware\\VerifyCsrfToken',
      'encrypt_cookies' => 'App\\Http\\Middleware\\EncryptCookies',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'facebook' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'redirect' => NULL,
    ),
    'google' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'redirect' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\Users\\yassi\\planb-backend\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\Users\\yassi\\planb-backend\\resources\\views',
    ),
    'compiled' => 'C:\\Users\\yassi\\planb-backend\\storage\\framework\\views',
  ),
  'xgapiclient' => 
  array (
    'base_api_url' => 'https://xls.abdur.xgenious.com/api',
    'has_token' => 'bd972f30bc7af2f42f667e2682db42207f62d81f',
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => 'C:\\Users\\yassi\\planb-backend\\storage\\fonts',
      'font_cache' => 'C:\\Users\\yassi\\planb-backend\\storage\\fonts',
      'temp_dir' => 'C:\\Users\\yassi\\AppData\\Local\\Temp',
      'chroot' => 'C:\\Users\\yassi\\planb-backend',
      'allowed_protocols' => 
      array (
        'file://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'http://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'https://' => 
        array (
          'rules' => 
          array (
          ),
        ),
      ),
      'log_output_file' => NULL,
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_paper_orientation' => 'portrait',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => true,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
  ),
  'purifier' => 
  array (
    'encoding' => 'UTF-8',
    'finalize' => true,
    'ignoreNonStrings' => false,
    'cachePath' => 'C:\\Users\\yassi\\planb-backend\\storage\\app/purifier',
    'cacheFileMode' => 493,
    'settings' => 
    array (
      'default' => 
      array (
        'HTML.Doctype' => 'HTML 4.01 Transitional',
        'HTML.Allowed' => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
        'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty' => true,
      ),
      'test' => 
      array (
        'Attr.EnableID' => 'true',
      ),
      'youtube' => 
      array (
        'HTML.SafeIframe' => 'true',
        'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
      ),
      'custom_definition' => 
      array (
        'id' => 'html5-definitions',
        'rev' => 1,
        'debug' => false,
        'elements' => 
        array (
          0 => 
          array (
            0 => 'section',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          1 => 
          array (
            0 => 'nav',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          2 => 
          array (
            0 => 'article',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          3 => 
          array (
            0 => 'aside',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          4 => 
          array (
            0 => 'header',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          5 => 
          array (
            0 => 'footer',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          6 => 
          array (
            0 => 'address',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
          ),
          7 => 
          array (
            0 => 'hgroup',
            1 => 'Block',
            2 => 'Required: h1 | h2 | h3 | h4 | h5 | h6',
            3 => 'Common',
          ),
          8 => 
          array (
            0 => 'figure',
            1 => 'Block',
            2 => 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow',
            3 => 'Common',
          ),
          9 => 
          array (
            0 => 'figcaption',
            1 => 'Inline',
            2 => 'Flow',
            3 => 'Common',
          ),
          10 => 
          array (
            0 => 'video',
            1 => 'Block',
            2 => 'Optional: (source, Flow) | (Flow, source) | Flow',
            3 => 'Common',
            4 => 
            array (
              'src' => 'URI',
              'type' => 'Text',
              'width' => 'Length',
              'height' => 'Length',
              'poster' => 'URI',
              'preload' => 'Enum#auto,metadata,none',
              'controls' => 'Bool',
            ),
          ),
          11 => 
          array (
            0 => 'source',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'src' => 'URI',
              'type' => 'Text',
            ),
          ),
          12 => 
          array (
            0 => 's',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          13 => 
          array (
            0 => 'var',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          14 => 
          array (
            0 => 'sub',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          15 => 
          array (
            0 => 'sup',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          16 => 
          array (
            0 => 'mark',
            1 => 'Inline',
            2 => 'Inline',
            3 => 'Common',
          ),
          17 => 
          array (
            0 => 'wbr',
            1 => 'Inline',
            2 => 'Empty',
            3 => 'Core',
          ),
          18 => 
          array (
            0 => 'ins',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'cite' => 'URI',
              'datetime' => 'CDATA',
            ),
          ),
          19 => 
          array (
            0 => 'del',
            1 => 'Block',
            2 => 'Flow',
            3 => 'Common',
            4 => 
            array (
              'cite' => 'URI',
              'datetime' => 'CDATA',
            ),
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            0 => 'iframe',
            1 => 'allowfullscreen',
            2 => 'Bool',
          ),
          1 => 
          array (
            0 => 'table',
            1 => 'height',
            2 => 'Text',
          ),
          2 => 
          array (
            0 => 'td',
            1 => 'border',
            2 => 'Text',
          ),
          3 => 
          array (
            0 => 'th',
            1 => 'border',
            2 => 'Text',
          ),
          4 => 
          array (
            0 => 'tr',
            1 => 'width',
            2 => 'Text',
          ),
          5 => 
          array (
            0 => 'tr',
            1 => 'height',
            2 => 'Text',
          ),
          6 => 
          array (
            0 => 'tr',
            1 => 'border',
            2 => 'Text',
          ),
        ),
      ),
      'custom_attributes' => 
      array (
        0 => 
        array (
          0 => 'a',
          1 => 'target',
          2 => 'Enum#_blank,_self,_target,_top',
        ),
      ),
      'custom_elements' => 
      array (
        0 => 
        array (
          0 => 'u',
          1 => 'Inline',
          2 => 'Inline',
          3 => 'Common',
        ),
      ),
    ),
  ),
  'modules' => 
  array (
    'namespace' => 'Modules',
    'stubs' => 
    array (
      'enabled' => false,
      'path' => 'C:\\Users\\yassi\\planb-backend\\vendor/nwidart/laravel-modules/src/Commands/stubs',
      'files' => 
      array (
        'routes/web' => 'routes/web.php',
        'routes/api' => 'routes/api.php',
        'views/index' => 'resources/views/index.blade.php',
        'views/master' => 'resources/views/layouts/master.blade.php',
        'scaffold/config' => 'config/config.php',
        'composer' => 'composer.json',
        'assets/js/app' => 'resources/assets/js/app.js',
        'assets/sass/app' => 'resources/assets/sass/app.scss',
        'vite' => 'vite.config.js',
        'package' => 'package.json',
      ),
      'replacements' => 
      array (
        'routes/web' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'MODULE_NAMESPACE',
          3 => 'CONTROLLER_NAMESPACE',
        ),
        'routes/api' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
        ),
        'vite' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'json' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'MODULE_NAMESPACE',
          3 => 'PROVIDER_NAMESPACE',
        ),
        'views/index' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'views/master' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
        ),
        'scaffold/config' => 
        array (
          0 => 'STUDLY_NAME',
        ),
        'composer' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'VENDOR',
          3 => 'AUTHOR_NAME',
          4 => 'AUTHOR_EMAIL',
          5 => 'MODULE_NAMESPACE',
          6 => 'PROVIDER_NAMESPACE',
        ),
      ),
      'gitkeep' => true,
    ),
    'paths' => 
    array (
      'modules' => 'C:\\Users\\yassi\\planb-backend\\Modules',
      'assets' => 'C:\\Users\\yassi\\planb-backend\\public\\modules',
      'migration' => 'C:\\Users\\yassi\\planb-backend\\database/migrations',
      'generator' => 
      array (
        'config' => 
        array (
          'path' => 'config',
          'generate' => true,
        ),
        'command' => 
        array (
          'path' => 'App/Console',
          'generate' => false,
        ),
        'channels' => 
        array (
          'path' => 'App/Broadcasting',
          'generate' => false,
        ),
        'migration' => 
        array (
          'path' => 'Database/migrations',
          'generate' => false,
        ),
        'seeder' => 
        array (
          'path' => 'Database/Seeders',
          'generate' => true,
        ),
        'factory' => 
        array (
          'path' => 'Database/Factories',
          'generate' => false,
        ),
        'model' => 
        array (
          'path' => 'App/Models',
          'generate' => false,
        ),
        'observer' => 
        array (
          'path' => 'App/Observers',
          'generate' => false,
        ),
        'routes' => 
        array (
          'path' => 'routes',
          'generate' => true,
        ),
        'controller' => 
        array (
          'path' => 'App/Http/Controllers',
          'generate' => true,
        ),
        'filter' => 
        array (
          'path' => 'App/Http/Middleware',
          'generate' => false,
        ),
        'request' => 
        array (
          'path' => 'App/Http/Requests',
          'generate' => false,
        ),
        'provider' => 
        array (
          'path' => 'App/Providers',
          'generate' => true,
        ),
        'assets' => 
        array (
          'path' => 'resources/assets',
          'generate' => false,
        ),
        'lang' => 
        array (
          'path' => 'lang',
          'generate' => false,
        ),
        'views' => 
        array (
          'path' => 'resources/views',
          'generate' => true,
        ),
        'test' => 
        array (
          'path' => 'tests/Unit',
          'generate' => false,
        ),
        'test-feature' => 
        array (
          'path' => 'tests/Feature',
          'generate' => false,
        ),
        'repository' => 
        array (
          'path' => 'App/Repositories',
          'generate' => false,
        ),
        'event' => 
        array (
          'path' => 'App/Events',
          'generate' => false,
        ),
        'listener' => 
        array (
          'path' => 'App/Listeners',
          'generate' => false,
        ),
        'policies' => 
        array (
          'path' => 'App/Policies',
          'generate' => false,
        ),
        'rules' => 
        array (
          'path' => 'App/Rules',
          'generate' => false,
        ),
        'jobs' => 
        array (
          'path' => 'App/Jobs',
          'generate' => false,
        ),
        'emails' => 
        array (
          'path' => 'App/Emails',
          'generate' => false,
        ),
        'notifications' => 
        array (
          'path' => 'App/Notifications',
          'generate' => false,
        ),
        'resource' => 
        array (
          'path' => 'App/resources',
          'generate' => false,
        ),
        'component-view' => 
        array (
          'path' => 'resources/views/components',
          'generate' => false,
        ),
        'component-class' => 
        array (
          'path' => 'App/View/Components',
          'generate' => false,
        ),
      ),
    ),
    'commands' => 
    array (
      0 => 'Nwidart\\Modules\\Commands\\ChannelMakeCommand',
      1 => 'Nwidart\\Modules\\Commands\\CheckLangCommand',
      2 => 'Nwidart\\Modules\\Commands\\CommandMakeCommand',
      3 => 'Nwidart\\Modules\\Commands\\ComponentClassMakeCommand',
      4 => 'Nwidart\\Modules\\Commands\\ComponentViewMakeCommand',
      5 => 'Nwidart\\Modules\\Commands\\ControllerMakeCommand',
      6 => 'Nwidart\\Modules\\Commands\\DisableCommand',
      7 => 'Nwidart\\Modules\\Commands\\DumpCommand',
      8 => 'Nwidart\\Modules\\Commands\\EnableCommand',
      9 => 'Nwidart\\Modules\\Commands\\EventMakeCommand',
      10 => 'Nwidart\\Modules\\Commands\\FactoryMakeCommand',
      11 => 'Nwidart\\Modules\\Commands\\InstallCommand',
      12 => 'Nwidart\\Modules\\Commands\\JobMakeCommand',
      13 => 'Nwidart\\Modules\\Commands\\LaravelModulesV6Migrator',
      14 => 'Nwidart\\Modules\\Commands\\ListCommand',
      15 => 'Nwidart\\Modules\\Commands\\ListenerMakeCommand',
      16 => 'Nwidart\\Modules\\Commands\\MailMakeCommand',
      17 => 'Nwidart\\Modules\\Commands\\MiddlewareMakeCommand',
      18 => 'Nwidart\\Modules\\Commands\\MigrateCommand',
      19 => 'Nwidart\\Modules\\Commands\\MigrateFreshCommand',
      20 => 'Nwidart\\Modules\\Commands\\MigrateRefreshCommand',
      21 => 'Nwidart\\Modules\\Commands\\MigrateResetCommand',
      22 => 'Nwidart\\Modules\\Commands\\MigrateRollbackCommand',
      23 => 'Nwidart\\Modules\\Commands\\MigrateStatusCommand',
      24 => 'Nwidart\\Modules\\Commands\\MigrationMakeCommand',
      25 => 'Nwidart\\Modules\\Commands\\ModelMakeCommand',
      26 => 'Nwidart\\Modules\\Commands\\ModelPruneCommand',
      27 => 'Nwidart\\Modules\\Commands\\ModelShowCommand',
      28 => 'Nwidart\\Modules\\Commands\\ModuleDeleteCommand',
      29 => 'Nwidart\\Modules\\Commands\\ModuleMakeCommand',
      30 => 'Nwidart\\Modules\\Commands\\NotificationMakeCommand',
      31 => 'Nwidart\\Modules\\Commands\\ObserverMakeCommand',
      32 => 'Nwidart\\Modules\\Commands\\PolicyMakeCommand',
      33 => 'Nwidart\\Modules\\Commands\\ProviderMakeCommand',
      34 => 'Nwidart\\Modules\\Commands\\PublishCommand',
      35 => 'Nwidart\\Modules\\Commands\\PublishConfigurationCommand',
      36 => 'Nwidart\\Modules\\Commands\\PublishMigrationCommand',
      37 => 'Nwidart\\Modules\\Commands\\PublishTranslationCommand',
      38 => 'Nwidart\\Modules\\Commands\\RequestMakeCommand',
      39 => 'Nwidart\\Modules\\Commands\\ResourceMakeCommand',
      40 => 'Nwidart\\Modules\\Commands\\RouteProviderMakeCommand',
      41 => 'Nwidart\\Modules\\Commands\\RuleMakeCommand',
      42 => 'Nwidart\\Modules\\Commands\\SeedCommand',
      43 => 'Nwidart\\Modules\\Commands\\SeedMakeCommand',
      44 => 'Nwidart\\Modules\\Commands\\SetupCommand',
      45 => 'Nwidart\\Modules\\Commands\\TestMakeCommand',
      46 => 'Nwidart\\Modules\\Commands\\UnUseCommand',
      47 => 'Nwidart\\Modules\\Commands\\UpdateCommand',
      48 => 'Nwidart\\Modules\\Commands\\UseCommand',
    ),
    'scan' => 
    array (
      'enabled' => false,
      'paths' => 
      array (
        0 => 'C:\\Users\\yassi\\planb-backend\\vendor/*/*',
      ),
    ),
    'composer' => 
    array (
      'vendor' => 'nwidart',
      'author' => 
      array (
        'name' => 'Nicolas Widart',
        'email' => 'n.widart@gmail.com',
      ),
      'composer-output' => false,
    ),
    'cache' => 
    array (
      'enabled' => false,
      'driver' => 'file',
      'key' => 'laravel-modules',
      'lifetime' => 60,
    ),
    'register' => 
    array (
      'translations' => true,
      'files' => 'register',
    ),
    'activators' => 
    array (
      'file' => 
      array (
        'class' => 'Nwidart\\Modules\\Activators\\FileActivator',
        'statuses-file' => 'C:\\Users\\yassi\\planb-backend\\modules_statuses.json',
        'cache-key' => 'activator.installed',
        'cache-lifetime' => 604800,
      ),
    ),
    'activator' => 'file',
  ),
  'chunk-upload' => 
  array (
    'storage' => 
    array (
      'chunks' => 'chunks',
      'disk' => 'local',
    ),
    'clear' => 
    array (
      'timestamp' => '-3 HOURS',
      'schedule' => 
      array (
        'enabled' => true,
        'cron' => '25 * * * *',
      ),
    ),
    'chunk' => 
    array (
      'name' => 
      array (
        'use' => 
        array (
          'session' => true,
          'browser' => false,
        ),
      ),
    ),
    'handlers' => 
    array (
      'custom' => 
      array (
      ),
      'override' => 
      array (
      ),
    ),
  ),
  'terbilang' => 
  array (
    'output' => 
    array (
      'date' => '{DAY} {MONTH} {YEAR}',
      'time' => '{HOUR} {SEPARATOR} {MINUTE} {MINUTE_LABEL} {SECOND} {SECOND_LABEL}',
    ),
    'locale' => 'en',
    'use_intl' => true,
    'distance' => 
    array (
      'type' => 
      \Riskihajar\Terbilang\Enums\DistanceDate::Day,
      'template' => '{YEAR} {MONTH} {DAY} {HOUR} {MINUTE} {SECOND}',
      'hide_zero_value' => true,
      'separator' => ' ',
      'terbilang' => false,
      'show' => 
      array (
        'year' => true,
        'month' => true,
        'day' => true,
        'hour' => true,
        'minute' => true,
        'second' => true,
      ),
    ),
  ),
  'flare' => 
  array (
    'key' => NULL,
    'flare_middleware' => 
    array (
      0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
      1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
      2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
      3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
      4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
      5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => 
      array (
        'maximum_number_of_collected_logs' => 200,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => 
      array (
        'maximum_number_of_collected_queries' => 200,
        'report_query_bindings' => true,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => 
      array (
        'max_chained_job_reporting_depth' => 5,
      ),
      6 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddContext',
      7 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionHandledStatus',
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => 
      array (
        'censor_fields' => 
        array (
          0 => 'password',
          1 => 'password_confirmation',
        ),
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => 
      array (
        'headers' => 
        array (
          0 => 'API-KEY',
          1 => 'Authorization',
          2 => 'Cookie',
          3 => 'Set-Cookie',
          4 => 'X-CSRF-TOKEN',
          5 => 'X-XSRF-TOKEN',
        ),
      ),
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'auto',
    'enable_share_button' => true,
    'register_commands' => false,
    'solution_providers' => 
    array (
      0 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\BadMethodCallSolutionProvider',
      1 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\MergeConflictSolutionProvider',
      2 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\UndefinedPropertySolutionProvider',
      3 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\IncorrectValetDbCredentialsSolutionProvider',
      4 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingAppKeySolutionProvider',
      5 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\DefaultDbNameSolutionProvider',
      6 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\TableNotFoundSolutionProvider',
      7 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingImportSolutionProvider',
      8 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\InvalidRouteActionSolutionProvider',
      9 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\ViewNotFoundSolutionProvider',
      10 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\RunningLaravelDuskInProductionProvider',
      11 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingColumnSolutionProvider',
      12 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownValidationSolutionProvider',
      13 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingMixManifestSolutionProvider',
      14 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingViteManifestSolutionProvider',
      15 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingLivewireComponentSolutionProvider',
      16 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UndefinedViewVariableSolutionProvider',
      17 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\GenericLaravelExceptionSolutionProvider',
      18 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\OpenAiSolutionProvider',
      19 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\SailNetworkSolutionProvider',
      20 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMysql8CollationSolutionProvider',
      21 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMariadbCollationSolutionProvider',
    ),
    'ignored_solution_providers' => 
    array (
    ),
    'enable_runnable_solutions' => NULL,
    'remote_sites_path' => 'C:\\Users\\yassi\\planb-backend',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
    'settings_file_path' => '',
    'recorders' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\Recorders\\DumpRecorder\\DumpRecorder',
      1 => 'Spatie\\LaravelIgnition\\Recorders\\JobRecorder\\JobRecorder',
      2 => 'Spatie\\LaravelIgnition\\Recorders\\LogRecorder\\LogRecorder',
      3 => 'Spatie\\LaravelIgnition\\Recorders\\QueryRecorder\\QueryRecorder',
    ),
    'open_ai_key' => NULL,
    'with_stack_frame_arguments' => true,
    'argument_reducers' => 
    array (
      0 => 'Spatie\\Backtrace\\Arguments\\Reducers\\BaseTypeArgumentReducer',
      1 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ArrayArgumentReducer',
      2 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StdClassArgumentReducer',
      3 => 'Spatie\\Backtrace\\Arguments\\Reducers\\EnumArgumentReducer',
      4 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ClosureArgumentReducer',
      5 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeArgumentReducer',
      6 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeZoneArgumentReducer',
      7 => 'Spatie\\Backtrace\\Arguments\\Reducers\\SymphonyRequestArgumentReducer',
      8 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\ModelArgumentReducer',
      9 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\CollectionArgumentReducer',
      10 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StringableArgumentReducer',
    ),
  ),
  'paytabs' => 
  array (
    'profile_id' => NULL,
    'server_key' => NULL,
    'currency' => NULL,
    'region' => NULL,
  ),
  'blog' => 
  array (
    'name' => 'Blog',
  ),
  'chat' => 
  array (
    'name' => 'Chat',
  ),
  'countrymanage' => 
  array (
    'name' => 'CountryManage',
  ),
  'emailtemplate' => 
  array (
    'name' => 'EmailTemplate',
  ),
  'faq' => 
  array (
    'name' => 'Faq',
  ),
  'freelancerchatcontroller' => 
  array (
    'name' => 'FreelancerChatController',
  ),
  'generalsettings' => 
  array (
    'name' => 'GeneralSettings',
  ),
  'integrations' => 
  array (
    'name' => 'Integrations',
  ),
  'newsletter' => 
  array (
    'name' => 'NewsLetter',
  ),
  'pages' => 
  array (
    'name' => 'Pages',
  ),
  'paymentgatewaysettings' => 
  array (
    'name' => 'PaymentGatewaySettings',
  ),
  'pluginmanage' => 
  array (
    'name' => 'PluginManage',
  ),
  'rolepermission' => 
  array (
    'name' => 'RolePermission',
  ),
  'service' => 
  array (
    'name' => 'Service',
  ),
  'subscription' => 
  array (
    'name' => 'Subscription',
  ),
  'supportticket' => 
  array (
    'name' => 'SupportTicket',
  ),
  'wallet' => 
  array (
    'name' => 'Wallet',
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
