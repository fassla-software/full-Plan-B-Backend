<?php

return [
    'app_name' => 'Xilancer',
    'super_admin_role_id' => 1,
    'admin_model' => \App\Models\Admin::class,
    'admin_table' => 'admins',
    'multi_tenant' => false,
    'author' => 'xgenious',
    'product_key' => 'bd972f30bc7af2f42f667e2682db42207f62d81f',
    'php_version' => '8.1',
    'extensions' => ['BCMath', 'Ctype', 'JSON', 'Mbstring', 'OpenSSL', 'PDO', 'pdo_mysql', 'Tokenizer', 'XML', 'cURL', 'fileinfo'],
    'website' => 'https://xgenious.com',
    'email' => 'support@xgenious.com',
    'env_example_path' => public_path('env-sample.txt'),
    'broadcast_driver' => 'log',
    'cache_driver' => 'file',
    'queue_connection' => 'sync',
    'mail_port' => '587',
    'mail_encryption' => 'tls',
    'model_has_roles' => true,
    'bundle_pack' => false,
    'bundle_pack_key' => 'dsfasd',
];