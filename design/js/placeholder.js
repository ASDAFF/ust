jQuery(function($){

    $('input[type="text"]:not(.placeholder), textarea:not(.placeholder)').each(function(){
        var that = $(this);
        if (!that.val()) {
            that.data('holder', that.attr('placeholder'));
            that.val(that.data('holder'));
            that.attr('placeholder', '');
        } else if (that.data('error')) {
            that.val(that.data('error'));
        } else {
            that.val(that.attr('value'));
        }
    });
    

    $('input[type="text"]:not(.placeholder), textarea:not(.placeholder)').focus(function(){
        if ($(this).val() === $(this).data('holder')) {
            $(this).val('');
            $(this).addClass("act");
        } else if ($(this).val() === $(this).data('error')) {
            $(this).val('');
            $(this).addClass("act");
        }
    });
    $('input[type="text"]:not(.placeholder), textarea:not(.placeholder)').blur(function(){
        if ($(this).val() === "") {
            $(this).removeClass("act");
            $(this).val($(this).data('holder'));
        } else {
            $(this).addClass("act");
        }
    });

});