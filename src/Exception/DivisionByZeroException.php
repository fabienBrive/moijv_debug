<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/11/2018
 * Time: 15:05
 */

namespace App\Exception;


use Throwable;

class DivisionByZeroException extends \Exception
{
    private $number;

    const DIVISION_BY_ZERO_EXCEPTOIN_CODE = 100;

    public function __construct($number, $code = 0, Throwable $previous = null)
    {
        parent::__construct("You cannot divide $number by 0", self::DIVISION_BY_ZERO_EXCEPTOIN_CODE, $previous);
        $this->number = $number;
    }
}