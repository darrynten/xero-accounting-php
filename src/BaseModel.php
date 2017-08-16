<?php
/**
 * Xero Library - Base Model
 *
 * @category Library
 * @package  Xero
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/xero-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/xero-php
 * @version  PHP 7+
 *
 * TODO Split this file it's getting too big
 */

namespace DarrynTen\Xero;

use DarrynTen\Xero\Request\RequestHandler;
use DarrynTen\Xero\Exception\ModelException;

/**
 * This is the base class for all the interactable Xero Models.
 *
 * This class covers all/get/save/delete/update calls for models that require it.
 *
 * @inheritdoc
 */
abstract class BaseModel extends StaticBaseModel
{
    /**
     * API Endpoint
     *
     * Null means non interactable
     */
    public $endpoint = null;

    /**
     * A request object
     *
     * TODO this should be refactored out
     */
    protected $request;

    /**
     * Features supported by the endpoint
     *
     * These features enable and disable certain calls from the base model
     *
     * @var array $features
     */
    protected $features = [
        'all' => false,
        'get' => false,
        'create' => false,
        'update' => false,
        /**
         * Non-system accounts and accounts not used on transactions
         * can be deleted using the delete method. If an account is
         * not able to be deleted you can update the status to ARCHIVED
         */
        'delete' => false,
        'order' => true,
        'filter' => true,
    ];

    /**
     * Make a new model
     *
     * Setup a request handler and bind the config
     *
     * @param array $config The config for the model
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->request = new RequestHandler($config);
    }

    /**
     * Returns 'all' of something
     *
     * All methods that retrieve a collection follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * NB: Does not load an account into the class! Returns a raw representation
     * from the Xero API.
     *
     * $account = new Account;
     * $allAccounts = $account->all();
     *
     * @param array $parameters for sort and filter implementation
     *
     * @return ModelCollection A collection of entities
     */
    public function all(array $parameters = [])
    {
        if (!$this->features['all']) {
            $this->throwException(ModelException::NO_GET_ALL_SUPPORT);
        }

        if ($parameters) {
            $parameters = $this->prepareGetQueryParams($parameters);
        }

        $results = $this->request->request('GET', $this->endpoint, null, $parameters);

        return new ModelCollection(static::class, $this->config, $results->{$this->entity});
    }

    /**
     * Returns 'one' of something
     *
     * All methods that retrieve a single entity follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * $account = new Account;
     * $account->get('297c2dc5-cc47-4afd-8ec8-74990b8761e9');
     *
     * @return object A single entity
     */
    public function get(string $id)
    {
        if (!$this->features['get']) {
            $this->throwException(ModelException::NO_GET_ONE_SUPPORT, sprintf('id %s', $id));
        }

        $result = $this->request->request('GET', $this->endpoint, $id);

        $this->loadResult($result->{$this->entity});
    }

    /**
     * Returns ModelCollection of something based on given ids
     *
     * $account = new Account;
     * $account->getByIds(['297c2dc5-cc47-4afd-8ec8-74990b8761e9', '297c2dc5-cc47-4afd-8ec8-74990b8761e9']);
     *
     * @param array $ids
     *
     * @return ModelCollection A collection of entities
     */
    public function getByIds(array $ids)
    {
        if (!$this->features['get']) {
            $this->throwException(
                ModelException::NO_GET_ONE_SUPPORT,
                sprintf(
                    'id %s',
                    implode(', ', ($ids))
                )
            );
        }

        $results = [];
        foreach ($ids as $id) {
            $result = $this->request->request('GET', $this->endpoint, $id)->{$this->entity};
            array_push($results, $result);
        }

        return new ModelCollection(static::class, $this->config, $results);
    }

    /**
     * Delete an entity
     *
     * All methods that retrieve a single entity follow the same pattern, and this
     * method here allows any compatible method to be called with something like:
     *
     * $account = new Account;
     * $account->delete('297c2dc5-cc47-4afd-8ec8-74990b8761e9');
     *
     * @param string $id The ID to delete
     *
     * @return void
     */
    public function delete(string $id)
    {
        if (!$this->features['delete']) {
            $this->throwException(ModelException::NO_DELETE_SUPPORT, sprintf('id %s', $id));
        }

        // TODO Response handle?
        $this->request->request('DELETE', $this->endpoint, $id);
    }

