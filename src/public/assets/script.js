var variatinId=1;
var metaId=1;
$(document).ready(function(){

    /**
     * Ajax request to get product additional details
     */
    $(document).on("click",'#abc', function(){
        var id=$(this).val();
        $.ajax({
            url:"http://localhost:8080/product/getAdditionalInfo",
            type:"POST",
            data:{
                "id":id,
            },
            datatype:"JSON",
        }).done(function(data){
            var data=$.parseJSON(data)
            displayModal(data);
        });
    });

    /**
     * Function to add meta fields
     */
    $('#add-meta-fields').click(function(e){
        e.preventDefault();
        var fields=' <div class="row" id="meta'+metaId+'">\
                        <div class="col">\
                            <div class="form-floating mt-3">\
                                <input type="text" class="form-control" name="lableName'+metaId+'" placeholder="Price">\
                                <label for="price">lable name'+metaId+'</label>\
                            </div>\
                        </div>\
                        <div class="col">\
                            <div class="form-floating mt-3">\
                                <input type="text" class="form-control" name="lableValue'+metaId+'" placeholder="Name">\
                                <label for="stock">lable value</label>\
                            </div>\
                        </div>\
                    </div>';
        metaId++;
        $("#meta-fields").append(fields);
    });

    /**
     * Function to add variations
     */
    $('#add-variation-fields').click(function(e){
        e.preventDefault();
        var field='<div class="row" id="variation'+(variatinId)+'">\
                        <div class="col">\
                            <div class="form-floating mt-3" >\
                                <input type="text" class="form-control" name="variationKey'+variatinId+'" placeholder="variation name">\
                                <label for="stock">Variation Field'+variatinId+'</label>\
                            </div>\
                        </div>\
                        <div class="col">\
                            <div class="form-floating mt-3">\
                                <input type="text" class="form-control" name="variationValue'+(variatinId)+'" placeholder="variation value">\
                                <label for="stock">Value</label>\
                            </div>\
                        </div>\
                        <div class="col">\
                            <div class="form-floating mt-3">\
                                <input type="text" class="form-control" name="variationprice'+(variatinId)+'" placeholder="variation price">\
                                <label for="stock">Price</label>\
                            </div>\
                        </div>\
                    </div>';
        variatinId++;
        $("#variation-fields").append(field);
    });

    /**
     * get variations of the product
     */
    $("#product-info").change(function(){
        var productId=$(this).val();
        $.ajax({
            url:"http://localhost:8080/product/getVariation",
            type:"POST",
            data:{
                "id":productId,
            },
            datatype:"JSON",
        }).done(function(data){
            console.log(data)
            var data=$.parseJSON(data)
            var variation='<option val=""></option>';
            console.log(data)
            for (const key in data) {
                var value='';
                var name='';
                for (const k in data[key]) {
                    value+=''+k+':'+data[key][k]+',';
                    name += '#'+k+':'+data[key][k]+'';
                }
                variation += '<option value='+value.substring(0,[value.length-1])+'>'+name+'</option>';
            }
            // console.log(variation);
            $("#product-variation").html(variation);
        });
    });
});
/**
* Function to remove meta fields
*/
$( "#meta-fields" ).on( "click", "#remove-meta-fields", function(e) {
    e.preventDefault();
    $("#meta"+(--metaId)+"").remove();
  });

  /**
* Function to remove variation fields
*/
$( "#variation-fields" ).on( "click", "#remove-variation-fields", function(e) {
    e.preventDefault();
    $("#variation"+(--variatinId)+"").remove();
  });
/**
* Function to handle before submit form of product
* Sending no of meta fields and no of variations fields
*/
$("#add-product").click(function(e){
      e.preventDefault();
      var hiddenFields='<input type="hidden" name="noOfMetaFields" value="'+metaId+'">\
                        <input type="hidden" name="noOfVariationFields" value="'+variatinId+'">';

    $("#add-product").append(hiddenFields);
    $("#form-add-product").submit();
  })

/**
 * To display the additional information of product in modal
 * @param {array} data 
 */
function displayModal(data){
    var meta='';
    var variation='';
    for (const key in data.meta) {
        for (const k in data.meta[key]) {
            meta +='<p>'+k+':'+data.meta[key][k]+'</p>';
        }
    }
    for (const key in data.variation) {
        var temp='';
        for (const k in data.variation[key]) {
            temp+='<small>'+k+':'+data.variation[key][k]+'</small> ';
        }
        variation += '<p>'+temp+'</p>';
    }
    $("#model-meta-info").html(meta);
    $("#model-variation-info").html(variation);
}

/**
 * Date picker
 */
$("#order-date").change(function(){
    if($(this).val()=='Custom'){
        let dateFields='<div class="col">\
                        <label for="from_date">From</label>\
                        <input class="form-control" name="from_date" type="date">\
                    </div>\
                    <div class="col">\
                        <label for="to_date">To</label>\
                        <input class="form-control" name="to_date" type="date">\
                    </div>';
        
        $("#custom-date").html(dateFields);
    } else {
        $("#custom-date").html('');
    }
    
});

/**
 * update order status
 */
 $("#order-details").on('change','.order-status',function(){
    var selected = $(this).find('option:selected');
    var id = selected.data('orderid');
    var status=$(this).val();
    $.ajax({
        url:"http://localhost:8080/order/update",
        type:"POST",
        data:{
            "id":id,
            "status":status
        },
        datatype:"JSON",
    }).done(function(data){
       alert("Updated status");
    });
});
