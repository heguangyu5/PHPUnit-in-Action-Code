<?php
/**
 * @group post
 */
class OurBlog_Post_EditTest extends OurBlog_DatabaseTestCase
{
    public function getDataSet()
    {
        return $this->createArrayDataSet(
            include __DIR__ . '/fixtures.php'
        );
    }
    
    public function testCannotEditOthersPost()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('id not exists or not your post');
    
        $post = new OurBlog_Post(1);
        $post->edit(array(
            'id'         => 2,
            'categoryId' => 5,
            'title'      => '简历解析工具BobParser',
            'content'    => '这是一个很不错的项目'
        ));
    }
    
    public function testEdit()
    {
        $post = new OurBlog_Post(1);
        $post->edit(array(
            'id'         => 1,
            'categoryId' => 5,
            'title'      => '实战 Wireshark https 抓包',
            'content'    => 'Wireshark https SSLKEYLOGFILE chromium-browser'
        ));
        
        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects.php');

        $dataSet = $this->getConnection()->createDataSet(array('post'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }
}
