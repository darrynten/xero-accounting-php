<?php
/**
 * Xero Library
 *
 * @category Library
 * @package  Xero
 * @author   Vitaliy Likhachev <make.it.git@gmail.com>
 * @license  MIT <https://github.com/darrynten/sage-one-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/sage-one-php
 */

namespace DarrynTen\Xero;

use DarrynTen\Xero\Exception\ModelCollectionException;

/**
 * Paging response of model
 *
 */
class ModelCollection
{
    /**
     * @var integer $totalResults
     */
    protected $totalResults;

    /**
     * @var integer $returnedResults
     */
    protected $returnedResults;

    /**
     * @var array $results
     */
    protected $results;

    /**
     * @var array $keys list available keys on ModelCollection
     */
    private $keys = ['totalResults', 'results'];

    /**
     *
     * @param string $key Desired property
     *
     * @throws ModelCollectionException
     */
    public function __get($key)
    {
        if (array_search($key, $this->keys) !== false) {
            return $this->{$key};
        }

        throw new ModelCollectionException(ModelCollectionException::GETTING_UNDEFINED_PROPERTY, $key);
    }

    /**
     * @var string $class Full path to the class
     * @var array $config Configuration array
     * @var stdClass|array $results
     * object in format of pagination response from Xero (stdClass)
     * or array of models (it's converted into required format)
     */
    public function __construct($class, $config, $results)
    {
        $collectionObject = $results;
        if (is_array($results)) {
            $collectionObject = new \StdClass;
            $collectionObject->TotalResults = count($results);
            $collectionObject->Results = $results;
        }

        if (is_object($results)) {
            $rawResults = $results->{$this->getClassName($class)};
            $collectionObject->TotalResults = count($rawResults);
            $collectionObject->Results = $rawResults;
        }

        // if (!property_exists($collectionObject, 'TotalResults')) {
        //     throw new ModelCollectionException(
        //         ModelCollectionException::MISSING_REQUIRED_PROPERTY,
        //         'TotalResults'
        //     );
        // }
        // if (!property_exists($collectionObject, 'ReturnedResults')) {
        //     throw new ModelCollectionException(
        //         ModelCollectionException::MISSING_REQUIRED_PROPERTY,
        //         'ReturnedResults'
        //     );
        // }
        // if (!property_exists($collectionObject, 'Results')) {
        //     throw new ModelCollectionException(
        //         ModelCollectionException::MISSING_REQUIRED_PROPERTY,
        //         'Results'
        //     );
        // }

        $models = [];
        
        foreach ($collectionObject->Results as $result) {
            $model = new $class($config);
            $model->loadResult($result);
            $models[] = $model;
        }
            (var_dump('==================', $results, 'xxxxxxx', $collectionObject,' xxxxxxxxxxxxxxxxxxxxx', $collectionObject->Results, $models));

        $this->totalResults = $collectionObject->TotalResults;
        $this->results = $models;
    }

    /**
     * Extracts className from path A\B\C\ClassName
     *
     * @param string $classPath Full path to the class
     */
    private function getClassName(string $class)
    {
        $classPath = explode('\\', $class);
        $className = $classPath[ count($classPath) - 1];
        return $className;
    }
}
