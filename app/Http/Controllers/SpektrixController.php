<?php

namespace App\Http\Controllers;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SpektrixController extends Controller
{

    protected $client;
    public $today;
    private $apiUrl = 'https://system.spektrix.com/apitesting/api/v3/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' =>$this->apiUrl
        ]);
        $this->today = date('Y-m-d');
    }

    public function getEvents()
    {

        $data = $this->getAllEvents();
        $events = array();

        if (is_array($data) && !empty($data)) {

            foreach ($data as $event) {

                $eventDates = $this->getEventInstances($event['id']);

                $events[$event['id']] = $event;

                if (is_array($eventDates) && !empty($eventDates)) {

                    $limit = 0;

                    foreach ($eventDates as $instance) {

                        if ($limit <= 5) {
                            $tickets = $this->getInstanceStatus($instance['id']);
                            //    $events[$instance['event']['id']]['dates']['availability'] = $tickets;
                            $instance['availability'] = $tickets;
                            $instance['dateStartFormatted'] = $this->dateFormat($instance['start']);
                            $events[$instance['event']['id']]['dates'][$instance['id']] = $instance;
                        }

                        $limit++;
                    }
                }
            }
        }

        return view('home', ['data' => $events]);
    }

    private function dateFormat(string $date)
    {

        if (strpos($date, 'T') !== false) {

            $dateArr = explode('T', $date);

            if (is_array($dateArr)) {

                $dateTime = new DateTime($dateArr[0]);
                return $dateTime->format('d/m/y');
            } else {

                return false;
            }
        } else {

            return false;
        }
    }

    public function getAllEvents()
    {

        return $this->get('events?instanceStart_from=' . $this->today . '&onSale=true');
    }

    private function getEventInfoById(string $eventId)
    {

        return $this->get('events/' . $eventId);
    }

    private function getEventInstances(string $eventId)
    {

        return $this->get('events/' . $eventId . '/instances?start_from=' . $this->today . '');
    }

    private function getInstancePricing(string $instanceId)
    {

        return $this->get('instances/' . $instanceId . '/price-list');
    }

    private function getInstanceStatus(string $instanceId)
    {

        return $this->get('instances/' . $instanceId . '/status');
    }

    protected function get(string $endpoint)
    {

        try {

            $request = $this->client->request('GET', $endpoint);
            $response = $request->getBody();
            $data = json_decode($response, TRUE);
            return $data;

        } catch (RequestException $e) {

            $error = $e->getMessage();
            dd($error);
        }

       
    }
}
