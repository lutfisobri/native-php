<?php
namespace Riyu\Validation;

class Validator
{
    /**
     * The request instance.
     *
     * @var \Riyu\Http\Request
     */
    protected $request;

    /**
     * The array of rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * The array of errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * The array of custom error messages.
     *
     * @var array
     */
    protected $customMessages = [];

    public function __construct($request = null, $rules = null, array $customMessages = null)
    {
        if ($request) {
            $this->request = $request;
        }

        if ($rules) {
            $this->rules = $rules;
        }

        if ($customMessages) {
            $this->customMessage($customMessages);
        }
    }

    protected function customMessage(array $customMessages)
    {
        if ($customMessages) {
            foreach ($customMessages as $key => $value) {
                $key = explode('.', $key);

                if (count($key) == 1) {
                    $this->customMessages[$key[0]] = $value;
                    continue;
                }
    
                $this->customMessages[$key[0]][$key[1]] = $value;
            }
        }
    }

    /**
     * Validate the request.
     *
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules as $key => $rule) {
            $rules = explode('|', $rule);

            foreach ($rules as $rule) {
                $this->validateRule($key, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate the rule.
     *
     * @param string $key
     * @param string $rule
     * @return void
     */
    protected function validateRule($key, $rule)
    {
        $rule = explode(':', $rule);

        if (count($rule) > 1) {
            $this->validateWithParameter($key, $rule);
        } else {
            $this->validateWithoutParameter($key, $rule);
        }
    }

    /**
     * Validate the rule with parameter.
     *
     * @param string $key
     * @param array $rule
     * @return void
     */
    protected function validateWithParameter($key, $rule)
    {
        $ruleName = $rule[0];
        $parameter = $rule[1];

        if (method_exists($this, $ruleName)) {
            $this->$ruleName($key, $parameter);
        } else {
            throw new \Exception("Rule {$ruleName} does not exist.");
        }
    }

    /**
     * Validate the rule without parameter.
     *
     * @param string $key
     * @param array $rule
     * @return void
     */
    protected function validateWithoutParameter($key, $rule)
    {
        $ruleName = $rule[0];

        if (method_exists($this, $ruleName)) {
            $this->$ruleName($key);
        } else {
            throw new \Exception("Rule {$ruleName} does not exist.");
        }
    }

    /**
     * Validate the required rule.
     *
     * @param string $key
     * @return void
     */
    protected function required($key)
    {
        if (! $this->request->has($key)) {
            if (isset($this->customMessages[$key]['required'])) {
                $this->errors[$key][] = $this->customMessages[$key]['required'];
                return;
            } else if (isset($this->customMessages['required'])) {
                $this->errors[$key][] = $this->replaceField($this->customMessages['required'], $key);
                return;
            }
            
            $this->errors[$key][] = 'The ' . $key . ' field is required.';
        }
    }

    /**
     * Validate the email rule.
     *
     * @param string $key
     * @return void
     */
    protected function email($key)
    {
        if (! filter_var($this->request->get($key), FILTER_VALIDATE_EMAIL)) {
            if (isset($this->customMessages[$key]['email'])) {
                $this->errors[$key][] = $this->customMessages[$key]['email'];
                return;
            }
            $this->errors[$key][] = 'The ' . $key . ' field must be a valid email address.';
        }
    }

    protected function min($key, $parameter)
    {
        if (strlen($this->request->get($key)) < $parameter) {
            if (isset($this->customMessages[$key]['min'])) {
                $this->errors[$key][] = $this->customMessages[$key]['min'];
                return;
            }
            $this->errors[$key][] = 'The ' . $key . ' field must be at least ' . $parameter . ' characters.';
        }
    }

    protected function max($key, $parameter)
    {
        if (strlen($this->request->get($key)) > $parameter) {
            if (isset($this->customMessages[$key]['max'])) {
                $this->errors[$key][] = $this->customMessages[$key]['max'];
                return;
            }
            $this->errors[$key][] = 'The ' . $key . ' field may not be greater than ' . $parameter . ' characters.';
        }
    }

    protected function numeric($key)
    {
        if (! is_numeric($this->request->get($key))) {
            if (isset($this->customMessages[$key]['numeric'])) {
                $this->errors[$key][] = $this->customMessages[$key]['numeric'];
                return;
            }
            $this->errors[$key][] = 'The ' . $key . ' field must be numeric.';
        }
    }

    protected function replaceField($message, $key)
    {
        return str_replace(':field', $key, $message);
    }

    /**
     * Determine if the validation fails.
     *
     * @return bool
     */
    public function fails()
    {
        return ! empty($this->errors);
    }

    /**
     * Get the errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    public function input($key)
    {
        return $this->request->$key;
    }
}