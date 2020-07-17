<?php
/**
 * @group post
 */
class OurBlog_Post_AddTest extends OurBlog_DatabaseTestCase
{
    protected $data;
    protected static $post;

    public function getDataSet()
    {
        $this->data = include __DIR__ . '/data.php';
        if (!self::$post) {
            self::$post = new OurBlog_Post(1);
        }

        return $this->createArrayDataSet(array(
            'post' => array()
        ));
    }

    public function testCategoryIdKeyIsRequired()
    {
        unset($this->data['categoryId']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key categoryId');
    
        self::$post->add($this->data);
    }

    public function testTitleKeyIsRequired()
    {
        unset($this->data['title']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key title');
    
        self::$post->add($this->data);
    }

    public function testContentKeyIsRequired()
    {
        unset($this->data['content']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key content');
    
        self::$post->add($this->data);
    }

    public function testCategoryIdIsRequried()
    {
        $this->data['categoryId'] = '';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('categoryId required');
    
        self::$post->add($this->data);
    }

    public function testTitleIsRequried()
    {
        $this->data['title'] = '';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('title required');
    
        self::$post->add($this->data);
    }
    
    public function invalidCategoryIds()
    {
        return array(
            array('Linux'),
            array('0'),
            array('-1')
        );
    }

    /**
     * @dataProvider invalidCategoryIds
     */
    public function testInvalidCategoryId($categoryId)
    {
        $this->data['categoryId'] = $categoryId;
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid categoryId');

        self::$post->add($this->data);
    }
    
    public function testTitleMaxLength()
    {
        $this->data['title'] .= str_repeat(
            'A',
            501 - mb_strlen($this->data['title'], 'UTF-8')
        );
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('title too long, maxlength is 500');
    
        self::$post->add($this->data);
    }
    
    public function testContentMaxLength()
    {
        $this->data['content'] = str_pad($this->data['content'], 64001, 'A');
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('content too long, maxlength is 64000 bytes');
    
        self::$post->add($this->data);
    }
    
    public function testAddWithEmptyContent()
    {
        $this->data['content'] = '';
        self::$post->add($this->data);
        
        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-empty-content.php');
    
        $dataSet = $this->getConnection()->createDataSet(array('post'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));
    
        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }
    
    public function testAdd()
    {
        self::$post->add($this->data);
        
        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects.php');
    
        $dataSet = $this->getConnection()->createDataSet(array('post'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));
    
        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }
}
