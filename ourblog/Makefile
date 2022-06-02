PHP_FILES = autoload.php            \
            lib/OurBlog/Db.php      \
            lib/OurBlog/Util.php    \
            lib/OurBlog/User.php    \
            lib/OurBlog/Post.php    \
            lib/OurBlog/Upload.php  \
            admin/login.php         \
            admin/logout.php        \
            admin/check-login.php   \
            admin/header.php        \
            admin/footer.php        \
            admin/index.php         \
            admin/add.php           \
            admin/edit.php          \
            admin/delete.php        \
            admin/upload.php        \
            reg.php         \
            activate.php    \
            header.php      \
            footer.php      \
            index.php       \
            post.php

libourblog_u-4.4a.so: $(PHP_FILES)
	bpc -l ourblog $(PHP_FILES)

install: libourblog_u-4.4a.so
	bpc -l ourblog --install && ldconfig

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -f *.a *.so *.heap *.sch
