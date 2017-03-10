function modifierMessage(idMess, token)
{
	var message = document.getElementById('m_'+idMess);

	var save_text = message.innerHTML;
	save_text = save_text.replace(/<br>/ig, '');

	var new_text = '<form method="post" action="#">'+
						'<input type="hidden" value="'+idMess+'" name="idPost" />'+
						'<input type="hidden" value="'+token+'" name="__token" />'+
						'<textarea name="message" style="max-width:850px; min-width:850px; min-height:50px; max-height:350px;" rows="10" cols="100">'+save_text+'</textarea><br/>'+
						'<input type="submit" name="upMessage" value="Modifier" class="button"/> <a href="./?view=topic&amp;id='+idMess+'" style="text-decoration: none;"><input type="button" value="Annuler" /></a>'+
					'</form>';

	message.innerHTML = new_text;

	var test = document.getElementById('inputUpdate');

	// On modifie le lien en retirant l'évènement onclick pour éviter les abus

	test.innerHTML = '<a href="#p_'+idMess+'" class="button">Modifier</a>';
}