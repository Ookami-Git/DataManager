function save() {
    //Verify if all required field is set
    $(".required").filter(function() {
        if ($(this).find('input, select').val() == "" && ! /.*:skip/i.test($(this).find('input, select').attr('name'))) {
            toast_msg='Champ : '+$(this).closest('.field').find('label:first').text()+'<br>'+$(this).find('input, select').attr('name');
            $('body').toast({
                position: 'bottom attached',
                displayTime: 10000,
                title: 'Champ requis vide',
                message: toast_msg,
                showProgress: 'top',
                class: 'center aligned error',
                newestOnTop: true
            });
            return true;
        } else { 
            return false;
        };
    }).addClass("error");
    if ($(".error:visible").length > 0) {return false;}
    //Load data from form
    if (typeof document.saveAll === 'undefined' || document.saveAll != true) {
        var saveClass='.newvalue';
    } else {
        var saveClass='';
    }
    var data = $(`form :input${saveClass}`).serializeJSON({checkboxUncheckedValue: "false"})
    //Set parameters and url
    var theUrl;
    let params = new URLSearchParams();
    params.set("user",JSON.stringify(data));
    theUrl=`/api/user/update`;
    //PREPARE AND EXECUTE REQUEST
    var xmlhttpUpdate = new XMLHttpRequest();
    xmlhttpUpdate.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 3000,
                title: 'Enregistré',
                message: 'Vos modifications ont bien été enregistrés',
                showProgress: 'top',
                class: 'center aligned success',
                newestOnTop: true
            });
            $("input").removeClass('newvalue');
            $('#save').addClass('disabled');
        } 
        if (this.readyState == 4 && this.status != 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 30000,
                title: 'Erreur',
                message: `Edition - Vos modifications n'ont pas pu être enregistrées.<br>Code Retour : ${this.status}<br>Erreur : ${this.response}`,
                showProgress: 'top',
                class: 'center aligned error',
                newestOnTop: true
            });
        }
    };
    xmlhttpUpdate.open("PUT", theUrl);
    xmlhttpUpdate.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttpUpdate.send(params);
}