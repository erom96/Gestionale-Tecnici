$(document).on("change","#filterstato", function() {
        
    var val = this.value;

    $.ajax({         
        type:       "POST",
        url:        "/Controllers/jsrouter.php",
        data:       "filter="+val,
        dataType:   "html",
        success:    function(msg)
        {
            $("#interventiAdmin").html(msg);
        },
        error: function(msg)
        {
            alert("Errore durante la richiesta");
        }           
    });       
});
    
var serverTime = new Date();
var options = {
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    second: 'numeric'
};

function updateTime() {
    // Incrementa serverTime di 1 secondo e aggiorna l'html per l'id #time
    serverTime = new Date(serverTime.getTime() + 1000);
    $('#time').html(serverTime.toLocaleString('it-IT', options));
}

$(function() {
    updateTime();
    setInterval(updateTime, 1000);
});
    
$(document).on("click","tbody td#listaInterventi a", function(){

    var val = $(this).attr("id");

    $.ajax({
        type:       "POST",
        url:        "/Controllers/jsrouter.php",
        data:       "showid="+val,
        dataType:   "html",
        success:    function(msg)
        {
            $("#modalFade").modal('show');
            $("#modalDialog").html(msg);
        },
        error: function(msg)
        {
            alert("Errore durante la richiesta");
        }
    });
});
    
$(document).on("click","button#prendiIntervento", function(){

    var val = $(this).attr("value");

    $.ajax({
        type:       "POST",
        url:        "/Controllers/jsrouter.php",
        data:       "prendiIntervento="+val,
        dataType:   "html",
        success:    function(msg)
        {
            var data = JSON.parse(msg);
            $("button").remove("#prendiIntervento");
            $( "b#tecnicoGrassetto" ).replaceWith( "<b>"+data.nomeUtente+"</b>" );
            $("tbody#interventiTecniciAdd").append("<tr>"+$("tr#intervento"+val).html()+"</tr>");
            $("tr#intervento"+val).remove();
            $("div#messaggioAssegnaIntervento").fadeIn(300);
            $("div#messaggioAssegnaIntervento").fadeOut(2000);
        },
        error: function(msg)
        {
            alert("Errore durante la richiesta");
        }
    });
});
    
$(document).on("click","button#terminaIntervento", function(){

    var val = $(this).attr("value");

    $.ajax({
        type:       "POST",
        url:        "/Controllers/jsrouter.php",
        data:       "terminaIntervento="+val,
        dataType:   "html",
        success:    function(msg)
        {
            var data = JSON.parse(msg);
            $("button").remove("#terminaIntervento");
            $( "b#terminatoIl" ).replaceWith( "Terminato il <b>"+data.dataTermine+"</b>" );
            $("tr#intervento"+val).remove();
            $("div#messaggioTerminaIntervento").fadeIn(300);
            $("div#messaggioTerminaIntervento").fadeOut(2000);
        },
        error: function(msg)
        {
            alert("Errore durante la richiesta");
        }
    });
});
    
$(document).on("click", "button#creaCommento", function (e) {
    
    e.preventDefault();

    var val = $(this).attr("value");  
    var post_data = $('#datiCommento').serialize();

    if (!$.trim($("#messaggioTextArea").val())) {
        alert("Inserisci un messaggio!");
        return;
    }
              
    $.ajax({
        type:       "POST",
        url:        "/Controllers/jsrouter.php",
        data:       "creaCommento="+val+"&"+post_data,
        dataType:   "html",
        success:    function(msg)
        {
            $("div#commentoInviato").fadeIn(300);
            $("div#commentoInviato").fadeOut(2000);
            $("textarea#messaggioTextArea").val('');
            $("div#sezioneCommenti").prepend(msg);
        },
        error: function(msg)
        {
            alert("Errore durante la richiesta");
        }
    });
});


