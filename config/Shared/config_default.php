<?php

use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Propel\PropelConstants;

$config[KernelConstants::SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker';
$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED;
$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';
$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerEco',
    'Spryker',
];

$config[PropelConstants::ZED_DB_ENGINE] = '';
$config[PropelConstants::ZED_DB_USERNAME] = '';
$config[PropelConstants::ZED_DB_PASSWORD] = '';
$config[PropelConstants::PROPEL] = [
    'database' => [
        'connections' =>  [
            'default' => [
                'dsn' => 'dsn',
            ],
        ],
    ],
];
