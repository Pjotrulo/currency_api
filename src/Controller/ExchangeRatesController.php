<?php

namespace App\Controller;

use App\Entity\ExchangeRates;
use App\Repository\ExchangeRatesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ExchangeRatesController extends AbstractController
{
    #[Route('/currency', name: 'add_currency', methods:'POST')]
    public function add(ExchangeRatesRepository $repository, Request $request): JsonResponse
    {
        $currency = $request->request->get('currency');
        $amount = $request->request->get('amount');
        $api_key = $request->request->get('api_key');

        if($api_key == 'GdfbSHRkw90w4qC') {
            if($currency === 'GBP' || $currency == 'USD' || $currency == 'EUR') {

                $date = new DateTime('now');
    
                $data = $repository->findByCurrencyAndDate($currency, date_format($date, "Y-m-d"));
    
                $exchangeRates = new ExchangeRates();
                $exchangeRates->setCurrency($currency);
                $exchangeRates->setDate($date);
                $exchangeRates->setAmount(number_format($amount, 2, '.'));
    
                if(!$data) {
                    $repository->save($exchangeRates, true);

                    return $this->json([
                        'status' => 'Added succesfully'
                    ], 200);
                } else if ($data) {
                    return $this->json([
                        'status' => 'Value already added'
                    ], 302);
                }
            } else {
                return $this->json([
                    'status' => 'Currency '.$currency.' not supported!! Choose GBP, USD or EUR'
                ], 400);
            }
        } else {
            return $this->json([
                'status' => 'You are not authorized for this feature.'
            ], 403);
        } 
    }

    #[Route('/currency/{api_key}/{currency}/{date}', name: 'get_currency', methods:'GET')]
    public function getCurrency(ExchangeRatesRepository $repository, string $api_key, string $currency, string $date): JsonResponse
    {
        if($api_key == '8V0BR1zTHBMGFf4' || $api_key == 'GdfbSHRkw90w4qC') {
            if($currency == 'GBP' || $currency == 'USD' || $currency == 'EUR') {

                $data = $repository->findByCurrencyAndDate($currency, $date);
    
                if(!$data) {
                    return $this->json([
                        'status' => 'Wrong date.'
                    ], 400);
                } else if($data) {
                    return $this->json($data[0]);
                }
    
            } else {
                return $this->json([
                    'status' => 'Currency '.$currency.' not supported!! Choose GBP, USD or EUR'
                ], 400);
            }
        } else {
            return $this->json([
                'status' => 'You are not authorized for this feature.'
            ], 403);
        }
    }

    #[Route('/currency/{api_key}/{date}', name: 'get_all_currency', methods:'GET')]
    public function getAllCurrency(ExchangeRatesRepository $repository, string $api_key, string $date): JsonResponse
    {
        if($api_key == '8V0BR1zTHBMGFf4' || $api_key == 'GdfbSHRkw90w4qC') {
            $data = $repository->findByDate($date);

            if(!$data) {
                return $this->json([
                    'status' => 'Wrong date.'
                ], 400);
            } else if($data) {
                return $this->json($data);
            }
        } else {
            return $this->json([
                'status' => 'You are not authorized for this feature.'
            ], 403);
        }
    }

    #[Route('/auth', name: 'auth', methods:'POST')]
    public function auth(Request $request): JsonResponse
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $role = $request->request->get('role');

        if($username == 'admin' && $password == 'zaq1@WSX' && $role == 'admin') {
            return $this->json("Your api key: GdfbSHRkw90w4qC");
        } else if($username == 'user' && $password == 'user' && $role == 'readonly') {
            return $this->json("Your api key: 8V0BR1zTHBMGFf4");
        }
    }
}
