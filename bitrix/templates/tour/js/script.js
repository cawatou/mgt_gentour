$(document).ready(function(){
    var city_id = $('.cityAll .letterlist a.active').attr('data-citytv');
    load_tours(city_id);

    var selectCity = $('select.departure');
    var placeholder = selectCity.find('option:first');
    var option, id;
    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: {
            "get_city": 1
        },
        success: function (json) {
            JSON.parse(json, function (key, value) {
                if (key == 'id') id = value;
                if (key == 'name') {
                    option = '<option value="' + id + '">' + value + '</option>';
                    selectCity.append(option);
                }
            });
			
			
        }
    })
})

$('.departure').on('change', function () {
    var departure_id = $(this).val();
    var selectCountry = $('select.country');
    var placeholder = selectCountry.find('option:first');
    var option, id;

    selectCountry.html('');
    selectCountry.append(placeholder);

    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: {
            "departure_id": departure_id
        },
        success: function (json) {
            JSON.parse(json, function (key, value) {
                if (key == 'id') id = value;
                if (key == 'name') {
                    option = '<option value="' + id + '">' + value + '</option>';
                    selectCountry.append(option);
                    selectCountry.trigger('refresh')
                }
            });
        }
    })
});

$('.country').on('change', function () {
    var country_id = $(this).val();
    var selectRegions = $('select.regions');
    var placeholder = selectRegions.find('option:first');
    var option, id;

    selectRegions.html('');
    selectRegions.append(placeholder);

    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: {
            "country_id": country_id
        },
        success: function (json) {
            JSON.parse(json, function (key, value) {
                if (key == 'id') id = value;
                if (key == 'name') {
                    option = '<option value="' + id + '">' + value + '</option>';
                    selectRegions.append(option);
                    selectRegions.trigger('refresh')
                }
            });
        }
    })
});

$('#gen_tours input[type=submit]').on('click', function (e) {
    $('input.requestid').val(0);
    $('input.status').val(0);
})

$('#gen_tours').on('submit', function (e) {
    e.preventDefault();
    $('#gen_tours input[type=submit]').attr('disable');
    var data = $(this).serializeArray();

    var requestid = $('input.requestid').val();
    var status = $('input.status').val();
    if (requestid == '0') {
        data.push({name: 'get_requestid', value: 1});
        $.ajax({
            type: 'post',
            url: '/ajax/dev.php',
            data: data,
            success: function (res) {
                console.log(res);
                $('pre.result').html(res);
                $('input.requestid').val(res);
                $('#gen_tours').submit();
            }
        })
    } else if (status != '100') {
        data.push({name: 'get_status', value: 1});
        check_status(data);
        status = $('input.status').val();
        $('pre.result').html(status);
        $('pre.result').append(' wait ..');

        var intervalID = setInterval(function () {
            if (status != '100') {
                check_status(data);
                status = $('input.status').val();
            } else {
                console.log('end');
                $('#gen_tours').submit();
                clearInterval(intervalID);
            }
        }, 10000);
    } else if (status == '100') {
        //Запрос результата
        var data = $(this).serializeArray();
        if($('#region_name').val() == '') var category = $(".departure option:selected").html() + " - " + $(".country option:selected").html() + "(" + $(".regions option:selected").html() + ")";
        var category = $(".departure option:selected").html() + " - " + $(".country option:selected").html() + "(" + $('#region_name').val() + ")";
        data.push({name: 'get_result', value: 1});
        data.push({name: 'cat_name', value: category});

        $.ajax({
            type: 'post',
            url: '/ajax/dev.php',
            data: data,
            success: function (res) {
                if (res != 'empty') {
                    console.log(res);
                    $('pre.result').html(res);
                    var data = $('#gen_tours').serializeArray();
                    data.push({name: 'cat_name', value: category});
                    new_items(data);
                } else {
                    $('pre.result').html('Туры не найдены');
                }
            }
        })

    }

});

// Селект отправления на главной
$('.city_departure').on('change', function () {
    var departure_id = $(this).val();
    load_tours(departure_id);
});

// Переход в карточку туров
$(document).on('click' ,'.hotdeals',function(){
    var url = $(this).attr('data-url');
    //console.log(url);
    window.location.href = url;
});

// Заполнение модального окна (Оплатить онлайн)
$('.payonline').on('click', function(){
    var tourvisor_id = $(this).parent().attr('data-tourvid');
    var bx_id = $(this).parent().attr('data-bxid');
    var bxt_id = $(this).closest('.tabwrap').data('id');
    $.ajax({
        type: 'post',
        url: '/ajax/modal_tour.php',
        data: {
            'pay_online': 1,
            'tourid': tourvisor_id,
            'bxid': bx_id,
            'bxtid': bxt_id
        },
		beforeSend: function(){
			$('#buyme .modal-body').html('<div style="text-align:center"> <img src="/bitrix/templates/tour/images/89.gif"> </div>');
		},
        success: function(res){
            $('#buyme .modal-body').html(res);
        }
    })
})

