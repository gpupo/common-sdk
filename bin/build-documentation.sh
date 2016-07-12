#!/bin/bash
# @Date:   2016-06-24T09:45:16-03:00
# @Modified at 2016-06-24T09:45:37-03:00

source "$(dirname $0)/init.sh";

h1 "Build documentation for [${PROJECT_NAME}] project";

pushd Resources/doc;
STRING="s~{project.name}~${PROJECT_NAME}~g";
find . -type f -print0 |  xargs -0 perl -i.bak -pe $STRING
find . -name "*.bak" -type f -delete
popd

cat build/logs/testdox.txt | grep -vi php |  sed "s/.*\[/-&/" | \
sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' |\
sed 's/Gpupo\\Tests\\/### /g' > Resources/doc/testdox.md;
cat Resources/doc/libraries-list.md | sed 's/  * / | /g' | sed 's/e0 / | /g' > Resources/doc/libraries-table.md;

echo '' > README.md;
names='main require license QA thanks install usage console links links-common dev todo dev-common testdox libraries-table footer-common'
for name in $names
do
  touch Resources/doc/${name}.md;
  printf "\n\n"  >>  README.md;
  cat Resources/doc/${name}.md >> README.md;
  printf "\n"  >>  README.md;
done

git commit --amend --no-edit;

echo "Build wiki for [${PROJECT_NAME}] project";

if [ ! -d "var/wiki" ]; then
  git clone --depth=1  git@github.com:${PROJECT_NAME}.wiki.git var/wiki || exit 1;
fi

if ! git remote | grep alternative > /dev/null; then
  git remote add alternative git@bitbucket.org:${PROJECT_NAME}].git/wiki
fi

cd var/wiki
git checkout --orphan auto
rm -f ./*.md;
cp ../../Resources/doc/* .
cp _Sidebar.md Home.md;

printf "\n\nDocumentation for **${PROJECT_NAME}** created by [gpupo/common-sdk](http://www.g1mr.com/common-sdk/) | `date +"%m-%d-%y"`" >> _Footer.md

git add *.md
git commit -am "Documentation for [${PROJECT_NAME}] created by gpupo/common-sdk";
git push -f origin auto:master
git push -f alternative auto:master
git fetch -f origin master:master
git checkout master
git branch -D auto

echo "done!";
exit 0;
