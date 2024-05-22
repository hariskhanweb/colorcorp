$('#prodhasvariate').change(function() {
    let varient = $(this).val();
    if(varient=="1"){ $("#prodothrsection").css('display', 'block'); } else { $("#prodothrsection").css('display', 'none'); }
});

function RemoveGalleryImg(imgid){
    $('#gallryimgid').val(imgid);
    $("#cofirmgallModal").modal({backdrop: 'static', keyboard: false});
}
function RemoveSelImage(){
    var tokens = $('input[name=_token]').val();
    var imgid = $('#gallryimgid').val();
    var urlpath = $('#routepath').val();
    if($.trim(imgid)!=''){ 
        $("#cofirmgallModal").modal('hide');
        $.ajax({
            type: 'POST',
            url: urlpath,
            data: {_token:tokens, dataimgid:imgid},
            success:function(data){
                if(data['data']==1){ 
                    $('#pgalimg'+imgid).css('display', 'none');                    
                }
            }
        });
    } else{
        $("#cofirmgallModal").modal('hide');
    }
}

function RemoveProduct(productid){    
    if(productid!="" && productid!="0"){
        $("#delproductid").val(productid);
        $("#cofirmModal").modal({backdrop: 'static', keyboard: false});
    }
}
function SubmitDelfrm(){
    $("#cofirmModal").modal('hide');
    $("#productdeleteform").submit();
}

function ShowAttributeOpts(parentid){
    let psecid = "prodparentattr"+parentid;
    if($("#"+psecid).prop('checked') == true){
        $("#showattroption"+parentid).css('display', 'block');
    } else {
        $("#showattroption"+parentid).css('display', 'none');
    }
}

