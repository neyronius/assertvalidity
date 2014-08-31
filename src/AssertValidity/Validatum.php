<?php

namespace AssertValidity;

class Validatum {

    protected $rules = [];
    protected $errorBackTrace = [];

    public function __construct()
    {
        $this->import(require __DIR__ . '/rules.php');
    }

    public function check($rule, $value)
    {
        $this->errorBackTrace = [];
        return $this->checkWorker($rule, $value);
    }

    protected function isStringRule($rule)
    {
        if (is_string($rule)) {

            if (strlen($rule) === 0) {
                throw new Exception("Rule name could not be empty", 1409480471);
            }

            $ruleStructure = explode(':', $rule);

            return [
                $this->getRuleDescription($ruleStructure[0]),
                array_slice($ruleStructure, 1)
            ];
        }

        return null;
    }

    /**
     * Check if parameter is Closure
     * 
     * @param \Closure $rule
     * @return boolean
     */
    protected function isRealCallable($rule)
    {
        return is_callable($rule) && is_object($rule) && $rule instanceof \Closure;
    }

    protected function isCallBackRule($rule)
    {
        if ($this->isRealCallable($rule)) {
            return [$rule, []];
        } elseif (is_array($rule) && count($rule) === 2 && isset($rule[0], $rule[1]) && $this->isRealCallable($rule[0]) && is_array($rule[1])) {
            return [$rule[0], $rule[1]];
        }

        return null;
    }

    protected function isArrayRule($rule)
    {
        if (is_array($rule) && count($rule) === 1) {
            $key = array_keys($rule)[0];

            if (is_string($key)) {
                return [
                    $this->getRuleDescription($key),
                    $rule[$key]
                ];
            }
        }

        return null;
    }

    protected function isSetOfRules($rule)
    {
        if (is_array($rule)) {
            return [$rule, []];
        }

        return null;
    }

    /**
     * Execute rule and returns true or false
     * Used recursively
     * 
     * $rule:
     *  
     *  Determinitives (det):
     *  ["rule" => [p1, p2, ...] - one rule name with parameters
     *  "rule:parameter1:parameter2" - one rule name with parameters eq ["rule" => [p1, p2]]
     *  "rule" - one rule name eq ["rule" => []]
     *  [function($v, $p1, $p2){}:boolean, [$p1, $p2]] - one callback with parameters
     *  function($v){}:boolean - simple callback
     * 
     *  Or array of determenitives:
     *  ["rule", "rule" => [p1, p2], function($v){}, ...det ]
     * 
     * @param string|[]|callable $rule
     * @param mixed $value
     * @return boolean
     * @throws \Exception
     */
    protected function checkWorker($rule, $value)
    {
        $checkers = ['isArrayRule', 'isCallBackRule', 'isStringRule', 'isSetOfRules'];

        foreach ($checkers as $checker) {
            $ruleNormalized = call_user_func_array([$this, $checker], [$rule]);

            if ($ruleNormalized) {
                break;
            }
        }

        if (!$ruleNormalized || $ruleNormalized[0] === null) {
            //@todo Add a string description for $rule variable
            throw new \Exception("Invlaid rule $rule");
        }

        if ($this->isRealCallable($ruleNormalized[0])) {

            $res = call_user_func_array(
                    $ruleNormalized[0], array_merge([$value], $ruleNormalized[1], [$this])
            );

            if (!$res && is_string($rule) && strlen($rule) > 0) {
                $this->errorBackTrace[] = $rule;
            }

            return $res;
        } elseif (is_array($ruleNormalized[0])) {

            foreach ($ruleNormalized[0] as $k => $v) {
                if (is_string($k)) {
                    $workerRule = [$k => $v];
                } elseif ($this->isRealCallable($v)) {
                    $workerRule = [$v, $ruleNormalized[1]];
                } else {
                    $workerRule = $v;
                }

                if (!$this->checkWorker($workerRule, $value)) {
                    //$this->errorBackTrace[] = $ruleName;
                    return false;
                }
            }
            return true;
            
        } else {
            throw new \Exception("Invalid rule type: " . gettype($ruleNormalized[0]));
        }
    }

    /**
     * 
     * 
     * @param string $name
     * @param callable|string|array $callback
     */
    public function add($name, $description)
    {
        $this->rules[$name] = $description;
    }

    /**
     * 
     * @param array $rules
     */
    public function import($rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    public function isRuleExists($ruleName)
    {
        return isset($this->rules[$ruleName]);
    }

    /**
     * Returns rule description as array or callable
     * Returns null if rule is not found
     * Handles aliases  like 'int' => 'integer' recursively
     * 
     * @param string $ruleName
     * @return array|callable|null
     */
    public function getRuleDescription($ruleName)
    {
        if (isset($this->rules[$ruleName])) {
            $ruleDescription = $this->rules[$ruleName];

            if (is_string($ruleDescription)) {
                return $this->getRuleDescription($ruleDescription);
            }

            return $ruleDescription;
        }

        return null;
    }

    public function getErrorBackTrace()
    {
        return array_reverse($this->errorBackTrace);
    }

    /**
     * Clear errors storage
     */
    public function clearErrorBackTrace()
    {
        $this->errorBackTrace = [];
    }

}
