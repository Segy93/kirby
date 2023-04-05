# Deploy procedura

`x-y-z` je verzija koju treba izbaciti ([semver](https://semver.org))
Zameniti svuda ovo odgovarajućim podacima

1. Napraviti novu granu od dev grane, sa imenom: `release-x.y.z`
2. Otvoriti `composer.json` i izmeniti `version` ključ u `x.y.z`
3. `rm composer.lock`
4. `php composer.phar install`
5. `git tag -a vx.y.z -m "Version x.y.z"`
6. `git push origin --tags`
7. Napraviti pull request da se release-x.y.z spoji na dev
8. Napraviti pull request da se dev spoji na master