// Заполнение модального окна (Оплатить в офисе)
$('.payoffice').on('click', function(){
    var tourvisor_id = $(this).parent().attr('data-tourvid');
    var bx_id = $(this).parent().data('bxid');
    var bxt_id = $(this).closest('.tabwrap').data('id');
	if(tourvisor_id){$('input[name=tourid]').val(tourvisor_id)}else{$('input[name=tourid]').val(bx_id)}
	$.ajax({
        type: 'post',
        url: '/ajax/modal_tour.php',
        data: {
            'pay_office': 1,
            'tourid': tourvisor_id,
            'bxid': bx_id,
            'bxtid': bxt_id
        },
		beforeSend: function(){
			$('#order .modal-body .form-order-info').html('<div style="text-align:center"> <img src="/bitrix/templates/tour/images/89.gif"> </div>');
		},
        success: function(res){
            $('#order .modal-body .form-order-info').html(res);
        }
    })
})


$(document).on('click','#add_hotel_group button',function(e){
    var group = $("#add_hotel_group select").val();
    if(group != 0) {
        $('#hgroup').val(group);
        var data = $("#hotel_data").serialize();

        $.ajax({
            type: 'post',
            url: '/ajax/hotel_group.php',
            data: data,
            success: function(res){
                if(res == 'add') alert("Добавлено");
                if(res == 'update') alert("Обновлено");
            }
        })
    }
})

/*=========================================== FUNCTIONS ====================================================*/

function check_status(data) {
    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: data,
        success: function (res) {
            $('input.status').val(res);
            $('pre.result').html(res);
            $('pre.result').append('% wait ..');
        }
    })
}

function new_items(data) {
    var departure_name = $(".departure option:selected").html();
    data.push({name: 'new_items', value: 1});
    data.push({name: 'departure_name', value: departure_name});
    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: data,
        success: function (res) {
            $('pre.result').html('Туры добавлены.');
        }
    })
}

function load_tours(city_id){
    /*Загрузка первых 4 ех туров в витрину*/
    $.ajax({
        type: 'post',
        url: '/ajax/dev.php',
        data: {
            "load_tour": 1,
            "showcase": 1,
            "city_id":  city_id,
        },
        success: function (res) {
            $('.showcase_tours .gridview').remove();
            $('.showcase_tours .listview').remove();
            $('.showcase_tours .hotindex').append(res);
			
			

            /*Загрузка остальных туров в витрину (в виде слайдера)*/
            $.ajax({
                type: 'post',
                url: '/ajax/dev.php',
                data: {
                    "load_tour": 1,
                    "remaining_tours": 1,
                    "city_id": city_id,
                },
                success: function (res) {
                    $('.remaining_tours').remove();
                    $('.showcase_tours').after(res);

                    /* ============= СЮДА ============*/
					$.fn.fullpage.reBuild();
					$('.hot-carusel2').owlCarousel({
						loop:false,
						margin:20,
						dots:false,
						nav:true,
						center: false,
						responsive:{
							0:{
								items:2
							},
							600:{
								items:2
							},
							1000:{
								items:4
							}
						}
					});
					hblkhvr();
                }
            })
            /*Загрузка туров из соседних городов*/
            $.ajax({
                type: 'post',
                url: '/ajax/dev.php',
                data: {
                    "load_siblings": 1,
                    "city_id":  city_id,
                },
                success: function(res){
                    if(res == ''){
                        $('.sibling_tours').css('display', 'none');
                    }else{
                        $('.sibling_tours').css('display', 'table');
                        $('.sibling_tours .hotturblock').remove();
                        $('.sibling_tours .hotturblock-mobile').remove();
                        $('.sibling_tours div').append(res);
                    }
					$.fn.fullpage.reBuild();
                }
            })
			$('.hotdeals').css('opacity',1);
			hblkhvr();
            $('.gridview').owlCarousel({
                loop:false,
                margin:20,
                dots:false,
                nav:true,
                center: false,
                responsive:{
                    0:{
                        items:2
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:4
                    }
                }
            });
           

        }
    })
}


$(document).on("change","#hotel_groups .group", function(){
	findRow();
});
$(document).on("change","#hotel_groups .departure", function(){
	findRow();
});
$(document).on("change","#hotel_groups .regions", function(){
	findRow();
});
$(document).on("change","#hotel_groups .hstars", function(){
	findRow();
});

function findRow(){
	$(".srows").each(function(){
		a=1;b=1;c=1;d=1;
		var gr = $(this).data('group');
		var cu = $(this).data('country');
		var re = $(this).data('regions');
		var st = $(this).data('stars');
		
		sgr = $("#hotel_groups select[name=group]").val();
		scu = $("#hotel_groups select[name=departure]").val();
		sre = $("#hotel_groups select[name=regions]").val();
		sst = $("#hotel_groups select[name=stars]").val();
		
		if(sgr!=0) if(gr==sgr) a=1; else a=0;
		if(scu!=0) if(cu==scu) b=1; else b=0;
		if(sre!=0) if(re==sre) c=1; else c=0;
		if(sst!=0) if(st==sst) d=1; else d=0;
		
		if(a&&b&&c&&d) $(this).show(); else $(this).hide();
	});
}