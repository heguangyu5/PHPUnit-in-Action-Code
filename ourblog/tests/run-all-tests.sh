#!/bin/bash

run_group() {
    printf "\n\n\033[32;49;1m=== Run $1 ===\033[39;49;0m\n\n"
}

run_group "Reg,Activate"
phpunit --group reg,activate

run_group "BaseDbTablesInit"
phpunit --group BaseDbTablesInit

run_group "Others"
phpunit --exclude-group reg,activate,BaseDbTablesInit
