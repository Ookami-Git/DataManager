$(document).ready(function() {
    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 's' && e.repeat == false) {
            // Prevent the Save dialog to open
            e.preventDefault();
            // Place your code here
            if ($( "#save" ).length >= 1 && !$( "#save" ).hasClass( "disabled" )) {
                $('input, textarea').blur();
                save();
            }
        }
    });

    window.onbeforeunload = function(){
        if ($( "#save" ).length >= 1 && !$( "#save" ).hasClass( "disabled" )) {
            return 'Leave without save?';
        }
    };

    $('#connectionType').bind("change", function () {
        passwordProperties();
    })

    refreshInput();

    $('#newPass, #confirmNewPass').bind("change", function () {
        if ($('#newPass').val() != $('#confirmNewPass').val()) {
            $('#confirmNewPass').parent().addClass('error');
        } else {
            $('#confirmNewPass').parent().removeClass('error');
        }
        if ($('#newPass').val() == '') {
            $(this).removeClass('newvalue');
        }
    })
})

function refreshInput() {
    // Input constraints -- Refresh when add new 
    $(".nospace").on({
        keydown: function(e) {
          if (e.which === 32)
            return false;
        },
        change: function() {
          this.value = this.value.replace(/\s/g, "");
        }
    });
    $('input, select, textarea').bind("change input", function(){
        $(this).closest('.field').removeClass("error");
        this.classList.add('newvalue');
        $('#save').removeClass('disabled');
    });
}

function deletediv(button,type,name) {
    $('body').toast({
        message: 'Supprimer '+name+' ?',
        displayTime: 0,
        actions:    [{
            text: 'Oui',
            icon: 'trash',
            class: 'red',
            click: function() {
                button.closest(type).remove();
                $('#save').removeClass('disabled');
                $('body').toast({message:name+' a été supprimé'});
            }
            },{
            text: 'Non',
            class: 'blue',
            click: function() {
                $('body').toast({message:'Annulation de la suppression de '+name});
            }
        }]
    })
    ;
}

function genericAdd(divThis,parent,child,template,replace=false,inputParentName=false) {
    var divDest = divThis.closest(parent).find(child);
    if (divDest.length > 0) {
        if (replace) {divDest.empty();}
        var divTemplate = $(template);
        if (divTemplate.length > 0) {
            newDiv = $(divTemplate[0].innerHTML)
            divDest.append(newDiv);
            if (inputParentName) {
                var inputs = newDiv.find('input, select, textarea');
                for( x=0; x< inputs.length; x++ ) {
                    var input = inputs[x];
                    var name = $(input).attr('name');
                    if (name != '') {$(input).attr("name",inputParentName+name);}
                }
            }
            $('.ui.accordion').accordion();
            $('.checkbox').checkbox();
            $('.ui.dropdown').dropdown();
        }
    }
    refreshInput();
}

function alter_order(button,direction,parenttype) {
    var obj_to_move=button.closest(parenttype);
    switch (direction) {
        case "down":
            obj_to_move.insertAfter(obj_to_move.next(parenttype));
            break;
        case "up":
            obj_to_move.insertBefore(obj_to_move.prev(parenttype));
            break;
    }
}

function passwordProperties(){
    if ($('#connectionType').val() == "local") {
        $('#newuserpass').addClass('required').removeClass('disabled');
        var name = $('#newuserpass').find('input:first').attr('name');
        $('#newuserpass').find('input:first').attr("name",name.replace(':skip',''));
        
    } else {
        var name = $('#newuserpass').find('input:first').attr('name');
        $('#newuserpass').addClass('disabled').removeClass('required');
        if (!/.*:skip/i.test(name)) {
            $('#newuserpass').find('input:first').attr("name",name+":skip");
        }
    }
}