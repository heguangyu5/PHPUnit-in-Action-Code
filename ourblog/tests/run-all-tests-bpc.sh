#!/bin/bash

run_group() {
    printf "\n\n\033[32;49;1m=== Run $1 ===\033[39;49;0m\n\n"
}

run_group "Reg,Activate"
./run-test --bootstrap=bootstrap.php --group reg,activate

run_group "BaseDbTablesInit"
./run-test --bootstrap=bootstrap.php --group BaseDbTablesInit

run_group "Others"
./run-test --bootstrap=bootstrap.php --exclude-group reg,activate,BaseDbTablesInit
