#!/bin/bash

run_group() {
    printf "\n\n\033[32;49;1m=== Run $1 ===\033[39;49;0m\n\n"
}

run_group "Reg,Activate"
phpunit-bpc --bootstrap=bootstrap.php --group reg,activate --bpc=. .

run_group "BaseDbTablesInit"
phpunit-bpc --bootstrap=bootstrap.php --group BaseDbTablesInit --bpc=. .

run_group "Others"
phpunit-bpc --bootstrap=bootstrap.php --exclude-group reg,activate,BaseDbTablesInit --bpc=. .
