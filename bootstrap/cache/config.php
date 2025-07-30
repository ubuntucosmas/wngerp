<?php return array (
  2 => 'broadcasting',
  4 => 'concurrency',
  5 => 'cors',
  8 => 'hashing',
  14 => 'view',
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'production',
    'debug' => true,
    'url' => '',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:fBew7t0j29dyQv/58FzHin1qghGdwN7I2k/3YpzOvvs=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Concurrency\\ConcurrencyServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      11 => 'Illuminate\\Hashing\\HashServiceProvider',
      12 => 'Illuminate\\Mail\\MailServiceProvider',
      13 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      15 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      16 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      17 => 'Illuminate\\Queue\\QueueServiceProvider',
      18 => 'Illuminate\\Redis\\RedisServiceProvider',
      19 => 'Illuminate\\Session\\SessionServiceProvider',
      20 => 'Illuminate\\Translation\\TranslationServiceProvider',
      21 => 'Illuminate\\Validation\\ValidationServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\ViewServiceProvider',
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
      'Concurrency' => 'Illuminate\\Support\\Facades\\Concurrency',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Context' => 'Illuminate\\Support\\Facades\\Context',
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
      'Schedule' => 'Illuminate\\Support\\Facades\\Schedule',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Uri' => 'Illuminate\\Support\\Uri',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
    0 => 'App\\Providers\\ViewServiceProvider',
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
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'backup' => 
  array (
    'backup' => 
    array (
      'destination' => 
      array (
        'compression_method' => -1,
        'compression_level' => 9,
        'filename_prefix' => '',
        'disks' => 
        array (
          0 => 'local',
        ),
      ),
      'name' => 'Laravel',
      'source' => 
      array (
        'files' => 
        array (
          'include' => 
          array (
            0 => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal',
          ),
          'exclude' => 
          array (
            0 => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\vendor',
            1 => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\node_modules',
          ),
          'follow_links' => false,
          'ignore_unreadable_directories' => false,
          'relative_path' => NULL,
        ),
        'databases' => 
        array (
          0 => 'mysql',
        ),
      ),
      'database_dump_compressor' => NULL,
      'database_dump_file_timestamp_format' => NULL,
      'database_dump_filename_base' => 'database',
      'database_dump_file_extension' => '',
      'temporary_directory' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\app/backup-temp',
      'password' => NULL,
      'encryption' => 'default',
      'tries' => 1,
      'retry_delay' => 0,
    ),
    'notifications' => 
    array (
      'notifications' => 
      array (
        'Spatie\\Backup\\Notifications\\Notifications\\BackupHasFailedNotification' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\UnhealthyBackupWasFoundNotification' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupHasFailedNotification' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\BackupWasSuccessfulNotification' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\HealthyBackupWasFoundNotification' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupWasSuccessfulNotification' => 
        array (
          0 => 'mail',
        ),
      ),
      'notifiable' => 'Spatie\\Backup\\Notifications\\Notifiable',
      'mail' => 
      array (
        'to' => 'your@example.com',
        'from' => 
        array (
          'address' => 'cosmasasango12@gmail.com',
          'name' => 'Laravel',
        ),
      ),
      'slack' => 
      array (
        'webhook_url' => '',
        'channel' => NULL,
        'username' => NULL,
        'icon' => NULL,
      ),
      'discord' => 
      array (
        'webhook_url' => '',
        'username' => '',
        'avatar_url' => '',
      ),
    ),
    'monitor_backups' => 
    array (
      0 => 
      array (
        'name' => 'Laravel',
        'disks' => 
        array (
          0 => 'local',
        ),
        'health_checks' => 
        array (
          'Spatie\\Backup\\Tasks\\Monitor\\HealthChecks\\MaximumAgeInDays' => 1,
          'Spatie\\Backup\\Tasks\\Monitor\\HealthChecks\\MaximumStorageInMegabytes' => 5000,
        ),
      ),
    ),
    'cleanup' => 
    array (
      'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
      'default_strategy' => 
      array (
        'keep_all_backups_for_days' => 7,
        'keep_daily_backups_for_days' => 16,
        'keep_weekly_backups_for_weeks' => 8,
        'keep_monthly_backups_for_months' => 4,
        'keep_yearly_backups_for_years' => 2,
        'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
      ),
      'tries' => 1,
      'retry_delay' => 0,
    ),
  ),
  'cache' => 
  array (
    'default' => 'database',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'cache',
        'lock_connection' => NULL,
        'lock_table' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\framework/cache/data',
        'lock_path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\framework/cache/data',
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
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'portal',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'busy_timeout' => NULL,
        'journal_mode' => NULL,
        'synchronous' => NULL,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'portal',
        'username' => 'root',
        'password' => '',
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
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'portal',
        'username' => 'root',
        'password' => '',
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
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'portal',
        'username' => 'root',
        'password' => '',
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
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'portal',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 
    array (
      'table' => 'migrations',
      'update_date_on_publish' => true,
    ),
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'laravel_database_',
        'persistent' => false,
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
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\app/private',
        'serve' => true,
        'throw' => false,
        'report' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\app/public',
        'url' => '/storage',
        'visibility' => 'public',
        'throw' => false,
        'report' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
        'report' => false,
      ),
      'backups' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\app/backups',
      ),
    ),
    'links' => 
    array (
      'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\public\\storage' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\app/public',
    ),
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
        'path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
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
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'formatter' => NULL,
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\logs/laravel.log',
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
        'scheme' => NULL,
        'url' => NULL,
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'username' => 'cosmasasango12@gmail.com',
        'password' => 'wjzh sxcz oalw eyiv',
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
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
        'retry_after' => 60,
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
    ),
    'from' => 
    array (
      'address' => 'cosmasasango12@gmail.com',
      'name' => 'Laravel',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\resources\\views/vendor/mail',
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
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => 'Spatie\\Permission\\DefaultTeamResolver',
    'use_passport_client_credentials' => false,
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
    'defaults' => 
    array (
      'guard' => 'web',
    ),
  ),
  'project_phases' => 
  array (
    0 => 
    array (
      'title' => 'Client Engagement & Briefing',
      'offsetStart' => 0,
      'offsetEnd' => 5,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Receive Client Brief',
          'description' => 'Capture client needs via email, call, or physical visit.',
          'deliverables' => 
          array (
            0 => 'Customer Service captures client needs.',
            1 => 'Assign a Project Officer (PO).',
            2 => 'Log new project entry in system.',
          ),
        ),
        1 => 
        array (
          'name' => 'Analyze Requirements',
          'description' => 'Review and allocate project internally.',
          'deliverables' => 
          array (
            0 => 'Team leads and PO review client brief.',
            1 => 'Allocate project to relevant departments.',
            2 => 'Schedule internal project briefing.',
          ),
        ),
        2 => 
        array (
          'name' => 'Confirm Project Scope',
          'description' => 'Align with client on deliverables and expectations.',
          'deliverables' => 
          array (
            0 => 'Document project deliverables.',
            1 => 'Share requirements summary for client confirmation.',
            2 => 'Use official communication channels for confirmation.',
          ),
        ),
      ),
    ),
    1 => 
    array (
      'title' => 'Design & Concept Development',
      'offsetStart' => 6,
      'offsetEnd' => 15,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Initial Design Creation',
          'description' => 'Create and share initial design concepts.',
          'deliverables' => 
          array (
            0 => 'Designer creates initial concepts.',
            1 => 'Share internally and with client.',
            2 => 'Collect feedback via email or portal.',
          ),
        ),
        1 => 
        array (
          'name' => 'Final Design Approval',
          'description' => 'Refine and approve final design.',
          'deliverables' => 
          array (
            0 => 'Incorporate revisions from feedback.',
            1 => 'Client provides sign-off.',
            2 => 'Document final designs in ERP.',
          ),
        ),
        2 => 
        array (
          'name' => 'Material & Cost Listing',
          'description' => 'Estimate material needs and costs.',
          'deliverables' => 
          array (
            0 => 'List all required materials.',
            1 => 'Rough cost estimation.',
            2 => 'Finalize and approve materials list internally.',
          ),
        ),
      ),
    ),
    2 => 
    array (
      'title' => 'Quotation & Budget Approval',
      'offsetStart' => 21,
      'offsetEnd' => 25,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Budget Confirmation',
          'description' => 'Validate cost and prepare client quotation.',
          'deliverables' => 
          array (
            0 => 'Cross-check cost with scope.',
            1 => 'Generate and send quotation.',
          ),
        ),
        1 => 
        array (
          'name' => 'Approval & TAT',
          'description' => 'Follow up and confirm client approval.',
          'deliverables' => 
          array (
            0 => 'Follow up within 45 minutes (or as needed).',
            1 => 'Confirm client approval.',
            2 => 'Mark status as “Quote Approved”.',
          ),
        ),
      ),
    ),
    3 => 
    array (
      'title' => 'Procurement & Inventory Management',
      'offsetStart' => 16,
      'offsetEnd' => 20,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Inventory Check',
          'description' => 'Ensure necessary stock is available.',
          'deliverables' => 
          array (
            0 => 'Store manager checks available stock.',
          ),
        ),
        1 => 
        array (
          'name' => 'Procurement Process',
          'description' => 'Raise and track procurement of materials.',
          'deliverables' => 
          array (
            0 => 'Raise purchase request.',
            1 => 'Approve via Procurement Officer.',
            2 => 'Track supplier delivery status.',
          ),
        ),
        2 => 
        array (
          'name' => 'Inventory Ready for Production',
          'description' => 'Prepare materials for use.',
          'deliverables' => 
          array (
            0 => 'Receive and verify materials.',
            1 => 'Notify production team.',
          ),
        ),
      ),
    ),
    4 => 
    array (
      'title' => 'Production',
      'offsetStart' => 26,
      'offsetEnd' => 30,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Execute Production',
          'description' => 'Fabricate/brand as per approved design.',
          'deliverables' => 
          array (
            0 => 'Log time and material usage.',
          ),
        ),
        1 => 
        array (
          'name' => 'Quality Control',
          'description' => 'Ensure deliverables meet standards.',
          'deliverables' => 
          array (
            0 => 'QA team inspects output.',
            1 => 'Retouch if needed.',
            2 => 'Approve for delivery.',
          ),
        ),
        2 => 
        array (
          'name' => 'Packing & Handover for Setup',
          'description' => 'Prepare items for delivery.',
          'deliverables' => 
          array (
            0 => 'Package final items.',
            1 => 'Update delivery docket.',
            2 => 'Handover to logistics.',
          ),
        ),
      ),
    ),
    5 => 
    array (
      'title' => 'Event Setup & Execution',
      'offsetStart' => 31,
      'offsetEnd' => 35,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Site Delivery',
          'description' => 'Transport and confirm safe arrival of items.',
          'deliverables' => 
          array (
            0 => 'Load and transport items to venue.',
            1 => 'Confirm arrival and condition.',
          ),
        ),
        1 => 
        array (
          'name' => 'Setup Execution',
          'description' => 'Install and test setup on-site.',
          'deliverables' => 
          array (
            0 => 'Install branding/equipment as per design.',
            1 => 'Test all components.',
            2 => 'Confirm readiness with client.',
          ),
        ),
      ),
    ),
    6 => 
    array (
      'title' => 'Client Handover & Feedback',
      'offsetStart' => 41,
      'offsetEnd' => 43,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Final Handover',
          'description' => 'Wrap up project and submit final report.',
          'deliverables' => 
          array (
            0 => 'Submit final project report.',
            1 => 'Provide soft copies or photos.',
          ),
        ),
        1 => 
        array (
          'name' => 'Feedback Collection',
          'description' => 'Collect feedback and assess satisfaction.',
          'deliverables' => 
          array (
            0 => 'Debrief session with client.',
            1 => 'Record satisfaction and lessons learned.',
          ),
        ),
      ),
    ),
    7 => 
    array (
      'title' => 'Set Down & Return',
      'offsetStart' => 36,
      'offsetEnd' => 40,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Dismantling',
          'description' => 'Safely uninstall and collect materials.',
          'deliverables' => 
          array (
            0 => 'Uninstall props/equipment.',
            1 => 'Account for all items.',
          ),
        ),
        1 => 
        array (
          'name' => 'Returns & Storage',
          'description' => 'Return items to workshop and update records.',
          'deliverables' => 
          array (
            0 => 'Transport items back.',
            1 => 'Inspect for damage.',
            2 => 'Update return condition.',
          ),
        ),
      ),
    ),
    8 => 
    array (
      'title' => 'Archival & Reporting',
      'offsetStart' => 44,
      'offsetEnd' => 45,
      'default_tasks' => 
      array (
        0 => 
        array (
          'name' => 'Close Project',
          'description' => 'Mark project complete and archive.',
          'deliverables' => 
          array (
            0 => 'Mark Project as complete.',
            1 => 'Archive all related documentation.',
          ),
        ),
        1 => 
        array (
          'name' => 'Analytics & Reports',
          'description' => 'Generate insights for management review.',
          'deliverables' => 
          array (
            0 => 'Create cost, time, and material usage reports.',
            1 => 'Send summary to management.',
          ),
        ),
      ),
    ),
  ),
  'project_process_phases' => 
  array (
    0 => 
    array (
      'name' => 'Client Engagement & Briefing',
      'icon' => 'bi-folder-symlink',
      'summary' => 'Initial client meetings, project briefs, and requirements gathering. This phase sets the foundation for the entire project.',
      'status' => 'Not Started',
    ),
    1 => 
    array (
      'name' => 'Design & Concept Development',
      'icon' => 'bi-brush',
      'summary' => 'Creative development, mood boards, and initial design concepts. This is where ideas take visual form.',
      'status' => 'Not Started',
    ),
    2 => 
    array (
      'name' => 'Project Material List',
      'icon' => 'bi-list-task',
      'summary' => 'Detailed list of all materials required for the project. Includes specifications, quantities, and procurement status.',
      'status' => 'Not Started',
    ),
    3 => 
    array (
      'name' => 'Budget & Quotation',
      'icon' => 'bi-cash-coin',
      'summary' => 'Financial planning, cost estimation, and client quotations. Tracks project expenses and budget allocations.',
      'status' => 'Not Started',
    ),
    4 => 
    array (
      'name' => 'Production',
      'icon' => 'bi-gear',
      'summary' => 'Manufacturing and assembly of project components. Tracks work orders, progress, and quality control.',
      'status' => 'Not Started',
    ),
    5 => 
    array (
      'name' => 'Logistics',
      'icon' => 'bi-truck',
      'summary' => 'Coordination of transportation, storage, and delivery of project materials and equipment.',
      'status' => 'Not Started',
    ),
    6 => 
    array (
      'name' => 'Event Setup & Execution',
      'icon' => 'bi-tools',
      'summary' => 'On-site setup and implementation. Includes installation schedules and site reports.',
      'status' => 'Not Started',
    ),
    7 => 
    array (
      'name' => 'Client Handover',
      'icon' => 'bi-clipboard-check',
      'summary' => 'Final delivery to client. Includes training, documentation, and sign-off procedures.',
      'status' => 'Not Started',
    ),
    8 => 
    array (
      'name' => 'Set Down & Return',
      'icon' => 'bi-arrow-return-left',
      'summary' => 'Post-event activities, including equipment return and storage.',
      'status' => 'Not Started',
    ),
    9 => 
    array (
      'name' => 'Archival & Reporting',
      'icon' => 'bi-archive',
      'summary' => 'Final project review, documentation, and lessons learned. Formally closes out the project.',
      'status' => 'Not Started',
    ),
  ),
  'queue' => 
  array (
    'default' => 'database',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
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
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'roles' => 
  array (
    'roles' => 
    array (
      0 => 'super-admin',
      1 => 'admin',
      2 => 'hr',
      3 => 'pm',
      4 => 'po',
      5 => 'design',
      6 => 'finance',
      7 => 'store',
      8 => 'user',
      9 => 'production',
    ),
    'permissions' => 
    array (
      0 => 'manage users',
      1 => 'view users',
      2 => 'assign roles',
      3 => 'create project',
      4 => 'update project',
      5 => 'delete project',
      6 => 'view project',
      7 => 'assign project',
      8 => 'manage store',
      9 => 'create stock',
      10 => 'update stock',
      11 => 'delete stock',
      12 => 'view stock',
      13 => 'view stock reports',
      14 => 'submit outcome report',
      15 => 'approve outcome report',
      16 => 'access project module',
      17 => 'access store module',
      18 => 'access user module',
      19 => 'access reports module',
    ),
    'role_permissions' => 
    array (
      'super-admin' => 
      array (
        0 => '*',
      ),
      'admin' => 
      array (
        0 => 'access user module',
        1 => 'access project module',
        2 => 'access store module',
        3 => 'access reports module',
        4 => 'manage users',
        5 => 'view users',
        6 => 'assign roles',
        7 => 'create project',
        8 => 'update project',
        9 => 'view project',
        10 => 'assign project',
        11 => 'create stock',
        12 => 'update stock',
        13 => 'delete stock',
        14 => 'view stock',
        15 => 'view stock reports',
        16 => 'approve outcome report',
      ),
      'pm' => 
      array (
        0 => 'access project module',
        1 => 'create project',
        2 => 'update project',
        3 => 'view project',
        4 => 'assign project',
        5 => 'submit outcome report',
      ),
      'po' => 
      array (
        0 => 'access project module',
        1 => 'view project',
        2 => 'submit outcome report',
      ),
      'store' => 
      array (
        0 => 'access store module',
        1 => 'manage store',
        2 => 'create stock',
        3 => 'update stock',
        4 => 'delete stock',
        5 => 'view stock',
        6 => 'view stock reports',
      ),
      'user' => 
      array (
        0 => 'access project module',
        1 => 'view project',
      ),
    ),
  ),
  'scout' => 
  array (
    'driver' => 'database',
    'prefix' => '',
    'queue' => false,
    'after_commit' => false,
    'chunk' => 
    array (
      'searchable' => 500,
      'unsearchable' => 500,
    ),
    'soft_delete' => false,
    'identify' => false,
    'algolia' => 
    array (
      'id' => '',
      'secret' => '',
      'index-settings' => 
      array (
      ),
    ),
    'meilisearch' => 
    array (
      'host' => 'http://localhost:7700',
      'key' => NULL,
      'index-settings' => 
      array (
      ),
    ),
    'typesense' => 
    array (
      'client-settings' => 
      array (
        'api_key' => 'xyz',
        'nodes' => 
        array (
          0 => 
          array (
            'host' => 'localhost',
            'port' => '8108',
            'path' => '',
            'protocol' => 'http',
          ),
        ),
        'nearest_node' => 
        array (
          'host' => 'localhost',
          'port' => '8108',
          'path' => '',
          'protocol' => 'http',
        ),
        'connection_timeout_seconds' => 2,
        'healthcheck_interval_seconds' => 30,
        'num_retries' => 3,
        'retry_interval_seconds' => 1,
      ),
      'model-settings' => 
      array (
      ),
    ),
  ),
  'services' => 
  array (
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
    'resend' => 
    array (
      'key' => NULL,
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'database',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\framework/sessions',
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
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'telescope' => 
  array (
    'enabled' => true,
    'domain' => NULL,
    'path' => 'telescope',
    'driver' => 'database',
    'storage' => 
    array (
      'database' => 
      array (
        'connection' => 'mysql',
        'chunk' => 1000,
      ),
    ),
    'queue' => 
    array (
      'connection' => NULL,
      'queue' => NULL,
      'delay' => 10,
    ),
    'middleware' => 
    array (
      0 => 'web',
      1 => 'Laravel\\Telescope\\Http\\Middleware\\Authorize',
    ),
    'only_paths' => 
    array (
    ),
    'ignore_paths' => 
    array (
      0 => 'livewire*',
      1 => 'nova-api*',
      2 => 'pulse*',
    ),
    'ignore_commands' => 
    array (
    ),
    'watchers' => 
    array (
      'Laravel\\Telescope\\Watchers\\BatchWatcher' => true,
      'Laravel\\Telescope\\Watchers\\CacheWatcher' => 
      array (
        'enabled' => true,
        'hidden' => 
        array (
        ),
        'ignore' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ClientRequestWatcher' => true,
      'Laravel\\Telescope\\Watchers\\CommandWatcher' => 
      array (
        'enabled' => true,
        'ignore' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\DumpWatcher' => 
      array (
        'enabled' => true,
        'always' => false,
      ),
      'Laravel\\Telescope\\Watchers\\EventWatcher' => 
      array (
        'enabled' => true,
        'ignore' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ExceptionWatcher' => true,
      'Laravel\\Telescope\\Watchers\\GateWatcher' => 
      array (
        'enabled' => true,
        'ignore_abilities' => 
        array (
        ),
        'ignore_packages' => true,
        'ignore_paths' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\JobWatcher' => true,
      'Laravel\\Telescope\\Watchers\\LogWatcher' => 
      array (
        'enabled' => true,
        'level' => 'error',
      ),
      'Laravel\\Telescope\\Watchers\\MailWatcher' => true,
      'Laravel\\Telescope\\Watchers\\ModelWatcher' => 
      array (
        'enabled' => true,
        'events' => 
        array (
          0 => 'eloquent.*',
        ),
        'hydrations' => true,
      ),
      'Laravel\\Telescope\\Watchers\\NotificationWatcher' => true,
      'Laravel\\Telescope\\Watchers\\QueryWatcher' => 
      array (
        'enabled' => true,
        'ignore_packages' => true,
        'ignore_paths' => 
        array (
        ),
        'slow' => 100,
      ),
      'Laravel\\Telescope\\Watchers\\RedisWatcher' => true,
      'Laravel\\Telescope\\Watchers\\RequestWatcher' => 
      array (
        'enabled' => true,
        'size_limit' => 64,
        'ignore_http_methods' => 
        array (
        ),
        'ignore_status_codes' => 
        array (
        ),
      ),
      'Laravel\\Telescope\\Watchers\\ScheduleWatcher' => true,
      'Laravel\\Telescope\\Watchers\\ViewWatcher' => true,
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
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
  'concurrency' => 
  array (
    'default' => 'process',
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
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => '12',
      'verify' => true,
      'limit' => NULL,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
    'rehash_on_login' => true,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\resources\\views',
    ),
    'compiled' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\framework\\views',
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\fonts',
      'font_cache' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\fonts',
      'temp_dir' => 'C:\\Users\\Facility\\AppData\\Local\\Temp',
      'chroot' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal',
      'allowed_protocols' => 
      array (
        'data://' => 
        array (
          'rules' => 
          array (
          ),
        ),
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
      'artifactPathValidation' => NULL,
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
      'enable_remote' => false,
      'allowed_remote_hosts' => NULL,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'strict_null_comparison' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
        'output_encoding' => '',
        'test_auto_detect' => true,
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'ignore_empty' => false,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => NULL,
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'guess',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
      'cells' => 
      array (
        'middleware' => 
        array (
        ),
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'cache' => 
    array (
      'driver' => 'memory',
      'batch' => 
      array (
        'memory_limit' => 60000,
      ),
      'illuminate' => 
      array (
        'store' => NULL,
      ),
      'default_ttl' => 10800,
    ),
    'transactions' => 
    array (
      'handler' => 'db',
      'db' => 
      array (
        'connection' => NULL,
      ),
    ),
    'temporary_files' => 
    array (
      'local_path' => 'C:\\Users\\Facility\\Desktop\\New folder\\portal\\portal\\storage\\framework/cache/laravel-excel',
      'local_permissions' => 
      array (
      ),
      'remote_disk' => NULL,
      'remote_prefix' => NULL,
      'force_resync_remote' => NULL,
    ),
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
