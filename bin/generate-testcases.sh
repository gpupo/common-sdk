#!/bin/bash
mkdir -p Resources/Documentation;
rm -f Resources/Documentation/*
eval "$(php vendor/gpupo/common-sdk/bin/ClassesFinder.php)"
ls -ls Resources/Documentation/*.php;
