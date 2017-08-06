<?php


namespace Confee\Support\Database;


use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;

/**
 * Class ModelFactory
 * @package Confee\Support\Database
 */
abstract class ModelFactory
{

    /**
     * @var \Illuminate\Database\Eloquent\Factory
     */
    protected $factory;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var string
     */
    protected $model;
    /**
     * ModelFactory constructor.
     */
    public function __construct()
    {
        $this->factory = app()->make(Factory::class);
        $this->faker = app()->make(Generator::class);
    }

    public function define()
    {
        $this->factory->define($this->model, function (){
            return $this->fields();
        });
    }

    /**
     * @return array
     */
    protected abstract function fields();
}