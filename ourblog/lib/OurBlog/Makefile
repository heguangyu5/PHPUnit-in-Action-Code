PHP_FILES = Db.php Util.php User.php Post.php Upload.php

libOurBlog_u-4.4a.so: $(PHP_FILES)
	bpc -l OurBlog $(PHP_FILES)

install: libOurBlog_u-4.4a.so
	bpc -l OurBlog --install && ldconfig

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -f *.a *.so *.heap *.sch
