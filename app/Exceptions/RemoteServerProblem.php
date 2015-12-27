<?php
/**
 * @Author  Roman Valihura
 * @Date: 12/27/15
 * @Time: 11:16 PM
 */

namespace App\Exceptions;


class RemoteServerProblem extends \HttpException {
    const CODE = 503;
    public function __construct($message) {
        parent::__construct($message, static::CODE);
    }
}