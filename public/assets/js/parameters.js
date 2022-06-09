$(document).ready(function() {
    /* Désactive la touche ENTREE pour valider le formulaire */
    $(document).keypress(
        function(event){
          if (event.which == '13') {
            event.preventDefault();
          }
    });
});

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
    params.set("parameters",JSON.stringify(data));
    theUrl=`/api/parameters/update`;
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

function datamanagerAdd() {
    //Verify if all required field is set
    $(".required").filter(function() {
        if ($(this).find('input, select').val() == "" && ! /.*:skip/i.test($(this).find('input, select').attr('name'))) {
            toast_msg='Champ : '+$(this).closest('.field').find('label:first').text()+'<br>'+$(this).find('input select').attr('name');
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
    var data = $('form :input').serializeJSON({checkboxUncheckedValue: "false"})
    //Set parameters and url
    var theUrl;
    let params = new URLSearchParams();
    params.set("parameters",JSON.stringify(data));
    theUrl=`/api/parameters`;
    //PREPARE AND EXECUTE REQUEST
    var xmlhttpUpdate = new XMLHttpRequest();
    xmlhttpUpdate.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
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
    xmlhttpUpdate.open("POST", theUrl);
    xmlhttpUpdate.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttpUpdate.send(params);
}

function datamanagerRemove(button,id,type){
    $('body').toast({
        message: `Supprimer : ${type} ${id} ?`,
        displayTime: 0,
        actions:    [{
            text: 'Oui',
            icon: 'trash',
            class: 'red',
            click: function() {
                //Set parameters and url
                var theUrl;
                theUrl=`/api/parameters/${id}/${type}`;
                //PREPARE AND EXECUTE REQUEST
                var xmlhttpUpdate = new XMLHttpRequest();
                xmlhttpUpdate.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        button.closest('tr').remove();
                        $('body').toast({message:`${type} ${id} a été supprimé`});
                    } 
                    if (this.readyState == 4 && this.status != 200) {
                        $('body').toast({
                            position: 'bottom attached',
                            displayTime: 30000,
                            title: 'Erreur',
                            message: `Suppression - Vos modifications n'ont pas pu être effectué.<br>Code Retour : ${this.status}<br>Erreur : ${this.response}`,
                            showProgress: 'top',
                            class: 'center aligned error',
                            newestOnTop: true
                        });
                    }
                };
                xmlhttpUpdate.open("DELETE", theUrl);
                xmlhttpUpdate.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttpUpdate.send();
            }
            },{
            text: 'Non',
            class: 'blue',
            click: function() {
                $('body').toast({message:`Annulation de la suppression de ${type} ${id}`});
            }
        }]
    })
}

//--- Rundeck ---
function readRundeck() {
    var theUrl = `${document.baseUrl}/api/parameters/rundeck`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response && this.response != "null") {
                rundeck=JSON.parse(this.response)
                rundeck.instances.forEach(function(instance){
                    $("#addRdk").click();
                    $(".rdkInstance:last").val(instance.instance).change();
                    $(".rdkUrl:last").val(instance.url);
                    $(".rdkApi:last").val(instance.api);
                    $(".rdkProject:last").val(instance.project);
                    $(".rdkToken:last").val(instance.token);
                    if (instance.api_verify_ssl) {$(".rdkApiSsl:last").attr( 'checked', true );}
                    if (instance.hide.jobs_disabled) {$(".rdkJobsDisabled:last").attr( 'checked', true );}
                    if (typeof instance.hide.jobs !== 'undefined') {
                        instance.hide.jobs.forEach(function (job) {
                            $(".addHideJob:last").click();
                            $(".jobId:last").val(job);
                        })
                    }
                });
            }
            $('#save').addClass('disabled');
        }
        if (this.readyState == 4 && this.status != 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 30000,
                title: 'Erreur',
                message: "Erreur lors du chargement des données existante",
                showProgress: 'top',
                class: 'center aligned error',
                newestOnTop: true
            });
        }
    };
    xmlhttp.open("GET", theUrl);
    xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlhttp.send();
}

//--- Menu Editor---
function parentGrpPath(item) {
    var grpPath = 'menu[]';
    while (item.parent().closest('.itemGroup').length > 0) {
        grpPath += '[items][]';
        item=item.parent().closest('.itemGroup');
    }
    return grpPath;
}

function readMenu() {
    var theUrl = `${document.baseUrl}/api/parameters/menu`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response && this.response != "null") {
                menu=JSON.parse(this.response)
                menu.forEach(function(item){
                    readAddMenu(item);
                });
            }
            $('#save').addClass('disabled');
        }
        if (this.readyState == 4 && this.status != 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 30000,
                title: 'Erreur',
                message: "Erreur lors du chargement des données existante",
                showProgress: 'top',
                class: 'center aligned error',
                newestOnTop: true
            });
        }
    };
    xmlhttp.open("GET", theUrl);
    xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlhttp.send();
}

function readAddMenu(item,level=0) {
    switch (item.type) {
        case 'group':
            if (level == 0) {
                $('#addGrp').click();
            } else {
                $('.addGrp:last').click();
            }
            $('.groupName:last').val(item.name);
            if(typeof item.items != "undefined") {
                item.items.forEach(function(subItem) {
                    readAddMenu(subItem,++level);
                }) 
            }
            break;
        case 'link':
            if (level == 0) {
                $('#addLink').click();
            } else {
                $('.addLink:last').click();
            }
            $('.linkName:last').val(item.name);
            $('.linkPage:last').val(item.page);
            break;
        case 'page':
            if (level == 0) {
                $('#addPage').click();
            } else {
                $('.addPage:last').click();
            }
            $('.pageName:last').val(item.name);
            $('.pagePage:last').dropdown('set selected', item.page);
            if(typeof item.parameters != "undefined") {
                item.parameters.forEach(function (parameter) {
                    $('.addGet:last').click();
                    $('.getVarName:last').val(parameter.name)
                    $('.getVarValue:last').val(parameter.value)
                })
            }
            break;
        default:
            break;
    }
}