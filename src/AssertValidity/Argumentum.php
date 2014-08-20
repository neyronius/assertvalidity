<?php

namespace AssertValidity;

/**
 * Class for checking arguments in method calls
 */
class Argumentum
{
    
    const PARAM_REGEXP = '/^@param\s+([0-9a-zA-Z_:]+)\s+\$([0-9a-zA-Z_]+)/';

    /**
     *
     * @var Validatum
     */
    protected $validator = null;

    public function setValidator(Validatum $v)
    {
        $this->validator = $v;
    }

    public function check($method = null, $arguments = null)
    {
//        $rm = new \ReflectionMethod($method);
        $rm = new \ReflectionFunction($method);
        $rules = $this->parseComment($rm->getDocComment());
        
        foreach ($rm->getParameters() as $p) {
            /* @var $p \ReflectionParameter */
            
            if(!$this->validator->check($rules[$p->getName()], $arguments[$p->getPosition()])){
                
                $name = $p->getName();
                $rule = $rules[$p->getName()];
                
                throw new \InvalidArgumentException("Parameter $name does not pass rule $rule");
            }
                    
        }        
    }
    
    /**
     * Parse docblock comment into array of variableName => rule
     * 
     * @param string $comment
     * @return array
     */
    protected function parseComment($comment)
    {
        $rules = [];
        foreach (preg_split("/(\r?\n)/", $comment) as $line) {

            $line = trim($line);

            //pass these strings
            if (in_array($line, ['/*', '/**', '*', '*/'])) {
                continue;
            }

            //remove first *
            if ($line[0] === '*') {
                $line = trim(substr($line, 1));
            }

            if (preg_match(self::PARAM_REGEXP, $line, $matches)) {
                $rules[$matches[2]] = $matches[1];
            }
        }

        return $rules;
    }
    
    /**
     * Returns current validator object
     * Can be used for updating validation rules
     * 
     * @return Validatum
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
