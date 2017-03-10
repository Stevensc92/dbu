$(document).ready(function() {
    $('.mp_input_pseudo').autocomplete({
        source: 'autocomplete.php',
        dataType: 'json'
    });
});