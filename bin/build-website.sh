#!/bin/bash
# @Date:   2015-07-24T09:43:06-03:00
# @Modified at 2016-06-16T11:26:54-03:00
# {release_id}

source "$(dirname $0)/init.sh";

h1 "Build Website for [${PROJECT_NAME}]"
mkdir -pv var/build/coverage _layouts build/logs/report;

git commit -a --amend --no-edit;

myCopy() {
  echo "---" > var/build/${2}.md;
  echo "layout: default" >> var/build/${2}.md;
  echo "---" >> var/build/${2}.md;
  cat ${1}.md  >> var/build/${2}.md;
}

cp -r build/logs/report var/build/coverage
cp -v build/logs/testdox.html var/build/coverage/
myCopy README index;
git checkout gh-pages || (git checkout --orphan gh-pages && git ls-files -z | xargs -0 git rm --cached);
echo '*' > .gitignore

cp ${commonSdk}/Resources/website/default.html _layouts/
cp ${commonSdk}/Resources/website/_config.yml .;

mv var/build/index.md  .;
git rm -r --cached coverage/.;
rm -rf coverage;
mv var/build/coverage .

pushd coverage;
  STRING="s~$PW~~g";
  find . -type f -print0 |  xargs -0 perl -i.bak -pe $STRING
  find . -name "*.bak" -type f -delete
popd

echo "Website for [${PROJECT_NAME}] created by gpupo/common-sdk" > build
git add -f build .gitignore index.md _config.yml _layouts/default.html coverage/.
git commit -m "Website for [${PROJECT_NAME}] created by gpupo/common-sdk";
git push -f origin gh-pages:gh-pages && git checkout $CURRENT_BRANCH;
rm -rfv _site _layouts coverage _config.yml index.md
