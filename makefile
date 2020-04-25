SCRIPT := tests.test.php
URL := 127.0.0.1:8056

ifeq ($(OS),Windows_NT)
    CMD := start
else
    CMD := open
endif

cli:
	@php -f ${SCRIPT}

html:
	@${CMD} http://${URL}/${SCRIPT}
	@php -S ${URL}