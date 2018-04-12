var reCaptchaOnloadCallback = function() {
  var recaptches = document.getElementsByClassName('g-recaptcha');
  for( var i = 0; i < recaptches.length; i++ )
  {
    if( recaptches[i].dataset.sitekey == '' )
      continue;
    
    grecaptcha.render( recaptches[i].id , {
      'sitekey' : recaptches[i].dataset.sitekey,
      'theme': recaptches[i].dataset.theme,
      'size': recaptches[i].dataset.size,
      'type': recaptches[i].dataset.type,
    });
  }
};