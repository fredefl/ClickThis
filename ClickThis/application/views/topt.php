<html>
<head>
  <link rel="shortcut icon" href="https://code.google.com/p/google-authenticator/logo" type="image/png">
  <title>TOTP Debugger</title>
  <script>
 
 function repeat(pattern, count) {
    if (count < 1) return '';
    var result = '';
    while (count > 0) {
        if (count & 1) result += pattern;
        count >>= 1, pattern += pattern;
    };
    return result;
};
// Given a secret key "K" and a timestamp "t" (in 30s units since the
// beginning of the epoch), return a TOTP code.
function totp(K,t) {
  function T(x){var y=0x100000000;return(y+(x|0))%y;}
  function sha1(C){
    function L(x,b){return T(x<<b|x>>>32-b);}
    var l=C.length,D=C.concat([T(1<<31)]),V=0x67452301,W=0xEFCDAB89,
        X=0x98BADCFE,Y=0x10325476,Z=0xC3D2E1F0;
    do D.push(0);while(D.length+1&15);D.push(32*l);
    while (D.length){
      var E=D.splice(0,16),a=V,b=W,c=X,d=Y,e=Z,f,i=12;
      function I(x){var t=T(L(a,5)+f+e+k+E[x]);e=d;d=c;c=L(b,30);b=a;a=t;}
      for(;++i<77;)E.push(L(E[i]^E[i-5]^E[i-11]^E[i-13],1));
      k=0x5A827999;for(i=0;i<20;I(i++))f=T(b&c|~b&d);
      k=0x6ED9EBA1;for(;i<40;I(i++))f=T(b^c^d);
      k=0x8F1BBCDC;for(;i<60;I(i++))f=T(b&c|b&d|c&d);
      k=0xCA62C1D6;for(;i<80;I(i++))f=T(b^c^d);
      V=T(V+a);W=T(W+b);X=T(X+c);Y=T(Y+d);Z=T(Z+e);}
    return[V,W,X,Y,Z];
  }
  var k=[],l=[],i=0,j=0,c=0;
  for (;i<K.length;){
    c=c*32+'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'.
      indexOf(K.charAt(i++).toUpperCase());
    if((j+=5)>31)k.push(T(Math.floor(c/(1<<(j-=32))))),c&=31;}
  j&&k.push(c*(1<<(j-32)));while(k.length<16)k.push(0);
  for(i=0;i<16;++i)l.push(T(0x6A6A6A6A^(k[i]=T(k[i]^0x5C5C5C5C))));
  var s=sha1(k.concat(sha1(l.concat([0,t])))),o=s[4]&0xF;
  return ((s[o>>2]<<8*(o&3)|(o&3?s[(o>>2)+1]>>>8*(4-o&3):0))&-1>>>1)%1000000;
}

// Periodically check whether we need to update the UI. It would be a little
// more efficient to only call this function as a direct result of
// significant state changes. But polling is cheap, and keeps the code a
// little easier.
var lastcode,lastlabel,lastsearch;
function refresh() {
  var Secret = "GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ";
  // Compute current TOTP code
  var k=Secret.
    replace(/[^ABCDEFGHIJKLMNOPQRSTUVWXYZ234567]/gi, '');
  var t=Math.floor(new Date().getTime()/30000);
  var s="";
  if (s) t=parseInt(s);
  var code=totp(k,t);
  var label="";
  var search="";

  // If TOTP code has changed (either because of user edits, or
  // because of elapsed time), update the user interface.
  if (code != lastcode || label != lastlabel || search != lastsearch) {
    lastcode=code;
    lastlabel=label;
    lastsearch=search;

    // Compute the OTPAuth URL and the associated QR code
    var h='https://www.google.com/chart?chs=200x200&chld=M|0&'+
      'cht=qr&chl=otpauth://totp/'+encodeURI(label)+'%3Fsecret%3D'+k;

	if(code.length > 6) {
		code = repeat('0',	6-code.length) + code;
	}
    // Show the current TOTP code.
    document.getElementById('code').innerHTML=code;

    // If the user manually entered a TOTP code, try to find a matching code
    // within a 25h window.
    var result='';
    if (search && !!(search=parseInt(search))) {
      for (var i=0; i < 25*120; ++i) {
        if (search == totp(k, t+(i&1?-Math.floor(i/2):Math.floor(i/2)))) {
          if (i<2) {
            result='&nbsp;';
            break;
          }
          if (i >= 120) {
            result=result + Math.floor(i/120) + 'h ';
            i%=120;
          }
          if (i >= 4) {
            result=result + Math.floor(i/4) + 'min ';
            i%=4;
          }
          if (i&2) {
            result=result + '30s ';
          }
          if (i&1) {
            result='Code was valid ' + result + 'ago';
          } else {
            result='Code will be valid in ' + result;
          }
          break;
        }
      }
      if (!result) {
        result='No such code within a &#177;12h window';
      }
    }
  }
}
</script>
</head>
<body style="font-family: sans-serif" onLoad="setInterval(refresh, 100)">
  <h1>TOTP Debugger</h1>
    Code is: <span id="code"></span>
  <br />
</body>
</html>