$('document').ready(function () {
    $("input[type=checkbox]").click(function(){
        var checkVal = $("input[type='checkbox']:checked").val();
        if(checkVal == 1) {
            $("#statusmodal .modal-body span").html("Enable");
            $("#status").val(checkVal);
            $("#category_id").val($(this).attr('data-id'));
        } else {
            $("#statusmodal .modal-body span").html("Disable");
            $("#status").val(0);
            $("#category_id").val($(this).attr('data-id'));
        }
    })

    $("#has_parent").change(function() {
        var selVal = $(this).val();
        // $(".parent-category-wrap").toggle();
        if(selVal == 1){
            $(".parent-category-wrap").show();
        } else {
            $(".parent-category-wrap").hide();
        }
    });

    $('.category-name').keyup(function(){
        let str = this.value;
        var newstr = str.replace('&', 'and');
        newstr = newstr.replace(/[&@\/\\#, +()$~%.'":*?<>{}]/g, '-');
        $('.category-slug').val(newstr.toLowerCase());
    });
});

function IsAlpha(str){return/^[a-zA-Z]*$/.test(str);}
function IsAlphaSpace(str){return/^[a-zA-Z ]*$/.test(str);}
function IsAlphaNum(str){return/^[a-zA-Z0-9]*$/.test(str);}
function IsAlphaNumWSpace(str){return/^[a-zA-Z0-9 ]*$/.test(str);}
function IsInteger(str){return/^\d+$/.test(str);}
function IsValidEmail(str){var ck_email=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;
return ck_email.test(str);}

function CheckAddProductValid(frm){
    isValid = true;
    $('#prodfrmalert').text(''); $('#prodfrmalert').css('display','none');
    if(document.getElementById('prodname').value==''){
      $('#prodfrmalert').css('display','block');
      $('#prodfrmalert').addClass('alert alert-danger');
      $("#prodfrmalert").html('Product name is required.');      
      $("#prodname").focus(); 
      isValid = false; return false;
    }
    if(document.getElementById('prodsku').value==''){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product sku is required.');  
        $("#prodsku").focus(); 
        isValid = false; return false;
    }
    var txtarval = $.trim(ckEditor("prodshortdesc").getData());
    if(txtarval ===''){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product short description is required.');  
        $("#prodshortdesc").focus(); 
        isValid = false; return false;
    }
    if(parseInt(txtarval.length) > 255){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product short description must not be greater than 255 characters.');  
        $("#prodshortdesc").focus(); 
        isValid = false; return false;
    }
    var txtarvallong = $.trim(ckEditor("prodlongdesc").getData());
    if(txtarvallong ===''){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product long description is required.');  
        $("#prodlongdesc").focus(); 
        isValid = false; return false;
    }
    if(document.getElementById('prodprice').value==''){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product price is required.');  
        $("#prodprice").focus(); 
        isValid = false; return false;
    }    
    if(document.getElementById('prodfeatureimg').value==''){
        $('#prodfrmalert').css('display','block');
        $('#prodfrmalert').addClass('alert alert-danger');        
        $("#prodfrmalert").html('Product featured image is required.');  
        $("#prodfeatureimg").focus(); 
        isValid = false; return false;
    }
    if(isValid==true){ document.getElementById(frm).submit(); }
}
function CheckEditProductValid(frm){
    isValid = true;
    $('#prodedtfrmalert').text(''); $('#prodedtfrmalert').css('display','none');
    if(document.getElementById('prodname').value==''){
      $('#prodedtfrmalert').css('display','block');
      $('#prodedtfrmalert').addClass('alert alert-danger');
      $("#prodedtfrmalert").html('Product name is required.');      
      $("#prodname").focus(); 
      isValid = false; return false;
    }
    if(document.getElementById('prodsku').value==''){
        $('#prodedtfrmalert').css('display','block');
        $('#prodedtfrmalert').addClass('alert alert-danger');        
        $("#prodedtfrmalert").html('Product sku is required.');  
        $("#prodsku").focus(); 
        isValid = false; return false;
    }
    var txtarval = $.trim(ckEditor("prodshortdesc").getData());
    if(txtarval ===''){
        $('#prodedtfrmalert').css('display','block');
        $('#prodedtfrmalert').addClass('alert alert-danger');        
        $("#prodedtfrmalert").html('Product short description is required.');  
        $("#prodshortdesc").focus(); 
        isValid = false; return false;
    }
    if(parseInt(txtarval.length) > 255){
        $('#prodedtfrmalert').css('display','block');
        $('#prodedtfrmalert').addClass('alert alert-danger');        
        $("#prodedtfrmalert").html('Product short description must not be greater than 255 characters.');  
        $("#prodshortdesc").focus(); 
        isValid = false; return false;
    }
    var txtarvallong = $.trim(ckEditor("prodlongdesc").getData());
    if(txtarvallong ===''){
        $('#prodedtfrmalert').css('display','block');
        $('#prodedtfrmalert').addClass('alert alert-danger');        
        $("#prodedtfrmalert").html('Product long description is required.');  
        $("#prodlongdesc").focus(); 
        isValid = false; return false;
    }
    if(document.getElementById('prodprice').value==''){
        $('#prodedtfrmalert').css('display','block');
        $('#prodedtfrmalert').addClass('alert alert-danger');        
        $("#prodedtfrmalert").html('Product price is required.');  
        $("#prodprice").focus(); 
        isValid = false; return false;
    }
    if(isValid==true){ document.getElementById(frm).submit(); }
}

var button = document.querySelector(".sidebar-toggle");
var box = document.querySelector("body");
if(button){
  button.addEventListener("click", function () {
    box.classList.toggle("left-side-menu-sm");
  });  
}

$(document).ready(function(){
      $(".category-search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        //console.log(value);
        $(".categories-wrapper div.grid-item").filter(function() {
          $(this).addClass('is-hidden');
          if($(this).attr('data-title').toLowerCase().indexOf(value) > -1){
            $(this).removeClass('is-hidden');
          }
        });
    });
});

$("#installation_address").change(function() {
    var selVal = $(this).val();
    // $(".parent-category-wrap").toggle();
    if(selVal == 1){
        $("#address_wrapper").show();

        $("#country-dd").attr('required', 'required');
        $("#state-dd").attr('required', 'required');
        $("#city").attr('required', 'required');
        $("#address").attr('required', 'required');
        $("#postcode").attr('required', 'required');
        $("#mobile_number").attr('required', 'required');
         $("#country-dd").val('');
        $("#state-dd").val('');
        $("#city").val('');
        $("#address").val('');
        $("#postcode").val('');
        $("#mobile_number").val('');

    } else {
        $("#address_wrapper").hide();
        $("#country-dd").removeAttr('required');
        $("#state-dd").removeAttr('required');
        $("#city").removeAttr('required');
        $("#address").removeAttr('required'); 
        $("#postcode").removeAttr('required');
        $("#mobile_number").removeAttr('required');
    }
});

function openSearchModal(type){
    $('#searchModal').show();
    $("#search_type").val(type);
    $('#searchModal h3 span').html(type);
}

function closeSearchModal() {
    $('#searchModal').hide();
    $("#search_key").val("");
}

$(document).ready(function(){
    $(".search-btn").on("click", function(e) {
      e.preventDefault();
      var value = $('.category-search').val();
      var tokens = jQuery('input[name=_token]').val();
      // $(".categories-wrapper").hide();
      $(".loader").show();
      
      $.ajax({
        method: "POST",
        url: "/search-category",
        data: {_token:tokens, text: value},
        dataType:'JSON',
        success: function(data) {
          if(data.success == 1){
            $(".loader").hide();

            if( data.result == '' ) {
                $("#category-wrapper").html('Category Not Found!');
            } else {
                $("#category-wrapper").html(data.result);
            }

            $('#category-wrapper').masonry();
            setTimeout(function() {
                var $container = $('#category-wrapper');
                $container.masonry('reloadItems');
                $container.masonry();
            }, 1000);
            /*setTimeout(function() {
                $(".loader").hide();
                $('.categories-wrapper').html(data.result);
                $(".categories-wrapper").show();
                $('#category-wrapper').masonry();
            }, 1000);*/
          }else{
            // console.log("hello");
            $(".loader").hide();
            $("#load-more-services").hide();
          }
        }
      });
    });
});

$(document).ready(function(){
    var page = 1;
    $(document).on("click", "#load-more-services", function(e) {
        e.preventDefault();
        page++;
        loadMoreCategories(page);
    });
});

function loadMoreCategories(page){
    // alert(page);
    $(".loader").show();
    var tokens = jQuery('input[name=_token]').val();
    var value = $('.category-search').val();
    $.ajax({
    method: "POST",
    url: "/load-more-category",
    data: {_token:tokens, text:value, page: page},
    dataType:'JSON',
    success: function(data) {
        // console.log(data);
      if(data.result){
        // console.log(data.result);
        $(".loader").hide();
        $("#category-wrapper").append(data.result);
        $('#category-wrapper').masonry();
        setTimeout(function() {
            var $container = $('#category-wrapper');
            $container.masonry('reloadItems');
            $container.masonry();
        }, 1000);
      }else{
        // console.log("hello");
        $(".loader").hide();
        $("#load-more-services").hide();
      }
    }
  });
}