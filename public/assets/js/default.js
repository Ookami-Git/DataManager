//Code au chargement de la page
window.onload = function() {
    //Active les menu dropdown
    $('.ui.dropdown').dropdown();
    //Active les checkbox
    $('.ui.checkbox').checkbox();
    //Activer le tri
    $('table.sortable').tablesort();

    //Charge la page par défaut
    labelcolor();

    var elementExists = document.getElementsByClassName("tablesearch");
    if (elementExists.length > 0) { 
        document.getElementById("searchengine").style.display = ""; 
    }
    var elementExists = document.getElementsByClassName("tableexport");
    if (elementExists.length > 0) {
        for (i = 0; i < elementExists.length; i++) {
            var div_tpl=$("#tpl_itemMenuExport")[0].innerHTML;
            $("#menuExportTable").append(div_tpl);
            $(".exportTitle:last").text(elementExists[i].id);
        }
        document.getElementById("btnExport").style.display = ""; 
    }
    var elementExists = document.getElementsByClassName("tablevisibility");
    if (elementExists.length > 0) { 
        document.getElementById("divVisibility").style.display = ""; 
    }
    if (document.getElementsByClassName("calendarreload").length > 0) {
        var queryParams = new URLSearchParams(window.location.search);
        var selected_date = null;
        if (queryParams.has("date")) {
            selected_date=new Date(parseInt(queryParams.get("date")));
        }
        $('#divCalendar').calendar({type: 'date', onChange: function() {
            url=window.location.href;
            selected_date=Date.parse($(this).calendar('get date'));
            if (selected_date !== "NaN") {
                // Construct URLSearchParams object instance from current URL querystring.
                var queryParams = new URLSearchParams(window.location.search);
                // Set new or modify existing parameter value. 
                queryParams.set("date", selected_date);
                location.search=queryParams.toString();
                //history.pushState(null, null, "?"+queryParams.toString());
            }
        }, initialDate: selected_date, today: true, firstDayOfWeek:1,text: {
            days: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Déc'],
            today: 'Aujourd\'hui'}});
        document.getElementById("divCalendar").style.display = ""; 
    }
}

//Affiche ou cache tous les tr avac la class trhide
function tr_visibility(classid) {
    var i;
    tr_selected = document.getElementsByClassName(classid);
    btn = document.getElementById("btnVisibility");
    for (i = 0; i < tr_selected.length; i++) {
        tr_selected[i].classList.toggle("trhide");
        tr_selected[i].classList.toggle("active");
        icon=tr_selected[i].getElementsByClassName("tr_visibility");
        icon[0].classList.toggle("slash");
        if (tr_selected[i].classList.contains("trhide")) {
            if (!btn.classList.contains("visiblehide")) {
                tr_selected[i].style.display = "none";
            }
        } else {
            tr_selected[i].style.display = "table-row";
        }
    }
    // Compatibilité avec searchfilter
    if (document.getElementsByClassName("tablesearch")) {searchFilter();}
}

//Affiche ou cache les tr
function toggle_visibility(reinit=false) {
    var i;
    btn = document.getElementById("btnVisibility");
    if (!reinit) {
        btn.classList.toggle("visiblehide");
        btn.classList.toggle("blue");
    }
    document.getElementById("iconVisibility").classList.toggle("slash");;
    tr_selected = document.getElementsByClassName("trhide");
    for (i = 0; i < tr_selected.length; i++) {
        if (btn.classList.contains("visiblehide")) {
            tr_selected[i].style.display = "table-row";
        } else {
            tr_selected[i].style.display = "none";
        }
    }
    // Compatibilité avec searchfilter
    if (document.getElementsByClassName("tablesearch") && !reinit) {searchFilter();}
}

//Cercle de couleur - Utilisateurs
function labelcolor() {
    SelectedElement = document.getElementsByClassName('label');
    for (t = 0; t < SelectedElement.length; t++) {
        if (SelectedElement[t].classList.contains("colorA") || SelectedElement[t].classList.contains("colorN")) {
            SelectedElement[t].classList.add("red");
        }
        if (SelectedElement[t].classList.contains("colorB") || SelectedElement[t].classList.contains("colorO")) {
            SelectedElement[t].classList.add("orange");
        }
        if (SelectedElement[t].classList.contains("colorC") || SelectedElement[t].classList.contains("colorP")) {
            SelectedElement[t].classList.add("yellow");
        }
        if (SelectedElement[t].classList.contains("colorD") || SelectedElement[t].classList.contains("colorQ")) {
            SelectedElement[t].classList.add("olive");
        }
        if (SelectedElement[t].classList.contains("colorE") || SelectedElement[t].classList.contains("colorR")) {
            SelectedElement[t].classList.add("green");
        }
        if (SelectedElement[t].classList.contains("colorF") || SelectedElement[t].classList.contains("colorS")) {
            SelectedElement[t].classList.add("teal");
        }
        if (SelectedElement[t].classList.contains("colorG") || SelectedElement[t].classList.contains("colorT")) {
            SelectedElement[t].classList.add("blue");
        }
        if (SelectedElement[t].classList.contains("colorH") || SelectedElement[t].classList.contains("colorU")) {
            SelectedElement[t].classList.add("violet");
        }
        if (SelectedElement[t].classList.contains("colorI") || SelectedElement[t].classList.contains("colorV")) {
            SelectedElement[t].classList.add("purple");
        }
        if (SelectedElement[t].classList.contains("colorJ") || SelectedElement[t].classList.contains("colorW")) {
            SelectedElement[t].classList.add("pink");
        }
        if (SelectedElement[t].classList.contains("colorK") || SelectedElement[t].classList.contains("colorX")) {
            SelectedElement[t].classList.add("brown");
        }
        if (SelectedElement[t].classList.contains("colorL") || SelectedElement[t].classList.contains("colorY")) {
            SelectedElement[t].classList.add("grey");
        }
        if (SelectedElement[t].classList.contains("colorM") || SelectedElement[t].classList.contains("colorZ")) {
            SelectedElement[t].classList.add("black");
        }
    }
}

function searchFilter() {
    var input, filter, table, tr, td, i;
    input = $(".searchbox:last:visible");
    filter = input.val().toUpperCase();
    table = document.getElementsByClassName("tablesearch");
    for (t = 0; t < table.length; t++) {
        tr = table[t].getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (x = 0; x < td.length; x++) {
                td_selected = tr[i].getElementsByTagName("td")[x];
                if (td_selected.textContent.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "table-row";
                    break;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    //Compatibilité avec la fonction visibility
    if (document.getElementsByClassName("tablevisibility")) {
        if (! input.value ) {toggle_visibility(true);}
    }
}