    /**
     * Submits a save call to Xero
     *
     * @return BaseModel
     */
    public function create()
    {
        if (!$this->features['create']) {
            $this->throwException(ModelException::NO_CREATE_SUPPORT);
        }
        $this->validateModel();
        $this->validateMinimumFieldsRequiredForCreate();

        $data = $this->toObject();
        $xml = $this->generateValidXmlFromArray($data);
        $data = $this->request->request('PUT', $this->endpoint, 'Save', ['body' => $xml]);
        /**
         * we do not need to verify results here because loadResult will throw exception
         * in case of invalid body
         * If we reach this string then we expect that API returned valid body with response code 200
         * otherwise ApiException was thrown and this line can not be reached
         */
        $this->loadResult($data->{$this->entity});

        return $this;
    }

    /**
     * Submits a update call to Xero
     *
     * @return BaseModel
     */
    public function update()
    {
        if (!$this->features['update']) {
            $this->throwException(ModelException::NO_UPDATE_SUPPORT);
        }
        if (!$this->{$this->idField}) {
            $this->throwException(ModelException::ID_MISSING_FOR_UPDATE);
        }
        $this->validateModel();

        $id = $this->{$this->idField};
        $data = $this->toObject();
        $xml = $this->generateValidXmlFromArray($data);

        $data = $this->request->request('POST', $this->endpoint, $id, ['body' => $xml]);
        /**
         * we do not need to verify results here because loadResult will throw exception
         * in case of invalid body
         * If we reach this string then we expect that API returned valid body with response code 200
         * otherwise ApiException was thrown and this line can not be reached
         */
        $this->loadResult($data->{$this->entity});

        return $this;
    }

    /**
     * Used to generate right filters for query from raw parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function prepareGetQueryParams(array  $parameters) : array
    {
        $queryParams = [];
        if (array_key_exists('order', $parameters)) {
            if (!$this->features['order']) {
                $this->throwException(ModelException::NO_SORT_SUPPORT);
            }
            if (!array_key_exists('field', $parameters['order'])) {
                $this->throwException(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);
            }
            if (!in_array($parameters['order']['field'], array_keys($this->fields))) {
                $this->throwException(ModelException::TRYING_SORT_BY_UNKNOWN_FIELD);
            }

            $queryParams['order'] = $parameters['order']['field'];
            if (array_key_exists('direction', $parameters['order']) &&
                in_array($parameters['order']['direction'], ['ASC','DESC'])) {
                $queryParams['order'] .= sprintf('+%s', $parameters['order']['direction']);
            }
        }

        if (array_key_exists('filter', $parameters)) {
            if (!$this->features['filter']) {
                $this->throwException(ModelException::NO_FILTER_SUPPORT);
            }
            foreach ($parameters['filter'] as $key => $value) {
                if ($value) {
                    if (!in_array($key, array_keys($this->fields))) {
                        $this->throwException(ModelException::TRYING_FILTER_BY_UNKNOWN_FIELD);
                    }
                    $preparedKey = sprintf('%ss', $this->getRemoteKey($key));
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $queryParams[$preparedKey] = $value;
                }
            }
        }

        return $queryParams;
    }

    /**
     * Used to generate XML nodes from array
     *
     * @param $array
     *
     * @return string
     */
    private function generateXmlFromArray($array)
    {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                if ($value) {
                    $xml .= '<' . $key . '>' . "\n" . $this->generateXmlFromArray($value) . '</' . $key . '>' . "\n";
                }
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES) . "\n";
        }

        return $xml;
    }

    /**
     *
     * Used to generate XML nodes from array
     *
     * @param $array
     *
     * TODO what is difference between generate valid xml and the
     * other method?
     *
     * @return string
     */
    protected function generateValidXmlFromArray(array $array)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

        $xml .= '<' . $this->entity . '>' . "\n";
        $xml .= $this->generateXmlFromArray($array);
        $xml .= '</' . $this->entity . '>' . "\n";

        return $xml;
    }



    /**
     * Validate that model has minimum amount of fields for create operation
     *
     * @throws ModelException
     */
    private function validateMinimumFieldsRequiredForCreate()
    {
        foreach ($this->fields as $key => $config) {
            if (array_key_exists('create', $config) &&
                !array_key_exists($key, $this->fieldsData)
            ) {
                if (array_key_exists('exceptType', $config['create']) &&
                    $this->fieldsData[$this->typeField] === $config['create']['exceptType']
                ) {
                    continue;
                }

                if (array_key_exists('onlyType', $config['create']) &&
                    $this->fieldsData[$this->typeField] !== $config['create']['onlyType']
                ) {
                    continue;
                }

                $this->throwException(
                    ModelException::REQUIRED_PROPERTY_MISSING_FOR_CREATE,
                    sprintf('property %s', $key)
                );
            }
        }
    }
}
