function lancer()
{
	document.getElementById("combat").style.display="none";
	document.getElementById("read-fight").style.display="block";
}

function nextRound(round)
{
	var CurrentRound = document.getElementById('read-fight'+round);
	var NextRoundChiffre = round +1;
	var NextRound = document.getElementById('read-fight'+NextRoundChiffre);
	var Fin = document.getElementById('tableau');

	if (round < 7)
	{
		CurrentRound.style.display="none";
		NextRound.style.display="block";
	}
	else
	{
		Fin.style.display="block";
	}
}