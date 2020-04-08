$(function() {
	$('#remind').hide();
});

function checkEmail () {
    var email = $('#email').val();
    console.info(email);
    if (email == '') {
        $('#remind').show();
    } else {
        $('#remind').hide();
    }
}

function sub() {
    var pass = true;
    var email = $('#email').val();
    if (email.trim() == '') {
        $('#remind').show();
        pass = false;
        return false;
    } else {
        $('#remind').hide();
    }

    var name = $('#name').val();
    if (name.trim() == '') {
        pass = false;
        return false;
    }
    if (pass) {


        return false;
    }
}


