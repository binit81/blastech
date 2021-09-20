<?php

namespace Matriphe\Md5Hash\Test;

use Matriphe\Cake13Hash\Cake13Hash;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    protected $salt = 'zamroni';

    public function setUp()
    {
        parent::setUp();

        $this->hash = new Cake13Hash($this->salt);
    }

    /**
     * @test
     */
    public function make_function_returns_correct_md5_hashed_value()
    {
        $this->assertSame(sha1($this->salt.'matriphe'), $this->hash->make('matriphe'));
    }

    /**
     * @test
     */
    public function check_function_returns_correct_value()
    {
        $this->assertTrue($this->hash->check('matriphe', sha1($this->salt.'matriphe')));
        $this->assertFalse($this->hash->check('matriphe', sha1('matriphe')));
        $this->assertFalse($this->hash->check('zamroni', sha1($this->salt.'matriphe')));
    }

    /**
     * @test
     */
    public function needsRehash_function_returns_false()
    {
        $this->assertFalse($this->hash->needsRehash(sha1('matriphe')));
    }
}
