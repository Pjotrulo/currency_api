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
    #[Route('/add/{currency}/{amount}', name: 'app_exchange_rates_add')]
    public function add(ExchangeRatesRepository $repository, string $currency, float $amount): JsonResponse
    {
        if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {
            
            $date = new DateTime('now');

            $data = $repository->findByCurrencyAndDate($currency, date_format($date, "Y-m-d"));

            $exchangeRates = new ExchangeRates();
            $exchangeRates->setCurrency($currency);
            $exchangeRates->setDate($date);
            $exchangeRates->setAmount(number_format($amount, 2, '.'));

            if (!$data) {
                $repository->save($exchangeRates, true);
                return $this->json("Added succesfully");
            } else if ($data) {
                return $this->json("Value already added.");
            }

        } else {
            return $this->json("Currency ".$currency." not supported!! Choose GBP, USD or EUR");
        }
    }

    #[Route('/getCurrency/{currency}/{date}', name: 'app_exchange_rates_get')]
    public function getCurrency(ExchangeRatesRepository $repository, string $currency, string $date): JsonResponse
    {
        if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {

            $data = $repository->findByCurrencyAndDate($currency, $date);

            if(!$data) {
                return $this->json("Wrong date.");
            } else if($data) {
                return $this->json($data[0]);
            }

        } else {
            return $this->json("Currency ".$currency." not supported!! Choose GBP, USD or EUR");
        }
    }

    #[Route('/getAllCurrency/{date}', name: 'app_exchange_rates_getAll')]
    public function getAllCurrency(ExchangeRatesRepository $repository, string $date): JsonResponse
    {
        $data = $repository->findByDate($date);

        if(!$data) {
            return $this->json("Wrong date.");
        } else if($data) {
            return $this->json($data);
        }
    }
}
