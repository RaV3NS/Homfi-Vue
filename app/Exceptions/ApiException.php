<?php declare(strict_types=1);

/**
 * Api Exception
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Exceptions
 */

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ApiException
 */
class ApiException extends Exception implements HttpExceptionInterface
{
    /**
     * Default response code.
     *
     * @var int
     */
    private $statusCode = 400;

    /**
     * The list or errors
     *
     * @var mixed
     */
    private $errors;

    /**
     * ApiException constructor.
     *
     * @param mixed $errors
     */
    public function __construct($errors, int $statusCode = 400)
    {
        parent::__construct('The given data is invalid.');

        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    /**
     * Get status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set status code.
     *
     * @return ApiException
     */
    public function setStatusCode(int $statusCode): ApiException
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Returns response headers.
     *
     * @return string[] Response headers
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * Get list of errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
