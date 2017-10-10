#!/usr/bin/env bash

cpath=`pwd`
modulePath="$cpath/module"
globalResult=1
message=""

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
    if [ "$?" = 0 ]; then
        newMessage=$'\nTests are green'
        message="$message$newMessage"
    else
        newMessage=$'\nTests are failing'
        message="$message$newMessage"
    fi
    echo "Done tests"
}

function checkWithLatestDemoShop {
    echo "Checking module with latest DemoShop"
    composer config repositories.amazonpay path $modulePath

    composer require "spryker-eco/amazon-pay @dev"
    result=$?
    if [ "$result" = 0 ]; then
        newMessage=$'\nCurrent version of module is COMPATIBLE with latest DemoShop modules\' versions'
        message="$message$newMessage"

        if runTests; then
            globalResult=0

            checkModuleWithLatestVersionOfDemoshop
        fi
    else
        newMessage=$'\nCurrent version of module is NOT COMPATIBLE with latest DemoShop due to modules\' versions'
        message="$message$newMessage"

        checkModuleWithLatestVersionOfDemoshop
    fi
}

function checkModuleWithLatestVersionOfDemoshop {
    echo "Merging composer.json dependencies..."
    updates=`php "$modulePath/merge-composer.php" "$modulePath/composer.json" composer.json "$modulePath/composer.json"`
    newMessage=$'\nUpdated dependencies in module to match DemoShop\n'
    message="$message$newMessage$updates"

    echo "Installing module with updated dependencies..."
    composer require "spryker-eco/amazon-pay @dev"
    result=$?
    if [ "$result" = 0 ]; then
        newMessage=$'\nModule is COMPATIBLE with latest versions of modules used in DemoShop'
        message="$message$newMessage"

        runTests
    else
        newMessage=$'\nModule is NOT COMPATIBLE with latest versions of modules used in DemoShop'
        message="$message$newMessage"
    fi
}

# create folder for demoshop
#cd travis

# try installation of eco-module as-is
cd demoshop/
#git checkout composer.json composer.lock config/Shared/config_default-devtest.php
composer install

checkWithLatestDemoShop

echo "$message"
exit $globalResult