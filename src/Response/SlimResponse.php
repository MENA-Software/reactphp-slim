<?php
declare(strict_types=1);
/**
 * Response adapter class file.
 * It performs the setup of a reactPHP response and finishes the communication
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7.0
 *
 * @package    reactSlim
 * @subpackage reactSlim
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @copyright  (c) 2016, Moisés Barquín <moises.barquin@gmail.com>
 * @version    GIT: $Id$
 */
namespace mbarquin\reactSlim\Response;

use React\Http\Response as ReactResponse;
use Slim\Http\Response as SlimPHPResponse;
use Psr\Http\Message\ResponseInterface as PsrHttpResponse;

/**
 * Response adapter class
 * It performs the setup of a reactPHP response and finishes the communication
 */
class SlimResponse implements ResponseInterface
{
    /**
     * It performs the setup of a reactPHP response from a SlimPHP response
     * object and finishes the communication
     *
     * @param ReactResponse    $reactResp    ReactPHP native response object
     * @param PsrHttpResponse  $slimResponse SlimPHP native response object
     * @param bool             $endRequest   If true, response flush will be finished
     *
     * @return void
     */
    static function setReactResponse(
        ReactResponse $reactResp,
        PsrHttpResponse $slimResponse,
        bool $endRequest = false
    ) {
        $headers = static::reduceHeaders($slimResponse->getHeaders());
        $reactResp->writeHead($slimResponse->getStatusCode(), $headers);

        $reactResp->write($slimResponse->getBody());

        if ($endRequest === true) {
            $reactResp->end();
        }
    }

    /**
     * Reduces slim headers array to be used on reactPHP
     *
     * @param array $headersArray Headers array given by slim
     *
     * @return array Ready 4 reactPHP array
     */
    static public function reduceHeaders(array $headersArray) :array
    {
        $auxArray = [];
        foreach ($headersArray as $name => $value) {
            $myContent = '';
            foreach($value as $text) {
                $myContent .= $text;
            }
            $auxArray[$name] = $myContent;
        }

        return $auxArray;
    }

    /**
     * Returns a new Slim response object instance
     *
     * @return SlimPHPResponse
     */
    static public function createResponse() :SlimPHPResponse
    {
        return new SlimPHPResponse();
    }
}
