<?php

namespace App\Http\Controllers;

use App\Exceptions\NotProcessed;
use App\Exceptions\RemoteServerProblem;
use Illuminate\Http\Request;
use Guzzle\Service\Client;

class MoviesController extends Controller
{
    const DATA_SOURCE_URL = 'https://raw.githubusercontent.com/Omertron/api-imdb/master/JSON/chart-top.json';

    private $allowedField = ['title', 'rating', 'year', 'num_votes'];

    /**
     * @return Response
     */
    public function getMovies(Request $request)
    {
        $limit = intval($request->query('limit') ? : 10);
        $offset = intval($request->query('offset') ? : 0);

        $movies = $this->getData();
        
        return response()->json([
            'total' => count($movies),
            'list'  => array_slice($movies, $offset, $limit)
        ]);
    }

    protected function getData() {
        $client = new Client();
        $response = $client->get(static::DATA_SOURCE_URL)->send();

        if(200 !== $response->getStatusCode()) {
            throw new RemoteServerProblem('Data is not available');
        }

        return $this->convertData(json_decode($response->getBody(), true));
    }

    protected function convertData(array $data) {
        if(!@$data['data']['list']['list']) {
            throw new NotProcessed('Bad data from remote server');
        }

        $moviesList = $data['data']['list']['list'];
        foreach ($moviesList as $position => &$movie) {
            $this->filter($movie);
            $movie['position'] = $position + 1;
        }
        return $moviesList;
    }

    protected function filter(&$movie) {
        $movie = array_intersect_key($movie, array_flip($this->allowedField));
    }
}