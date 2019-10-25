<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:36
 */
use PHPUnit\Framework\TestCase;
use GUIHelper\UploadFile.php;
class StackTest extends TestCase
{
    public function testclearFlatTree()
    {

        $prs->initTree();
        $prs->clearFlatTree();
        $this->assertSame(6,$prs->getNumOfIter());
        $this->assertSame($testModel, $prs->getResultString());
    }
}