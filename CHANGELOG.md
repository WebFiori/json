# Changelog

## [5.0.0](https://github.com/WebFiori/json/compare/v4.0.2...v5.0.0) (2026-06-10)


### Features

* **config:** deprecate global constants in favor of Json::setDefaults() ([af1c924](https://github.com/WebFiori/json/commit/af1c9246a33659073f5e924493f139e77c69b0f2)), closes [#61](https://github.com/WebFiori/json/issues/61)
* **decoding:** typed deserialization with nested object hydration ([73384ae](https://github.com/WebFiori/json/commit/73384ae8f9257b7dba71f622c23f1b731599722c)), closes [#59](https://github.com/WebFiori/json/issues/59)
* **encoding:** auto-detect associative arrays and encode as JSON objects ([183f3df](https://github.com/WebFiori/json/commit/183f3df3456e9744d1bb31085011c323d4f1fcee)), closes [#56](https://github.com/WebFiori/json/issues/56)
* **encoding:** normalize getter-derived property names and add #[JsonProperty] attribute ([0c92486](https://github.com/WebFiori/json/commit/0c924867a37acc5b3c11974cccc507cea9ea567e)), closes [#58](https://github.com/WebFiori/json/issues/58)
* **encoding:** replace error suppression with reflection-based parameter check ([abd1d13](https://github.com/WebFiori/json/commit/abd1d138d3b23ae9c8a8ee5a26f3aef5979760a2)), closes [#60](https://github.com/WebFiori/json/issues/60)


### Miscellaneous Chores

* add github-actions ecosystem to dependabot config ([bb05169](https://github.com/WebFiori/json/commit/bb0516909f265a1fba68d38efa4e778e8acc330d))
* Merge pull request [#70](https://github.com/WebFiori/json/issues/70) from WebFiori/dev ([a6bd41f](https://github.com/WebFiori/json/commit/a6bd41ff097a64e296792f2a3cf0b1900d417ef7))
* remove accidentally committed tests/vendor and update .gitignore ([27a5dd3](https://github.com/WebFiori/json/commit/27a5dd34f04acb4596fae513b808bf7ff891ddd7))
* Update composer.json ([d135b7b](https://github.com/WebFiori/json/commit/d135b7bfda21b7d336ebe8281a3c465834290b34))

## [4.0.2](https://github.com/WebFiori/json/compare/v4.0.1...v4.0.2) (2026-06-01)


### Bug Fixes

* **ci:** set platform.php to 8.1 to resolve compatible dependencies ([8a7058d](https://github.com/WebFiori/json/commit/8a7058df999073ed0134e366ea095b1443d2444d))


### Miscellaneous Chores

* align CI with ecosystem baseline ([636ea18](https://github.com/WebFiori/json/commit/636ea1827c2bab157ab2bb2e2b28c28d421a21bc))
* align CI with ecosystem baseline ([69855d3](https://github.com/WebFiori/json/commit/69855d3282af6067c4de6723841e3e5e27ded34e))

## [4.0.0](https://github.com/WebFiori/json/compare/v3.3.2...v4.0.0) (2025-07-30)


### ⚠ BREAKING CHANGES

* This major version introduces significant improvements and may contain breaking changes from previous versions. Please review the changelog and update your code accordingly.

### Features

* upgrade to version 4.0.0 ([ff8a02e](https://github.com/WebFiori/json/commit/ff8a02ecba0f040d0be0f3f123c08b1e9f9d1b8a))


### Bug Fixes

* Multiple Issues ([98bc0e5](https://github.com/WebFiori/json/commit/98bc0e5b1c0f17e6ea5ee9cb369b6aff4fa7d493))


### Miscellaneous Chores

* Enhance PHP Docs ([e0bf363](https://github.com/WebFiori/json/commit/e0bf36390856cf462048722876c8e85055f8378b))
* Moved Files ([3ceb92d](https://github.com/WebFiori/json/commit/3ceb92d35f5545e79620c8d37236fefcb630942d))
* Remove PHP 8.0 ([c7ae980](https://github.com/WebFiori/json/commit/c7ae98052264ef34848f27aba36c0d893cffef78))

## [3.3.2](https://github.com/WebFiori/json/compare/v3.3.1...v3.3.2) (2025-01-28)


### Bug Fixes

* Change of Properties Style on Parsing ([4bdccc3](https://github.com/WebFiori/json/commit/4bdccc39f6c751a8f30c274f4fac3814a954785e))

## [3.3.1](https://github.com/WebFiori/json/compare/v3.3.0...v3.3.1) (2024-12-23)


### Bug Fixes

* Use of New Null Syntax ([d82823d](https://github.com/WebFiori/json/commit/d82823d1cd438219c8bffaad2e000994cd6732f4))
