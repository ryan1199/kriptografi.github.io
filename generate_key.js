//file ini untuk pembuatan kunci RSA

function generate_key() {
    var primes = [];
    primes = primeFactorsTo(99);
    while(true) {
        var primes_length = primes.length;
        var p = primes[Math.floor(Math.random() * primes_length)];
        var q = primes[Math.floor(Math.random() * primes_length)];
        var Phi = (p - 1) * (q - 1);
        var e = Math.floor(Math.random() * Phi);
        var gcd = gcd_two_numbers(e,Phi);
        if(p != q && gcd == 1 && Phi > e && e > 1) {
            var d = modInverse(e, Phi);
            var n = p * q;
            document.forms["key_form"]["prime_p"].value = p;
            document.forms["key_form"]["prime_q"].value = q;
            document.forms["key_form"]["d"].value = d;
            document.forms["key_form"]["e"].value = e;
            document.forms["key_form"]["n"].value = n;
            break;
        }
    }
}

function primeFactorsTo(max)
{
    var store  = [], i, j, primes = [];
    for (i = 2; i <= max; ++i) 
    {
        if (!store [i]) 
          {
            primes.push(i);
            for (j = i << 1; j <= max; j += i) 
            {
                store[j] = true;
            }
        }
    }
    return primes;
}

function gcd_two_numbers(x, y) {
    if ((typeof x !== 'number') || (typeof y !== 'number')) 
      return false;
    x = Math.abs(x);
    y = Math.abs(y);
    while(y) {
      var t = y;
      y = x % y;
      x = t;
    }
    return x;
}

function modInverse(a, m)
{
    for(let x = 1; x < m; x++)
        if (((a % m) * (x % m)) % m == 1)
            return x;
}