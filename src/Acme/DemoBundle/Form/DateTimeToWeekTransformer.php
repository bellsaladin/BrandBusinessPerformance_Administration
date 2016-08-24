<?php
namespace Acme\DemoBundle\Form;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeToWeekTransformer implements DataTransformerInterface
{
    /**
     * Transforms an int to a DateTime.
     * POSSIBLE LOSS OF DATA
     *
     * @return string
     */
    public function transform( $val)
    {
        if(!$val) return ''; // this is mandatory, for first call when $val is yet NULL
        return $val->format('Y-m-d');;
    }

    /**
     * Transforms a DateTime to an Int.
     *
     * @param  string $string
     *
     * @return array
     */
    public function reverseTransform($val)
    {
        //var_dump($val); die('reverseTransform');

        return new \DateTime($val);
    }
}
