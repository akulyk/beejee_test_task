<?php

namespace app\http\requests;


use app\exceptions\validations\RequestValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use JeffOchoa\ValidatorFactory;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AbstractValidator
 *
 * @package app\validators
 */
abstract class AbstractRequest
{
    const FLASH_ERROR_PREFIX = 'error-';
    const FLASH_VALUE_PREFIX = 'value-';
    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Session
     */
    protected $session;

    protected $throwExceptionOnError = true;

    /**
     * AbstractRequest constructor.
     *
     * @param Request $request
     * @param ValidatorFactory $validatorFactory
     * @param Session $session
     * @throws RequestValidationException
     */
    public function __construct(Request $request, ValidatorFactory $validatorFactory, Session $session)
    {
        $this->validatorFactory = $validatorFactory;
        $this->request = $request;
        $this->session = $session;
        $this->init();
        $this->handle();
    }

    protected function init()
    {
        $this->validator = $this->validatorFactory->make($this->data(), $this->rules());
    }

    public function getHttpRequest()
    {
        return $this->request;
    }

    /**
     * @throws RequestValidationException
     */
    public function handle()
    {
        $this->cleanFlashBag();
        if ($this->hasErrors() && $this->throwExceptionOnError) {
            throw new RequestValidationException(
                $this->errors()->toArray(),
                'Request validation error!'
            );
        } elseif ($this->hasErrors()) {
            $flashBag = $this->session->getFlashBag();
            foreach ($this->errors()->toArray() as $name => $messages) {
                $flashBag->set(static::FLASH_ERROR_PREFIX . $name, implode(PHP_EOL, $messages));
            }
            foreach ($this->request->input() as $field => $value) {
                $flashBag->set(static::FLASH_VALUE_PREFIX . $field, $value);
            }
        }
    }

    public function data(): array
    {
        return $this->all();
    }

    public function all(): array
    {
        return $this->request->all();
    }

    public function get($key)
    {
        return $this->request->get($key);
    }

    public function post($key)
    {
        return $this->request->post($key);
    }

    public function hasErrors(): bool
    {
        return count($this->errors()) > 0;
    }

    public function errors()
    {
        return $this->validator->errors();
    }

    public function cleanFlashBag(){
        $flashBag = $this->session->getFlashBag();
        foreach ($this->data() as $field => $value){
            $flashBag->get(static::FLASH_ERROR_PREFIX . $field );
            $flashBag->get(static::FLASH_VALUE_PREFIX . $field );
        }
    }

    public abstract function rules(): array;
}
