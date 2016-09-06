function inputLimiter(e) {

}
function allLetter(e) {
  var AllowableCharacters='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  var k = document.all?parseInt(e.keyCode): parseInt(e.which);
  if (k!=13 && k!=8 && k!=0){
      if ((e.ctrlKey==false) && (e.altKey==false)) {
      return (AllowableCharacters.indexOf(String.fromCharCode(k))!=-1);
      } else {
      return true;
      }
  } else {
      return true;
  }
}
function numeric(e){
  var AllowableCharacters='0123456789';
  var k = document.all?parseInt(e.keyCode): parseInt(e.which);
  if (k!=13 && k!=8 && k!=0){
      if ((e.ctrlKey==false) && (e.altKey==false)) {
      return (AllowableCharacters.indexOf(String.fromCharCode(k))!=-1);
      } else {
      return true;
      }
  } else {
      return true;
  }
}
function textAlphanumeric(e){
  var AllowableCharacters='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var k = document.all?parseInt(e.keyCode): parseInt(e.which);
  if (k!=13 && k!=8 && k!=0){
      if ((e.ctrlKey==false) && (e.altKey==false)) {
      return (AllowableCharacters.indexOf(String.fromCharCode(k))!=-1);
      } else {
      return true;
      }
  } else {
      return true;
  }
}
function lengthDefine(inputtxt, min, max){
  var uInput = inputtxt.value;
  if(uInput.length >= min && uInput.length <= max){
    return true;
  }
  else{
    return false;
  }
}
function emailValidation(e){
  var AllowableCharacters='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.@_-';
  var k = document.all?parseInt(e.keyCode): parseInt(e.which);
  if (k!=13 && k!=8 && k!=0){
      if ((e.ctrlKey==false) && (e.altKey==false)) {
      return (AllowableCharacters.indexOf(String.fromCharCode(k))!=-1);
      } else {
      return true;
      }
  } else {
      return true;
  }
}
