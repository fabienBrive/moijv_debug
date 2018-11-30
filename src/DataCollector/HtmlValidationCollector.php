<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/11/2018
 * Time: 10:08
 */

namespace App\DataCollector;


use HtmlValidator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class HtmlValidationCollector implements DataCollectorInterface
{
    private $result;

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     * @return mixed
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $content = $response->getContent();
        $contentType = $response->headers->get('Content-Type');
        if (strpos($contentType, 'text/html') ===0) {
            $validator = new Validator();

            $this->result = [
                'messages' => [
                    'test Message 1',
                    'test Message 2'
                ]
            ];

        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app.html.validation.collector';
    }

    /**
     * @return mixed
     */
    public function reset()
    {
        // TODO: Implement reset() method.
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

}