#!/usr/bin/env bash

RED='\033[1;31m'
GREEN='\033[1;32m'
moduleNiceName='Amazonpay'
modulePath="$TRAVIS_BUILD_DIR/$MODULE_DIR"
scriptPath="$TRAVIS_BUILD_DIR/$MODULE_DIR/build"
globalResult=1
buildMessage=""

function runTests {
    echo "Preparing environment..."
    echo "Copying test files to DemoShop folder "
    cp -r "vendor/spryker-eco/$MODULE_NAME/tests/Functional/SprykerEco/Zed/$moduleNiceName" tests/PyzTest/Zed/

    echo "Fix namespace of tests..."
    grep -rl ' Functional\\SprykerEco' "tests/PyzTest/Zed/$moduleNiceName/Business/" | xargs sed -i -e 's/ Functional\\SprykerEco/ PyzTest/g'

    echo "Copy configuration..."
    if [ -f "vendor/spryker-eco/$MODULE_NAME/config/Shared/config.dist.php" ]; then
        tail -n +2 "vendor/spryker-eco/$MODULE_NAME/config/Shared/config.dist.php" >> config/Shared/config_default-devtest.php
        php "$scriptPath/fix-config.php" config/Shared/config_default-devtest.php
    fi

    echo "Setup test environment..."
    ./setup_test -f

    echo "Running tests..."
    vendor/bin/codecept run -c "tests/PyzTest/Zed/$moduleNiceName" Business
    if [ "$?" = 0 ]; then
        buildMessage="$buildMessage\n${GREEN}Tests are green"
        result=0
    else
        buildMessage="$buildMessage\n${RED}Tests are failing"
        result=1
    fi

    echo "Tests finished"
    return $result
}

function checkWithLatestDemoShop {
    echo "Checking module with latest DemoShop"
    composer config repositories.ecomodule path $modulePath

    composer require "spryker-eco/$MODULE_NAME @dev"
    result=$?
    if [ "$result" = 0 ]; then
        buildMessage="$buildMessage\n${GREEN}$MODULE_NAME is compatible with the modules used in Demo Shop"

        if runTests; then
            globalResult=0

            checkModuleWithLatestVersionOfDemoshop
        fi
    else
        buildMessage="$buildMessage\n${RED}$MODULE_NAME is not compatible with the modules used in Demo Shop"

        checkModuleWithLatestVersionOfDemoshop
    fi
}

function checkModuleWithLatestVersionOfDemoshop {
    echo "Merging composer.json dependencies..."
    updates=`php "$scriptPath/merge-composer.php" "$modulePath/composer.json" composer.json "$modulePath/composer.json"`

    if [ "$updates" = "" ]; then
        buildMessage="$buildMessage\n${GREEN}$MODULE_NAME is already using the latest versions of modules used in Demo Shop"
        return
    fi

    buildMessage="$buildMessage\nUpdated dependencies in module to match DemoShop\n$updates"

    echo "Installing module with updated dependencies..."
    composer require "spryker-eco/$MODULE_NAME @dev"
    result=$?
    if [ "$result" = 0 ]; then
        buildMessage="$buildMessage\n${GREEN}$MODULE_NAME is COMPATIBLE with latest versions of modules used in Demo Shop"

        runTests
    else
        buildMessage="$buildMessage\n${RED}$MODULE_NAME is NOT COMPATIBLE with latest versions of modules used in Demo Shop"
    fi
}

cd $SHOP_DIR
checkWithLatestDemoShop
echo -e "$buildMessage"
exit $globalResult
