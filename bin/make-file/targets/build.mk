
## Build and publish a github gh-pages branch
build@gh-page:
	mkdir -p var/cache;
	echo "---" > var/cache/index.md;
	echo "layout: default" >> var/cache/index.md;
	echo "---" >> var/cache/index.md;
	cat README.md  >> var/cache/index.md;
	git checkout gh-pages || (git checkout --orphan gh-pages && git ls-files -z | xargs -0 git rm --cached);
	mkdir -p _layouts;
	cp -f vendor/gpupo/common/Resources/gh-pages-template/default.html _layouts/
	cp -f vendor/gpupo/common/Resources/gh-pages-template/_config.yml .;
	cp var/cache/index.md  .;
	git add -f index.md _config.yml _layouts/default.html;
	git commit -m "Website recreated by gpupo/common";
	git push -f origin gh-pages:gh-pages;
	git checkout -f master;
