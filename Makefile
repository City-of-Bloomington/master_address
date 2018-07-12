SASS := $(shell command -v pysassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)
VERSION := $(shell command git rev-parse HEAD | cut -c 1-8)
LANGUAGES := $(wildcard language/*/LC_MESSAGES)

default: clean compile package

clean:
	rm -Rf build
	mkdir build

package:
	rsync -rl --exclude-from=buildignore --delete . build/master_address
	cd build && tar czf master_address.tar.gz master_address

deps:
ifndef SASS
	$(error "node-sass is not installed")
endif
ifndef MSGFMT
	$(error "gettext is not installed")
endif

compile: deps $(LANGUAGES)
	pysassc -t compact -m public/css/screen.scss public/css/screen.css

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
