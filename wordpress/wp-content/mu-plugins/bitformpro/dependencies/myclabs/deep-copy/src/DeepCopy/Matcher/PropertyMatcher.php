<?php
/**
 * @license MIT
 *
 * Modified on 30-June-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace BitCode\BitFormPro\Dependencies\DeepCopy\Matcher;

/**
 * @final
 */
class PropertyMatcher implements Matcher
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $property;

    /**
     * @param string $class    Class name
     * @param string $property Property name
     */
    public function __construct($class, $property)
    {
        $this->class = $class;
        $this->property = $property;
    }

    /**
     * Matches a specific property of a specific class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return ($object instanceof $this->class) && $property == $this->property;
    }
}
