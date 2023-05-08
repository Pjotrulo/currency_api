<?php

namespace App\Controller;

use App\Entity\ExchangeRates;
use App\Repository\ExchangeRatesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ExchangeRatesController extends AbstractController
{
    #[Route('/add/{currency}/{amount}', name: 'app_exchange_rates')]
    public function add(ExchangeRatesRepository $repository, string $currency, float $amount): JsonResponse
    {
        if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {
            
            $date = new DateTime('now');

            $exchangeRates = new ExchangeRates();
            $exchangeRates->setCurrency($currency);
            $exchangeRates->setDate($date);
            $exchangeRates->setAmount(number_format($amount, 2, '.'));

            $repository->save($exchangeRates, true);

            return $this->json("Added data successfully!");
        } else {
            return $this->json("Currency ".$currency." not supported!! Choose GBP, USD or EUR");
        }
    }

    #[Route('/getCurrency/{currency}/{date}', name: 'app_exchange_rates')]
    public function getCurrency(ExchangeRatesRepository $repository, string $currency, string $date): JsonResponse
    {
        if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {

            $properties = $repository->findByCurrencyAndDate($currency, $date);

            return $this->json($properties[0]);
        } else {
            return $this->json("Currency ".$currency." not supported!! Choose GBP, USD or EUR");
        }
    }
}
