{{-- resources/views/search_page.blade.php --}}
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>


<!-- search box  part 1-->


<form id="search-form" action="#" method="POST">
    @csrf
    <input type="text" id="search_city" value="Boston" placeholder="Search destinations...">
    departure date<input type="date" id="departureDate" value="2024-05-02">
    <button type="button" id="search-btn">Search</button>
</form>

<div class="ideas">
    <h2>Local Search Client-Side Results</h2>
    <div id="search-results" ></div>
</div>

<div class="hotel">
    <h2>Hotel Search</h2>
    <div id="total_hotels"></div>
    <div id="hotel_list_results"></div>
</div>


<div class="flight">
    <h2>Flight Search</h2>
    <input type="text" id="departure" value="Paris" placeholder="select your departure place...">
    originLocationCode:<div id="originLocationCode"></div>
    <input type="text" id="destination" placeholder="select your destination place...">
    destinationLocationCode:<div id="destinationLocationCode"></div>
    <button type="button" id="flight_search">Search</button>
    <div id="flight_search_results">
</div>




<script>
    $(document).ready(function(){
        var destinations = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '/search_ideas?search_city=%QUERY',
                wildcard: '%QUERY',
                transform: function(response) {
                console.log("get data:", response); 
                // 假设返回的数据是对象数组，我们返回它以供 typeahead 使用
                return response.map(function(item) {
                    return item.destination; // 直接返回字符串数组
                });
            }
            }
        });

        $('#search_city').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'destinations',
            // display: 'value'
            source: destinations
        }).on('typeahead:selected', function(event, suggestion) {
            // fill blank with the corresponding value
            $(this).val(suggestion.value);
            // console.log("final value:", destinations); 
        });
        

        $('#search-btn').on('click', function(){
            $('#destination').val($('#search-input').val());
            var search_city = $('#search_city').val();
            $.ajax({
                url: '/fetch_destinations',
                type: 'GET',
                data: {search_city: search_city},
                success: function(data){
                    $('#search-results').empty();
                    $.each(data, function(index, item){
                        $('#search-results').append(
                            '<div>' +
                            '<p>Destination: ' + item.destination + '</p>' +
                            '<p>Title: ' + item.title + '</p>' +
                            '<p>Tags: ' + item.tags + '</p>' +
                            '<p>username: ' + item.user_name + '</p>' +
                            '</div>'
                        );
                    });
                }
            });

            // var hotelsearchurl = "https://test.api.amadeus.com/v3/shopping/hotel-offers?cityCode=" + cityCode

// get citycode/ destination code
            $('#destination').val($('#search_city').val());
            var departureDate = $('#departureDate').val(); 
            var cityName = $('#destination').val(); //
            $.ajax({
                url: '/get_city_code',
                type: 'GET',
                data: { cityName: cityName },
                success: function(data) {
                    var cityCode = data.cityCode;
                    $('#destinationLocationCode').text(cityCode);
                    console.log("after"+cityCode);
                    if(cityCode && departureDate) {
                        console.log("success hotel-part"+cityCode, departureDate)
                        getHotels(cityCode, departureDate);
                    } else {
                        console.log("fail"+cityCode, departureDate)
                        alert("Please enter both city code and check-in date.");
                    };
                },
                error: function(error) {
                    console.error("Error fetching city code:", error);
                }
            });
// get depature citycode
            var departureName = $('#departure').val();
            $.ajax({
                    url: '/get_city_code',
                    type: 'GET',
                    data: { cityName: departureName },
                    success: function(data) {
                        var originLocationCode = data.cityCode;
                        var destinationLocationCode = $('#destinationLocationCode').text();
                        $('#originLocationCode').text(originLocationCode);
                        console.log($('#originLocationCode').text());
                        if (!originLocationCode || !destinationLocationCode) {
                            alert('Please fill in both departure and destination fields.');
                            return;
                        } else {
                            console.log("flight-part"+originLocationCode, destinationLocationCode, departureDate);
                            // searchFlights(originLocationCode, destinationLocationCode, departureDate)
                        }
                    }
            });


            var departure = $('#departure').val();
            var destination = $('#destination').val();
            
            function getHotels(cityCode, departureDate) {
            // var apiUrl = '/get-hotels'; // Laravel 路由
            $.ajax({
                url: '/get_hotels',
                type: "GET",
                data: {
                    'cityCode': cityCode, // 确保这与 Laravel 控制器期待的参数名匹配
                    'departureDate': departureDate 
                },
                success: function(response) {
                    console.log("get-hotle function response"+response); // 用于调试
                    var totalHotels = response.totalHotels;
                    $('#total_hotels').text('Total Hotels: ' + totalHotels);

                    // 清空现有酒店列表
                    $('#hotel_list_results').empty();

                    // 遍历酒店数据并展示
                    $.each(response.hotels, function(i, hotel) {
                        var hotelElement = $('<div class="hotel">' +
                            '<h3>' + hotel.name + '</h3>' +
                            '<p>Distance: ' + hotel.distance.value + ' ' + hotel.distance.unit + '</p>' +
                            '<p>Address: ' + (hotel.address.line1 || 'N/A') +
                            ', ' + (hotel.address.city || 'N/A') +
                            ', ' + (hotel.address.region || 'N/A') +
                            ', ' + (hotel.address.postalCode || 'N/A') +
                            ' ' + (hotel.address.countryCode || 'N/A') + '</p>' +
                            '<p>Price: USD ' + (hotel.price || 'N/A') + '</p>' +
                            '</div>');

                        // 添加到页面中
                        $('#hotel_list_results').append(hotelElement);
                    });
                },
                error: function(error) {
                    console.error("Error: ", error);
                }
            });
        };

        });
    });

