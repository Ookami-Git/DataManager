//------ COMMON ------
$(document).ready(function() {
    /* Désactive la touche ENTREE pour valider le formulaire */


    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'h' && e.repeat == false) {
            e.preventDefault();
            // Place your code here
            $('.ui.modal.longer.helper').modal('show');
        }
    });

    window.onbeforeunload = function(){
        if ($( "#save" ).length >= 1 && !$( "#save" ).hasClass( "disabled" )) {
            return 'Leave without save?';
        }
    };
});

$(document).on('keyup change','input, checkbox, select',function(){
    $('#save').removeClass('disabled');
    $(this).closest('.field').removeClass("error");
});

function save() {
    //Verify if all required field is set
    $(".required").filter(function() {
        if ($(this).find('input').val() == "" && ! /.*:skip/i.test($(this).find('input').attr('name'))) {
            toast_msg='Champ : '+$(this).closest('.field').find('label:first').text()+'<br>'+$(this).find('input').attr('name');
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
    var data = $('form :input[value!=""]').serializeJSON({skipFalsyValuesForTypes: ["string", "null"],checkboxUncheckedValue: "false"})
    //Set parameters and url
    var path=window.location.pathname;
    path = path.split('/');
    var type = path[path.length - 1];
    var descName = path[path.length - 2];
    var theUrl;
    let params = new URLSearchParams();
    params.set("name",descName);
    params.set("value",JSON.stringify(data));
    params.set("type",type);
    params.set(type,JSON.stringify(data));
    theUrl=`${document.baseUrl}/api/datamanager/${descName}/${type}`;
    //PREPARE AND EXECUTE REQUEST
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.params = params;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 404) {
            // CREATE NEW ENTRY
            var xmlhttpCreate = new XMLHttpRequest();
            xmlhttpCreate.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 201) {
                    $('body').toast({
                        position: 'bottom attached',
                        displayTime: 3000,
                        title: 'Enregistré',
                        message: 'Vos modifications ont bien été enregistrés',
                        showProgress: 'top',
                        class: 'center aligned success',
                        newestOnTop: true
                    });
                    $('#save').addClass('disabled');
               }
               if (this.readyState == 4 && this.status != 201) {
                    $('body').toast({
                        position: 'bottom attached',
                        displayTime: 10000,
                        title: 'Enregistré',
                        message: `Création - Vos modifications n'ont pas pu être enregistrées.<br>Code Retour : ${this.status}<br>Erreur : ${this.response}`,
                        showProgress: 'top',
                        class: 'center aligned error',
                        newestOnTop: true
                    });
               }
            };
            xmlhttpCreate.open("POST", `${document.baseUrl}/api/datamanager`);
            xmlhttpCreate.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttpCreate.send(this.params);
        }
        if (this.readyState == 4 && this.status == 200) {
            // UPDATE ENTRY
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
            xmlhttpUpdate.send(this.params);
        }
    };
    xmlhttp.open("GET", `${document.baseUrl}/api/datamanager/${descName}/name`);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
}

function displayParameters(item,parent,paraBase) {
    var type = item.closest(parent).find('.typeSelector:first').dropdown('get value');
    var Type = type.charAt(0).toUpperCase() + type.slice(1);

    if (item.closest(parent).find(`.displayParameter${Type}`).length == 0) {
        genericAdd(item,".dmItem","parameters",`#tpl_parameters_${type}`);
    }

    var parameter = item.closest(parent).find(`.${paraBase}`);

    for( i=0; i< parameter.length; i++ ) {
        var childDiv = parameter[i];
        if (childDiv.classList.contains(`${paraBase}${Type}`)) {
            childDiv.style.display = "block";
            var inputs = $(childDiv).find('input');
            for( x=0; x< inputs.length; x++ ) {
                var input = inputs[x];
                var name = $(input).attr('name');
                $(input).attr("name",name.replace(':skip',''));
            }
        } else {
            childDiv.style.display = "none";
            var inputs = $(childDiv).find('input');
            for( x=0; x< inputs.length; x++ ) {
                var input = inputs[x];
                var name = $(input).attr('name');
                if (!/.*:skip/i.test(name)) {
                    $(input).attr("name",name+":skip");
                }
            }
        }
    }
}

