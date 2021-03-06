<?php
/**
 * Class constructor validator. Validates argument types duplication
 *
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Framework\Code\Validator;

use Magento\Framework\Code\ValidationException;
use Magento\Framework\Code\ValidatorInterface;

class TypeDuplication implements ValidatorInterface
{
    /**
     * Name of the suppress warnings annotation.
     */
    const SUPPRESS_ANNOTATION = 'SuppressWarnings';

    const TYPE_DUPLICATIONS = 'Magento.TypeDuplication';

    /**
     * @var \Magento\Framework\Code\Reader\ArgumentsReader
     */
    protected $_argumentsReader;

    /**
     * @param \Magento\Framework\Code\Reader\ArgumentsReader $argumentsReader
     */
    public function __construct(\Magento\Framework\Code\Reader\ArgumentsReader $argumentsReader = null)
    {
        $this->_argumentsReader = $argumentsReader ?: new \Magento\Framework\Code\Reader\ArgumentsReader();
    }

    /**
     * Validate class
     *
     * @param string $className
     * @return bool
     * @throws ValidationException
     */
    public function validate($className)
    {
        $class = new \ReflectionClass($className);
        $classArguments = $this->_argumentsReader->getConstructorArguments($class, true);

        $arguments = $this->_getObjectArguments($classArguments);

        $typeList = [];
        $errors = [];
        foreach ($arguments as $argument) {
            $name = $argument['name'];
            $type = $argument['type'];
            if (in_array($type, $typeList)) {
                $errors[] = 'Multiple type injection [' . $type . ']';
            } elseif (isset($typeList[$name])) {
                $errors[] = 'Variable name duplication. [$' . $name . ']';
            }
            $typeList[$name] = $type;
        }

        if (!empty($errors)) {
            if (false == $this->_ignoreWarning($class)) {
                $classPath = str_replace('\\', '/', $class->getFileName());
                throw new ValidationException(
                    'Argument type duplication in class ' .
                    $class->getName() .
                    ' in ' .
                    $classPath .
                    PHP_EOL .
                    implode(
                        PHP_EOL,
                        $errors
                    )
                );
            }
        }
        return true;
    }

    /**
     * Get arguments with object types
     *
     * @param array $arguments
     * @return array
     */
    protected function _getObjectArguments(array $arguments)
    {
        $output = [];
        foreach ($arguments as $argument) {
            $type = $argument['type'];
            if (!$type || $type == 'array') {
                continue;
            }
            $reflection = new \ReflectionClass($type);
            if (false == $reflection->isInterface()) {
                $output[] = $argument;
            }
        }
        return $output;
    }

    /**
     * Check whether warning must be skipped
     *
     * @param \ReflectionClass $class
     * @return bool
     */
    protected function _ignoreWarning(\ReflectionClass $class)
    {
        $annotations = $this->_argumentsReader->getAnnotations($class);
        if (isset($annotations[self::SUPPRESS_ANNOTATION])) {
            return $annotations[self::SUPPRESS_ANNOTATION] == self::TYPE_DUPLICATIONS;
        }
        return false;
    }
}