</script>


<script>
    $(document).ready(function(){
                 
        $('#flight_search').on('click', function(){
            // get param
            var destinationLocationCode = $('#destinationLocationCode').text();
            var departureDate = $('#departureDate').val();
            var originLocationCode = $('#originLocationCode').text();
                 // get departure citycode
            console.log("originLocationCode"+originLocationCode);
            // api param
            var accessToken = "XSJChwVmfESC28vzY0whc995AiaH";
            var flight_baseUrl = "https://test.api.amadeus.com/v2/shopping/flight-offers";
            var flight_params = new URLSearchParams({
                originLocationCode: originLocationCode,
                destinationLocationCode: destinationLocationCode,
                departureDate: departureDate, // 示例日期
                adults: "1",
                nonStop: "false",
                max: "5"
            });
            var flight_url = `${flight_baseUrl}?${flight_params.toString()}`;

            // get api
            $.ajax({
                url: flight_url,
                type: "GET",
                headers: {
                    'Authorization': 'Bearer ' + accessToken,
                },
                success: function(response) { 
                    $.each(response.data, function(i, flightOffer) {
                        var aircraftCode = flightOffer.itineraries[0].segments[0].aircraft.code;
                        var aircraftType = response.dictionaries.aircraft[aircraftCode];
                        var flightDuration = flightOffer.itineraries[0].duration;
                        var departureLocationCode = flightOffer.itineraries[0].segments[0].departure.iataCode;
                        var arrivalLocationCode = flightOffer.itineraries[0].segments[flightOffer.itineraries[0].segments.length - 1].arrival.iataCode;
                        var price = flightOffer.price.total;
                        var currency = flightOffer.price.currency;

                        var flightInfo = '<div>' +
                                '<h3>Flight ID: ' + flightOffer.id + '</h3>' +
                                '<p>Price: ' + price + ' ' + currency + '</p>' +
                                '<p>Flight type: ' + aircraftType + '</p>' +
                                '<p>Duration: ' + flightDuration + '</p>' +
                                '<p>departureLocation: ' + departureLocationCode + '</p>' +
                                '<p>arrivalLocation: ' + arrivalLocationCode + '</p>' +
                                '</div>';
                            $('#flight_search_results').append(flightInfo);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching flight data:", status, error);
                }
            });
        });
    });
</script>

<script>
    // function


</script>