<?php
use DarkSide666\Swetest\Swetest;

class SwetestTest extends \Codeception\TestCase\Test
{
    /**
     * @var DarkSide666\Swetest\Swetest
     */
    private $swetest;

    protected function _before()
    {
        $this->swetest = new Swetest();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf("DarkSide666\\Swetest\\Swetest", $this->swetest);
    }

    public function testSetPath()
    {
        $this->assertInstanceOf("DarkSide666\\Swetest\\Swetest", $this->swetest->setPath(__DIR__.'/../../resources/'));
    }

    /**
     * @expectedException DarkSide666\Swetest\SwetestException
     */
    public function testSetInvalidPath()
    {
        $this->swetest->setPath(__DIR__);
    }

    public function testGetPath()
    {
        $this->assertInternalType('string', $this->swetest->getPath());
    }

    public function testSetMaskPath()
    {
        $this->assertInstanceOf("DarkSide666\\Swetest\\Swetest", $this->swetest->setMaskPath(true));
    }

    public function testStringQuery()
    {
        $this->assertInstanceOf("DarkSide666\\Swetest\\Swetest", $this->swetest->query('-h'));
    }

    public function testArrayQuery()
    {
        $query = [
            'h'
        ];
        $this->assertInstanceOf("DarkSide666\\Swetest\\Swetest", $this->swetest->query($query));
    }

    public function queryProvider()
    {
        return [
            [
                [
                    'p' => 0,
                    'f' => 'Tl',
                    'eswe',
                    'head',
                ],
                '-p0 -fTl -eswe -head',
            ],
            [
                [
                    'p' => 0,
                    'd' => 1,
                    'n' => 10,
                    's' => 2,
                ],
                '-p0 -d1 -n10 -s2',
            ],
        ];
    }

    /**
     * @dataProvider queryProvider
     */
    public function testCompile($query, $expected)
    {
        $this->assertEquals($expected, $this->swetest->compile($query));
    }

    /**
     * @expectedException DarkSide666\Swetest\SwetestException
     */
    public function testErrorExecute()
    {
        $this->swetest->execute();
    }

    public function testResponse()
    {
        $this->swetest->query('-b1.1.2014 -p0 -fTl -eswe -head')->execute();
        $this->assertEquals(['status' => 0, 'output' => [0 => '01.01.2014  280.4787281']], $this->swetest->response());
    }

    /**
     * @expectedException DarkSide666\Swetest\SwetestException
     */
    public function testErrorResponse()
    {
        $this->swetest->response();
    }

    /**
     * @expectedException DarkSide666\Swetest\SwetestException
     */
    public function testErrorGetStatus()
    {
        $this->swetest->getStatus();
    }


    /**
     * @expectedException DarkSide666\Swetest\SwetestException
     */
    public function testErrorGetOutput()
    {
        $this->swetest->getOutput();
    }

    public function testGetLastQuery()
    {
        $this->swetest->query('-h')->execute();

        $this->assertEquals('***-***swetest -edir***-*** -h', $this->swetest->getLastQuery());
    }

    public function testGetNullLastQuery()
    {
        $this->assertEmpty($this->swetest->getLastQuery());
    }
}