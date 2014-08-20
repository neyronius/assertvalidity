<?php

namespace AssertValidity;

class Validatum
{
    
    protected $rules = [];
    
    protected $errorBackTrace = [];
    
    public function __construct()
    {
        $this->merge(require __DIR__ . '/rules.php');
    }
    
    public function check($rule, $value)
    {
        $this->errorBackTrace = [];
        return $this->checkWorker($rule, $value);
    }

    /**
     * 
     * @param string|[]|callable $rule
     * @param mixed $value
     * @return boolean
     * @throws \Exception
     */
    protected function checkWorker($rule, $value)
    {
        //try{
            
            $ruleName = ''; //string name of the rule
            $ruleArguments = [];

            if(is_string($rule)){

                if(strlen($rule) === 0){
                    throw new \Exception("Invalid rule name");
                }

                $ruleStructure = explode(':', $rule);

                $ruleName = $ruleStructure[0];
                $ruleArguments = array_slice($ruleStructure, 1);

            }elseif(is_array($rule)){

                if(count($rule) !== 2){
                    throw new \Exception("Invalid rule structure. Array[2] expected");
                }

                if(is_string($rule[0])){
                    $ruleName = $rule[0];
                }elseif(is_callable($rule[0]) || is_array($rule[0])){
                    $ruleDescription = $rule[0];
                }else{
                    throw new \Exception("Invalid rule type", 1408044522);
                }
                
                $ruleArguments = [$rule[1]];

            }elseif(is_callable($rule)){

                $ruleDescription = $rule;

            }else{
                throw new \Exception("Invalid parameter", 1407955541);
            }

            if(!isset($ruleDescription)){
                
                if(!isset($this->rules[$ruleName])){
                    throw new \Exception("Rule '{$ruleName}' does not exist", 1407955542);
                }
                
                $ruleDescription = $this->rules[$ruleName];
            }

            if(is_callable($ruleDescription)){
                
                $res = call_user_func_array(
                                $ruleDescription,
                                array_merge([$value], $ruleArguments, [$this])
                        );
                
                if(!$res && is_string($ruleName) && strlen($ruleName) > 0){
                    $this->errorBackTrace[] = $ruleName;
                }
                
                return $res;
            }

            if(!is_array($ruleDescription)){
                throw new \Exception("Invalid rule descriprion");
            }

            foreach ($ruleDescription as $k => $r) {

               if (is_string($k)) {
                   $r = [$k, $r];
                   //['hash', [...]]
               }elseif(is_callable($r)){
                   //rule => [ ..., function(){} ]
                   $r = [$r, $ruleArguments];
               }

               if (!$this->checkWorker($r, $value)) {
                   $this->errorBackTrace[] = $ruleName;
                   return false;
               }
            }

            return true;
            
//        } catch (\Exception $ex) {
//            
//            die(sprintf("\r\n Error in rule %s: %s, file: %s, line: %d \r\n",
//                    $rule,
//                    $ex->getMessage(),
//                    $ex->getFile(),
//                    $ex->getLine()
//            ));
//        }
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
    public function merge($rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }
    
    public function isRuleSet($ruleName)
    {
        return isset($this->rules[$ruleName]);
    }
    
    public function getErrorBackTrace()
    {
        return array_reverse($this->errorBackTrace);
    }

}