//----- SOURCES --------
function addSource(data = null) {
    var div_tpl=$("#tpl_dmSource")[0].innerHTML;
    divSrc=$("#sources").append(div_tpl);
    divSrc=divSrc.find('.dmSource:last');

    if (data !== null) {
        divSrc.find('.sourceName').val(data.name);
        divSrc.find('.typeSelector').dropdown('set selected', data.type);
        if ("fromLoop" in data) {
            divSrc.find('.loopBase').val(data.fromLoop.base);
        }
    }
    loadParameters(divSrc,data);
}

function loadParameters(div,data) {
    var parentDiv,type;
    if (div[0].classList.contains("dmSource")) {
        parentDiv = div;
    } else {
        parentDiv = div.closest('.dmSource');
    }
    type = parentDiv.find('.typeSelector').dropdown('get value');
    returnInput(type,parentDiv,data);
}

function returnInput(type,divSrc,data) {
    var theUrl = `${document.baseUrl}/api/type/${type}`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.divSrc = divSrc;
    xmlhttp.data = data;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var x, divParameters, data;
            x = this;
            data = x.data;
            divParameters = x.divSrc.find('parameters');
            divParameters.empty();
            typeDesc=JSON.parse(this.response)
            for (const [key, value] of Object.entries(typeDesc)) {
                //type
                var divTpl=$(`#tpl_input_${value.type}`)[0].innerHTML;
                var divInsert = divParameters.append(divTpl);
                //name for export
                divInsert.find('input:last').attr('name', `sources[][parameters][${key}]` );
                //label
                if ("label" in value) {
                    divInsert.find('label:last').text(value.label);
                } else {
                    divInsert.find('label:last').text(key);
                }
                //require
                if ("require" in value) {
                    if (value.require == true) {
                        divInsert.find('.field:last').addClass('required');
                    }
                }
                //data
                if (data != null) {
                    if (key in data.parameters) {
                        if (value.type == "boolean") {
                            divInsert.find('input:last').attr('name', divInsert.find('input:last').attr('name')+":boolean");
                            if (data.parameters[key]) {
                                divInsert.find('input:last').attr( 'checked', true );
                            } else {
                                divInsert.find('input:last').attr( 'checked', false );
                            }
                        } else {
                            divInsert.find('input:last').val(data.parameters[key]);
                        }
                    }
                } else {
                    //default
                    if ('default' in value) {
                        if (value.type == "boolean") {
                            if (value.default) {
                                divInsert.find('input:last').attr( 'checked', true );
                            } else {
                                divInsert.find('input:last').attr( 'checked', false );
                            }
                        } else {
                            divInsert.find('input:last').val(value.default);
                        }
                    }
                }
            }
            $('.checkbox').checkbox();
        }
        if (this.readyState == 4 && this.status != 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 30000,
                title: 'Erreur',
                message: "Impossible de charger les champs pour "+type,
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

function readSource(name) {
    var theUrl = `${document.baseUrl}/api/datamanager/${name}/source`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            description=JSON.parse(this.response)
            description.sources.forEach(function(source){
                addSource(source);
            });
            $('#save').addClass('disabled');
        }
        if (this.readyState == 4 && this.status != 200 && this.status != 404) {
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

//------- ITEMS -------
function readFilters(loopItem) {
    loopItem.forEach(function(filter){
        if (typeof filter.regex !== 'undefined') {
            $(".btnAddFilterRegex:last").click();
            $(".filterRegex:last").val(filter.regex);
        }
        if (typeof filter.condition !== 'undefined') {
            $(".btnAddFilterCond:last").click();
            $(".filterCondition:last").val(filter.condition);
            $(".filterValue:last").val(filter.value);
        }
        $(".filterData:last").val(filter.data);
        $(".filterExpected:last").val(`${filter.expected}`);
    })
}

function getHelp() {
    var source = $("#selectSourceHelp").val();
    var path=window.location.pathname;
    path = path.split('/');
    var descName = path[path.length - 2];
    var theUrl = `${document.baseUrl}/helper/${descName}/${source}`;
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#sourceHelper").empty();
            $("#sourceHelper").append(this.responseText);
        }
        if (this.readyState == 4 && this.status != 200) {
            $('body').toast({
                position: 'bottom attached',
                displayTime: 30000,
                title: 'Erreur',
                message: "Impossible de charger l'aide pour "+source,
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

function readItem(name) {
    var theUrl = `${document.baseUrl}/api/datamanager/${name}/item`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            description=JSON.parse(this.response)
            description.items.forEach(function(item){
                $(".btnAddItem:last").click();
                $(".itemName:last").val(item.title.label).change();
                $(".itemColor:last").val(item.title.color).change();
                if (item.title.hide) {$(".itemHideRibbon:last").attr( 'checked', true );}
                $(".typeSelector:last").dropdown('set selected', item.type).change();

                if (item.type == "table") {
                    $(".itemLoopBase:last").val(item.parameters.loopBase);
                    if (typeof item.parameters.filters !== 'undefined') {
                        readFilters(item.parameters.filters);
                    }
                }
                if (item.type == "text") {
                    $(".itemTextarea:last").val(item.print);
                    if (item.parameters.segment) {$(".textSegment:last").attr( 'checked', true );}
                }
                if (typeof item.display !== 'undefined') {
                    item.display.forEach(function (display) {
                        $(".btnAddDisplay:last").click();
                        $(".fieldName:last").val(display.label).change();
                        $(".displayPrintDefault:last").val(display.print.default);
                        if (display.collapse) {$(".displayCollapse:last").attr( 'checked', true );}
                        $(".displayIcon:last").val(display.icon);
                        if (typeof display.print.conditional !== 'undefined') {
                            display.print.conditional.forEach(function(cdisplay) {
                                $(".btnAddCDisplay:last").click();
                                $(".displayPrintConditional:last").val(cdisplay.print);
                                if (typeof cdisplay.conditions !== 'undefined') {
                                    readFilters(cdisplay.conditions);
                                }
                            }) 
                        }
                    })
                }
            });
            $('#save').addClass('disabled');
        }
        if (this.readyState == 4 && this.status != 200 && this.status != 404) {
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

//------- SELECT ------
function deletepage(button) {
    var name = button.closest('tr').find('.descName').text();
    $('body').toast({
        message: 'Supprimer '+name+' ?',
        displayTime: 0,
        actions:    [{
            text: 'Oui',
            icon: 'trash',
            class: 'red',
            click: function() {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        button.closest('tr').remove();
                        $('body').toast({message:'Page '+name+' supprimée'});
                   }
                   if (this.readyState == 4 && this.status == 500) {
                        $('body').toast({
                            position: 'bottom attached',
                            displayTime: 30000,
                            title: 'Erreur',
                            message: "Impossible de supprimer l'entrée "+name,
                            showProgress: 'top',
                            class: 'center aligned error',
                            newestOnTop: true
                        });
                   }
                };
                var theUrl = `${document.baseUrl}/api/datamanager/${name}`;
                xmlhttp.open("DELETE", theUrl);
                xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xmlhttp.send();
            }
            },{
            text: 'Non',
            class: 'blue',
            click: function() {
                $('body').toast({message:'Annulation de la suppression'});
            }
        }]
    })
    ;
}

//--- PRESENTATION ---
function readPresentation(name) {
    var theUrl = `${document.baseUrl}/api/datamanager/${name}/presentation`;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            description=JSON.parse(JSON.stringify(this.response))
            description.presentation.forEach(function(presentation){
                $(".btnAddPresentation:last").click();
                $(".typeSelector:last").dropdown('set selected', presentation.type).change();
                $(".presLabel:last:visible").val(presentation.parameters.label);
                $(".presIcon:last:visible").val(presentation.parameters.icon);
                $(".presColumn:last:visible").val(presentation.parameters.column);
                $(".pageSelector:last:visible").val(presentation.parameters.page);
                if (presentation.parameters.segment) {$(".pageSegment:last").attr( 'checked', true );}
                if (typeof presentation.parameters.items !== 'undefined') {
                    presentation.parameters.items.forEach(function (item) {
                        $(".btnAddItem:last").click();
                        $(".presItemSelector:last").dropdown('set selected', item);
                    })
                }
            });
            $('#save').addClass('disabled');
        }
        if (this.readyState == 4 && this.status != 200 && this.status != 404) {
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