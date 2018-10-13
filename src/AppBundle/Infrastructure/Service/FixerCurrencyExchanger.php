<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Service;

use Assert\Assertion;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Money\Currency;
use Symfony\Component\HttpFoundation\Response;

class FixerCurrencyExchanger implements CurrencyExchangerInterface
{
    const API_KEY = '1e125e6e1264c1e29fcb4132d9eaa340';

    /** @var Currency */
    private $currency;

    /** @var Currency */
    private $toCurrency;

    /** @var string | integer */
    private $amount;

    /** @var array  */
    private $currencies = [];

    public function exchangedAmount($amount, Currency $currency, Currency $toCurrency)
    {
        if ($currency->getCode() === $toCurrency->getCode()) {
            return $this->amount;
        }

        if (!isset($this->currencies[$currency->getCode()])) {
            $this->currencies[$toCurrency->getCode()] = $this->convert($currency);
        }
        $this->currency = $currency;
        $this->toCurrency = $toCurrency;
        $this->amount = intval($amount);

        return $this->amount * $this->currencies[$currency->getCode()][$toCurrency->getCode()];
    }

    private function convert($base):array
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'http://data.fixer.io/api/latest?access_key=' . self::API_KEY . '&base='. $base);
        } catch (ClientException $e) {
            throw new \InvalidArgumentException('something bad happened, guzzle failed :' . $e->getMessage());
        }

        Assertion::eq($response->getStatusCode(), Response::HTTP_OK, 'something bad happened, fixer problem');
        $result = json_decode($response->getBody()->getContents(), true);
        Assertion::true($result['success'], 'fixer.io returned error: ' . $result['error']['type']);
        return $response->getBody()['rates'];
    }
}