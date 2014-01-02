/* anti click droit */
function ejs_nodroit()
{
	return(false);
}
document.oncontextmenu = ejs_nodroit;

/* on bloque la touche entrée */
function refuserToucheEntree()
{
	// IE
	if(keyCode == 13) {
	returnValue = false;
	cancelBubble = true;
	}
	// DOM
	if(which == 13) {
	preventDefault();
	stopPropagation();
	}
}

function surligne(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "#a6c2d0";
}

// Fonction de désactivation de l'affichage des « tooltips »   
function deactivateTooltips()
{   
	var spans = document.getElementsByTagName('span'),
	spansLength = spans.length;

	for (var i = 0 ; i < spansLength ; i++)
	{
		if (spans[i].className == 'tooltip')
		{
			spans[i].style.display = 'none';
		}
	}
}

// La fonction ci-dessous permet de récupérer la « tooltip » qui correspond à notre input
    
function getTooltip(element)
{
	while (element = element.nextSibling)
	{
		if (element.className === 'tooltip') {
			return element;
		}
	}
	return false;
}


/* verif du champs pseudo */
function verifPseudo(champ)
{
	var login = document.getElementById('login');
	tooltipStyle = getTooltip(login).style;
    
   if(champ.value.length < 5 || champ.value.length > 30)
   {
      tooltipStyle.display = 'inline-block';
	  $("#valider").html('');
      surligne(champ, true);
      return false;
   }
   else
   {
      tooltipStyle.display = 'none';
      surligne(champ, false);
      return true;
   }
}

/* verif du champs mdp */
function verifPass(champ)
{
	var pass = document.getElementById('password');
	tooltipStyle = getTooltip(pass).style;
	
   if(champ.value.length < 10 || champ.value.length > 30)
   {
      tooltipStyle.display = 'inline-block';
	  $("#valider").html('');
      surligne(champ, true);
      return false;
   }
   else
   {
      tooltipStyle.display = 'none';
      surligne(champ, false);
      return true;
   }
}

/* verif du champs mail */
function verifMail(champ)
{
   var mail = document.getElementById('email');
   tooltipStyle = getTooltip(mail).style;
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   if(!regex.test(champ.value))
   {
      tooltipStyle.display = 'inline-block';
	  $("#valider").html('');
      surligne(champ, true);
      return false;
   }
   else
   {
      tooltipStyle.display = 'none';
      surligne(champ, false);
      return true;
   }
}

/* verif du champs sexe */
function verifSex(champ)
{
   var sexe = document.getElementById('sex');
   tooltipStyle = getTooltip(sexe).style;
	if (sexe.options[sexe.selectedIndex].value != '*') {
	  tooltipStyle.display = 'none';
      surligne(champ, false);
      return true;
	} else {
	  tooltipStyle.display = 'inline-block';
	  $("#valider").html('');
      surligne(champ, true);
      return false;
	}
}
$("input")
.change(function(){
var $input = $( this );
	if($input.prop("checked")===true)
	{
	$("#submit").attr("disabled",false);
	}
	else
	{
	$("#submit").attr("disabled",true);
	}
})
.change();

function verifForm(f)
{
   var pseudoOk = verifPseudo(f.pseudo);
   var mailOk = verifMail(f.email);

	if(pseudoOk && mailOk)
	{
		alert(pseudoOk);
		return true;
	}
	else
	{
		alert("Veuillez remplir correctement tous les champs");
		return false;
	}
}

deactivateTooltips();