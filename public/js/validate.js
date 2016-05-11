function isEmail(email){
    var pattern = /^([a-zA-Z0-9]+[-_.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[-_.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,6}$/;
    //console.log( pattern.test(email) + ":" + email);
    return pattern.test(email);
}

function isLengthMoreThan(pwd, length) {
    pwd = pwd.trim();
    //console.log(pwd.length >= length);
    return pwd.length >= length;
}