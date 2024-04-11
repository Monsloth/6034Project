<?php

namespace App\Http\Controllers;

use App\Http\Controllers\console;
use App\Models\SearchPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TravelIdea;
use Illuminate\Support\Facades\DB;

class SearchPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('SearchPage.index');
    }

    public function searchkeyword_by_destination(Request $request)
    {
        $search_keyword = $request->input('search_keyword');
        $destinations = TravelIdea::where('destination', 'LIKE', "%$search_keyword%")
            ->distinct()
            ->get(['destination']);
        return response()->json($destinations);
    }

    public function searchkeyword_by_tags(Request $request)
    {
        $search_keyword = $request->input('search_keyword');
        $destinations = TravelIdea::where('tags', 'LIKE', "%$search_keyword%")
            ->distinct()
            ->get(['tags', 'destination']);
        return response()->json($destinations);
    }


    public function searchTravelIdeas(Request $request)
    {
        $search_keyword = $request->input('search_keyword');
        $results = TravelIdea::where('destination', 'LIKE', "%$search_keyword%")
            ->orWhere('tags', 'LIKE', "%$search_keyword%")
            ->get(['title', 'destination', 'tags', 'user_name']);

        return response()->json($results);
    }






    public function getCityCode(Request $request)
    {
        $cityName = $request->input('cityName');
        $cityCode = DB::table('city_code')->where('city', $cityName)->first()->citycode ?? 'Not Found';

        return response()->json(['cityCode' => $cityCode]);
    }

    public function getHotels(Request $request)
    {
        $accessToken = 'DCbpQpv1CpBFGfgnPvBUtWIVATw9'; // 
        $cityCode = $request->query('cityCode'); // get cityCode
        $departureDate = $request->query('departureDate');

        $hotelListResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get("https://test.api.amadeus.com/v1/reference-data/locations/hotels/by-city", [
                    'cityCode' => $cityCode,
                    'radius' => "2",
                    'radiusUnit' => 'KM',
                    'hotelSource' => 'ALL'
                ]);

        if ($hotelListResponse->successful()) {
            $hotelsData = $hotelListResponse->json()['data'];

            foreach ($hotelsData as &$hotel) {
                // 对于每个酒店，获取价格信息
                $hotelId = $hotel['hotelId'];
                $hotelPriceResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken
                ])->get("https://test.api.amadeus.com/v3/shopping/hotel-offers", [
                            'hotelIds' => $hotelId,
                            'adults' => 1,
                            'checkInDate' => $departureDate,
                            'roomQuantity' => 1,
                            'paymentPolicy' => 'NONE',
                            'bestRateOnly' => true
                        ]);

                if ($hotelPriceResponse->successful()) {
                    // 假设我们只关心第一个offer
                    $offers = $hotelPriceResponse->json()['data'][0]['offers'] ?? null;
                    if ($offers && count($offers) > 0) {
                        $hotel['price'] = $offers[0]['price']['total'];
                    }
                }
            }

            // 返回带价格的酒店信息
            return response()->json([
                'totalHotels' => count($hotelsData),
                'hotels' => $hotelsData
            ]);
        }

        return response()->json([
            'message' => 'Failed to retrieve hotels'
        ], 400);
    }

    public function searchFlights(Request $request)
    {
        $departureCode = $request->query('originLocationCode');
        $destinationCode = $request->query('destinationLocationCode');
        $departureDate = $request->query('departureDate');

        $accessToken = 'DCbpQpv1CpBFGfgnPvBUtWIVATw9'; // 你的API访问令牌
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://test.api.amadeus.com/v2/shopping/flight-offers", [
                    'originLocationCode' => $departureCode,
                    'destinationLocationCode' => $destinationCode,
                    'departureDate' => $departureDate,
                    'adults' => 1,
                    'nonStop' => false,
                    'max' => 5,
                ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'Failed to retrieve flights'], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchPage $searchPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchPage $searchPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchPage $searchPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchPage $searchPage)
    {
        //
    }
}
