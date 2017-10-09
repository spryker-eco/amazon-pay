#!/usr/bin/env bash

cpath=`pwd`
modulePath="$cpath/module"

function runTests {
    echo "Preparing environment..."
    echo "Copying test files to DemoShop folder "
    cp -r vendor/spryker-eco/amazon-pay/tests/Functional/SprykerEco/Zed/Amazonpay tests/PyzTest/Zed/
    echo "Fix namespace of tests..."
    grep -rl ' Functional\\SprykerEco' tests/PyzTest/Zed/Amazonpay/Business/ | xargs sed -i -e 's/ Functional\\SprykerEco/ PyzTest/g'
    echo "Copy configuration..."
    if [ -f vendor/spryker-eco/amazon-pay/config/Shared/config.dist.php ]; then
        tail -n +2 vendor/spryker-eco/amazon-pay/config/Shared/config.dist.php >> config/Shared/config_default-devtest.php
        php "$modulePath/fix-config.php" config/Shared/config_default-devtest.php
    fi
    echo "Setup test environment..."
    vendor/bin/console propel:install
    ./setup_test -f
    echo "Running tests..."
    vendor/bin/codecept run -c tests/PyzTest/Zed/Amazonpay Business
    echo "Done tests"
}

function checkWithLatestDemoShop {
    echo "Checking with latest DemoShop"
    composer config repositories.amazonpay git https://github.com/spryker-eco/AmazonPay.git

    composer require spryker-eco/amazon-pay=dev-feature/ECO-573-per-item-processing
    result=$?
    if [ "$result" = 0 ]; then
        echo "Latest version of module is COMPATIBLE with latest DemoShop modules' versions"

        if runTests; then
            checkModuleWithLatestVersionOfModule
        fi
    else
        echo "Latest version of module is NOT COMPATIBLE with latest DemoShop due to modules' versions"

        checkModuleWithLatestVersionOfModule
    fi
}

function checkModuleWithLatestVersionOfModule {
    echo "Revert composer..."
    git checkout composer.json

    echo "Updating module dependencies..."
    composer config repositories.amazonpay path $modulePath

    echo "Merging dependencies..."
    php "$modulePath/merge-composer.php" "$modulePath/composer.json" composer.json "$modulePath/composer.json"

    echo "Installing module with merged dependencies..."
    composer require "spryker-eco/amazon-pay @dev"
    result=$?
    if [ "$result" = 0 ]; then
        echo "Module is COMPATIBLE with latest versions of modules used in DemoShop"

        runTests
    else
        echo "Module is NOT COMPATIBLE with latest versions of modules used in DemoShop"
    fi
}

# create folder for demoshop
#cd travis

# try installation of eco-module as-is
cd demoshop/
#git checkout composer.json composer.lock config/Shared/config_default-devtest.php
composer install
#vendor/bin/console propel:install
#./setup_test -f
#vendor/bin/codecept run
#exit

checkWithLatestDemoShop
