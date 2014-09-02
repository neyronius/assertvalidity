PHP validation library for variables and function's arguments
=============================================================

AssertValidity is a library that allows you to validate variables and function/method arguments.
 
```php
/**
 * 
 * @param string $a
 */
function test($a)
{
    AV::arg(__FUNCTION__, func_get_args());
}

test("1"); //ok

test(1); // InvalidArgumentException thrown

```