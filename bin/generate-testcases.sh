#!/bin/bash
# @Date:   2016-07-01T15:21:50-03:00
# @Modified at 2016-07-05T11:04:22-03:00
# {release_id}

mkdir -p var/autodoc;
eval "$(php vendor/gpupo/common-sdk/bin/ClassesFinder.php)"
