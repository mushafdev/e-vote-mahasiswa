window.onTurnstileSuccess= function(code){
    $(':button').prop('disabled', false);
}

  function process1(param){
    $(param).addClass('buttonload');
    $(param).html('<i class="fa fa-sync fa-spin"></i> Processed...');
    $(param).prop('disabled',true);
    $(param).click(function(){return false;});
    }
    
    function process_done1(param,text){
    $(param).removeClass('buttonload');
    $(param).html(text);
    $(param).prop('disabled',false);
    $(param).click(function(){return true;});
    }
    