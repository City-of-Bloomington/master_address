SHELL := /bin/bash
APPNAME := master_address

SASS := $(shell command -v sassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)
LANGUAGES := $(wildcard language/*/LC_MESSAGES)
JAVASCRIPT := $(shell find public -name '*.js' ! -name '*-*.js')

VERSION := $(shell cat VERSION | tr -d "[:space:]")
COMMIT := $(shell git rev-parse --short HEAD)

default: clean compile test package

deps:
ifndef SASS
	$(error "sassc is not installed")
endif
ifndef MSGFMT
	$(error "gettext is not installed")
endif

clean:
	rm -Rf build/${APPNAME}
	for f in $(shell find public/js -name '*-*.js' ); do rm $$f; done
	for f in $(shell find public/js -name '*-*.php'); do rm $$f; done

compile: deps $(LANGUAGES)
	cd                 public/css && sassc -mt compact screen.scss screen-${VERSION}.css
	cd data/Themes/COB/public/css && sassc -mt compact screen.scss screen-${VERSION}.css
	for f in ${JAVASCRIPT}; do cp $$f $${f%.js}-${VERSION}.js; done
	cd public/js/choosers && cp env.php env-${VERSION}.php

test:
	vendor/phpunit/phpunit/phpunit -c src/Test/Unit.xml

package:
	[[ -d build ]] || mkdir build
	rsync -rl --exclude-from=buildignore . build/${APPNAME}
	cd build && tar czf ${APPNAME}.tar.gz ${APPNAME}

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
