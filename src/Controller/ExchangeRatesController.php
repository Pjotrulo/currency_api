<?php

namespace App\Controller;

use App\Entity\ExchangeRates;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ExchangeRatesController extends AbstractController
{
    #[Route('/add/{currency}/{amount}', name: 'app_exchange_rates')]
    public function add(ManagerRegistry $manager, string $currency, float $amount): JsonResponse
    {
        if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {
            $entityManager = $manager->getManager();

            $date = new DateTime('now');

            $exchangeRates = new ExchangeRates();
            $exchangeRates->setCurrency($currency);
            $exchangeRates->setDate($date);
            $exchangeRates->setAmount(number_format($amount, 2, '.'));

            $entityManager->persist($exchangeRates);
            $entityManager->flush();

            return $this->json("Added data successfully!");
        } else {
            return $this->json("Currency ".$currency." not supported!! Choose GBP, USD or EUR");
        }
    }
}
