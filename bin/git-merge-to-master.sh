#!/bin/bash
# @Date:   2016-07-05T11:10:47-03:00
# @Modified at 2016-07-05T11:11:02-03:00
# {release_id}

CURRENT=`git branch | grep '\*' | awk '{print $2}'`
git checkout -b "${CURRENT}-`date +"%m-%d-%y-%s"`";
git checkout -b branchB
git checkout master
git checkout -b branchA
git merge -s ours branchB
git branch branchTEMP
git reset --hard branchB
git reset --soft branchTEMP
git commit --amend -m 'pack to master'
git checkout master
git merge --squash branchA
git commit
git branch -D  branchA branchB branchTEMP  ${CURRENT}
git push origin master:master;
git checkout -b ${CURRENT}
