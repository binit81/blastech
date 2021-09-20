
$(document).ready(function() {
    
        $('#state').on('change', function() {
            var state_ID = $(this).val();
            if(state_ID) {
                $.ajax({
                    url: '/findCityWithStateID/'+state_ID,
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data) {
                        //console.log(data);
                      if(data){
                        $('#city').empty();
                        $('#city').focus;
                        $('#city').append('<option value="">-- Select City --</option>'); 
                        $.each(data, function(cities_id, value){
                        $('select[name="city"]').append('<option value="'+ value.cities_id +'">' + value.city+ '</option>');
                    });
                  }else{
                    $('#city').empty();
                  }
                  }
                });
            }else{
              $('#city').empty();
            }
        });
    });
    