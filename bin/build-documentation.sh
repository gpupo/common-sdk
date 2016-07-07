#!/bin/bash
# @Date:   2016-06-24T09:45:16-03:00
# @Modified at 2016-06-24T09:45:37-03:00

if [ $# -eq 0 ]
  then
    echo "No arguments supplied";
    exit 1;
fi

if [ $1 -ge 5 ]; then echo "Project Name missed!" ; exit 2; fi;


PROJECT_NAME=$1;

echo "Build documentation for [${PROJECT_NAME}] project";

cat build/logs/testdox.txt | grep -vi php |  sed "s/.*\[/-&/" | \
sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' |\
sed 's/Gpupo\\Tests\\/### /g' > Resources/doc/testdox.md;

cat Resources/doc/libraries-list.md | sed 's/  * / | /g' | sed 's/e0 / | /g' > Resources/doc/libraries-table.md;

echo '' > README.md;
names='main require license QA thanks install usage console links links-common dev todo dev-common testdox libraries-table footer-common'
for name in $names
do
  touch Resources/doc/${name}.md;
  printf '<!-- '  >>  README.md;
  printf "$name"  >>  README.md;
  printf ' -->'  >>  README.md;
  printf "\n\n"  >>  README.md;
  cat Resources/doc/${name}.md >> README.md;
  printf "\n"  >>  README.md;
done

git commit -am 'Automatic documentation';

echo "Build wiki for [${PROJECT_NAME}] project";

if [ ! -d "var/wiki" ]; then
  git clone --depth=1  git@github.com:${PROJECT_NAME}.wiki.git var/wiki || exit 1;
fi

cd var/wiki
git checkout --orphan auto
rm -f ./*.md;
cp ../../Resources/doc/* .
cp _Sidebar.md Home.md;
git add *.md
git commit -am 'auto'
git push -f origin auto:master
git fetch -f origin master:master
git checkout master
git branch -D auto

echo "done!";
exit 0;
