test: run-test.php test-files
	bpc -u phpunit \
        -u phpunit-ext \
        -u OurBlog \
        -d display_errors=on \
        run-test.php \
        --input-file test-files

clean:
	@rm -rf .bpc-build-* md5.map run-test

clean-all: clean
	@rm -rf MockClassFile run-test.php test-files
