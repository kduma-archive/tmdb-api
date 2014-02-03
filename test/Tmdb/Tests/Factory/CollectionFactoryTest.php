<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb\Tests\Factory;

use Tmdb\Factory\CollectionFactory;
use Tmdb\Model\Collection;

class CollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConstructCollection()
    {
        /**
         * @var CollectionFactory $factory
         */
        $factory = $this->getFactory();
        $data    = $this->loadByFile('collection/get.json');

        /**
         * @var Collection $collection
         */
        $collection = $factory->create($data);

        $this->assertInstanceOf('Tmdb\Model\Collection', $collection);
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetFactories()
    {
        /**
         * @var CollectionFactory $factory
         */
        $factory = $this->getFactory();

        $class = new \stdClass();

        $factory->setMovieFactory($class);
        $factory->setImageFactory($class);

        $this->assertInstanceOf('stdClass', $factory->getMovieFactory());
        $this->assertInstanceOf('stdClass', $factory->getImageFactory());
    }


    /**
     * @test
     */
    public function shouldBeAbleToCreateCollection()
    {
        $factory = $this->getFactory();

        $data = array(
            array('id' => 1),
            array('id' => 2),
        );

        $collection = $factory->createCollection($data);

        $this->assertEquals(2, count($collection));
    }

    protected function getFactoryClass()
    {
        return 'Tmdb\Factory\CollectionFactory';
    }
}