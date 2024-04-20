{{-- resources/views/search_page.blade.php --}}
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
<link rel="stylesheet" href="{{ asset('css/search_page.css') }}">

<body>
<!-- search box  part 1-->
<form id="search-form" action="#" method="POST">
    @csrf
    <select id="search_type">
        <option value="destination">Destination</option>
        <option value="tags">Tags</option>
    </select>
    <div class="searchbar">
        <input type="text" id="search_keyword" placeholder="Enter keyword...">
        <button type="button" id="search-btn">Search</button>
    </div>
</form>

<div class="content-area">

    <div id="ideas" class="column">
        <h2>Local Search Client-Side Results</h2>
        <div id="search_results" ></div>
    </div>

    <div id="hotel" class="column">
        <h2>Hotel Search</h2>
        <div id="total_hotels"></div>
        <div id="hotel_list_results"></div>
    </div>


    <div id="flight" class="column">
        <h2>Flight Search</h2>
        <div id="flight_search_results">
        <div id="search_area_flight">
            departure date<input type="date" id="departureDate" value="2024-05-02">
            <input type="text" id="departure" value="Paris" placeholder="select your departure place...">
            originLocationCode:<div id="originLocationCode"></div>
            <input type="text" id="destination" placeholder="select your destination place...">
            destinationLocationCode:<div id="destinationLocationCode"></div>
            <button type="button" id="flight_search">Search</button>
        </div>
        </div>
</div>


</body>



<script>
    $(document).ready(function(){
        var searchType = $('#search_type').val(); // 初始搜索类型
        setupTypeahead(searchType); // 初始化Typeahead

        // 当搜索类型改变时更新Typeahead
        $('#search_type').change(function() {
            searchType = $(this).val();
            setupTypeahead(searchType);
        });

        function setupTypeahead(searchType) {
            var bloodhoundSource = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/searchkeyword_by_' + searchType + '?search_keyword=%QUERY',
                    wildcard: '%QUERY',
                    transform: function(response) {
                        // 依据返回的数据格式进行适当的处理
                        return $.map(response, function(item) {
                            if (searchType === 'destination') {
                                return { value: item.destination };
                            } else { // tags
                                return { value: item.tags }; // 如果是tags搜索，我们仍然返回目的地作为提示，这里可能需要调整以匹配你的具体需求
                            }
                        });
                    }
                }
            });

            // 初始化Typeahead
            $('#search_keyword').typeahead('destroy').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: searchType,
                display: 'value',
                source: bloodhoundSource
            });
        };
    })

    $('#search-btn').on('click', function(){
        var search_keyword = $('#search_keyword').val();
        var searchType = $('#search_type').val();

        if (searchType === 'destination') {
            // 如果搜索类型为destination，直接将搜索词赋值给目的地输入框
            $('#destination').val(search_keyword);
            console.log("search which ideas"+search_keyword)
        } else {
            // 如果搜索类型为tags，需要查询数据库获取对应的目的地并赋值
                $.ajax({
                    url: '/searchkeyword_by_tags',
                    type: 'GET',
                    data: {search_keyword: search_keyword},
                    success: function(data) {
                        // 假设我们用第一个返回结果的目的地
                        var destination = data[0] ? data[0].destination : '';
                        $('#destination').val(destination);
                        console.log("get destination"+$('#destination').val());
                    }
                });
            };
        $.ajax({
            url: '/searchTravelIdeas',
            type: 'GET',
            data: {search_keyword: search_keyword},
            success: function(data){
                console.log("find ideas in process")
                $('#search_results').empty();
                $.each(data, function(index, item){
                    $('#search_results').append(
                        '<div>' +
                        '<span class="index">' +"id"+ (index + 1) + '. </span>' +
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


            var departureDate = $('#departureDate').val(); 
            var cityName = $('#destination').val();
            $.ajax({
                url: '/get_city_code',
                type: 'GET',
                data: { cityName: cityName },
                success: function(data) {
                    var cityCode = data.cityCode;
                    console.log("search destin cityname"+cityName)
                    $('#destinationLocationCode').text(cityCode);
                    console.log($('#destination').val());
                    getHotels(cityCode, departureDate);
                },
                error: function(error) {
                    console.error("Error fetching city code:", error);
                }
            });
        // get depature citycode


            
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
                                '<span class="index">' +"id"+ (i + 1) + '. </span>' +
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
                    },
                });
            }
    })
</script>


<script>
    $(document).ready(function(){


        
        $('#flight_search').on('click', function(){
            var departureName = $('#departure').val();
            var destinationName = $('#destination').val();

            // if people want to change their destination or departure place different from the search keyword
            $.ajax({
                        url: '/get_city_code',
                        type: 'GET',
                        data: { cityName: departureName },
                        success: function(data) {
                            var originLocationCode = data.cityCode;
                            $('#originLocationCode').text(originLocationCode);
                        }
            });
            $.ajax({
                        url: '/get_city_code',
                        type: 'GET',
                        data: { cityName: destinationName },
                        success: function(data) {
                            var destinationLocationCode = data.cityCode;
                            $('#destinationLocationCode').text(destinationLocationCode);
                        }
            });


            // Check if either input is empty
            if (departureName === '' || destinationName === '') {
                alert('Please fill in both departure and destination fields.');
                return; // Stop the function from running further
            } else {

            };


            // get function param
            var destinationLocationCode = $('#destinationLocationCode').text();
            var departureDate = $('#departureDate').val();
            var originLocationCode = $('#originLocationCode').text();
                 // get departure citycode
            console.log("originLocationCode"+originLocationCode);
            // api param
            var accessToken = "GJZag6QXqehjITxCrleWrpUnatMg";
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