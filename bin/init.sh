#!/bin/bash
# @Date:   2016-07-08T08:56:28-03:00
# @Modified at 2016-07-08T08:57:32-03:00
# {release_id}

commonSdk="$(dirname $0)/../";
PW=`pwd`;
CURRENT_BRANCH=`git branch | grep '\*' | awk '{print $2}'`

if [ $# -eq 0 ]
  then
    echo "No arguments supplied";
    exit 1;
fi

PROJECT_NAME=$1;

size=${#PROJECT_NAME}
if [ $size -lt 7 ]
  then
    echo "Project Name missed!";
    exit 2;
fi

h1() {
    printf "\n- $1\n";
}

h1 "gpupo/common-sdk";
