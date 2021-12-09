#include "stdafx.h"
#include "librsa.h"

/** \brief Preforms i^j mod n
 *
 * \param i long int
 * \param j long int
 * \param n long int
 * \details The following is an algorithm I discovered reading "Applied Cryptography" by Bruce Schneier.
 * Pseudocode was provided and I translated it into c.\n
 * Preforms i^j mod n using modular exponentiation and more specificly uses exponentiation by squaring\n
 * It is implemented as binary exponentiation to speed the process up\n
 * This algorithm scales very nicely with larger numbers
 * \return long int i^j mod n
 */
long int RSA::modpow(long int i, long int j,long int n)
{

    long int result = 1;

    while (j > 0)   //if exponent is still positive
    {
        if ((j & 1) == 1)   //if the exponent is odd
            result = (result * i) % n;  //multiply in this bits' contribution while using modulus to keep result small
        j >>= 1;    //using a bit shift divides exponent by power of two
        i = (i * i) % n;    //square base and mod with n to keep i small
    }

    return result;
}

/** \brief Finds the gcd between 2 numbers using a recursive Euclid algorithm
 *
 * \param i int first number
 * \param j int second number
 * \return int gcd of i and j
 */
int RSA::gcd(int i, int j)
{
    if (j == 0) //If j == 0 then the gcd of the two numbers is i
        return i;
    else
        return gcd(j, i % j);   //Euclid's algorithm gcd(a, b) = gcd(b, a - b(a/b)
}

/** \brief Given seed, will find next prime smaller than seed
 *
 * \param seed int Where genPrime() starts it's search for a prime
 * \return int A prime number
 */
int RSA::genPrime(int seed)
{
    int i = 2;  //divide tester
    while (i <= (int)sqrt((float)seed))    //fails when prime is found
    {
        if (seed%i == 0)    //if seed is divisable by i then seed is not a prime
        {
            seed-=2; //reduce seed
            i = 2;  //reset tester
            continue;
        }
        i++;
    }
    return seed;
}

/** \brief Given string pt[] will encode each char in pt[] to an integer and store in pte[]
 *
 * \param pt[] char Plaintext string
 * \param pte[] int Where encodePlain() stores the encoded plaintext
 * \note Stores -1 as sentinel value at end of pte[]
 * \return void
 *
 */
void RSA::encodePlain( char pt[], int pte[] , int size )
{
    int i;
    for (i = 0; i < size; i++)
        pte[i] = pt[i];
    pte[i] = -1;
}

/** \brief Given array pte[] will decode each integer to a char and store in string pt[]
 *
 * \param pt[] char Where decodetoPlain stores the plaintext
 * \param pte[] int Encoded plaintext
 * \note Watches pte[] for sentinel value of -1
 * \return void
 *
 */
void RSA::decodetoPlain( char pt[], int pte[] ,int size )
{
    int i;
    for (i = 0; i < size; i++)
        pt[i] = pte[i];
    return;
}

/** \brief Pick a e at random that satisfies gcd(e,n) == 1
 *
 * \param p int Prime p
 * \param q int Prime q
 * \note returns -1 on failure (won't ever happen because of infinite loop)
 * \return int e where gcd(e,n) == 1
 *
 */
int RSA::picke(int p, int q)
{
    int e;

    while (1)
    {
        e = rand()%30 + 3;  //pick a random e between 3 & 17
        if (!(e&1)) continue;   //bit check to test if e is odd. continues loop if even
        if (gcd(p*q, e) == 1)   //check gcd of n and e == 1
            return e;
    }
    return -1;
}

/** \brief Finds a d such that d*e == 1 mod phi
 *
 * \param e int Private Exponent
 * \param phi int Totient of n
 * \note if no d can be found a -1 is returned to signal failure
 * \return int d where d*e == 1 mod phi
 * \todo Change to find d by modular inverse instead of (refined) bruteforce
 */
int RSA::findd(int e, int phi)
{
    int k;
    for (k = 1; k < 31; k++)
    {
        //printf("Trying %d for d, e=%d, p=%d, q=%d\n", (1 + phi*k)/e, e, q, p);   //debug
        if ((1 + phi*k)%e == 0) //This passes if (1 + phi*k) is cleanly divisable by e.
            {
                return (1 + phi*k)/e;   //(1 + phi*k)/e is the new d
            }
    }
    return -1;
}

/** \brief Finds the modular inverse of e and n; ex. used to find d exponent in RSA
 *
 * \param e unsigned int: Public exponent
 * \param n unsigned int: Public modulus
 * \return unsigned int: Returns the modular inverse of e and n; -1 on gcd error;
 *
 */
unsigned int RSA::modinv(int e, int n)
{
    unsigned int inverse = 0, last_e = 1, e2, n1 = 0, n2, temp1, temp2, quotient;
    int iter;

    /**< Step 1 */
    e2 = e; /**< Store e and n into variables so we can keep original values */
    n2 = n;
    iter = 1; /**< Remember odd/even iterations to ensure a positive result */

    /**< Step 2 */
    while (n2 != 0) /**< Keep looping while n != 0 */
    {
        /**< Step 3 */
        quotient = e2 / n2; /**< Divide */
        temp2 = e2 % n2; /**< and Subtract */
        temp1 = last_e + quotient * n1;

        last_e = n1; n1 = temp1; e2 = n2; n2 = temp2; /**< Swap */
        iter = -iter; /**< Flip iter to signal odd/even */
    }
    /**< Once n is 0 e is the gcd of e and n*/
    if (e2 != 1) /**< gcd(e,n) must be 1 for the modular inverse to exist */
        return -1;   /**< Return -1 to signal gcd error */

    if (iter < 0) /**< If iter is negative we must subtract from n to find the inverse to use */
        inverse = n - last_e;
    else
        inverse = last_e;

    return inverse;
}

void RSA::GenRSAKey(RSA_PRIVATE_KEY *pri_key, RSA_PUBLIC_KEY *pub_key)
{
    int i, phi;
    pri_key->d = -1;    //set d to it's sentinel value

    while (pri_key->d < 0)
    {
        pri_key->p = 0; //clear p
        while ((pri_key->p)*(pri_key->q) < 255 || pri_key->p == pri_key->q) //Get p and q if n < 255 or p == q
        {
            pri_key->p = rand()%51+75;  //Get seed for genPrime from 75 to 125
            pri_key->p |= 1 << 0; //sets the lowest bit of seed to a 1 to ensure a odd number
            pri_key->p = genPrime(pri_key->p);  //pass seed to genPrime to find next lowest prime
            pri_key->q = rand()%51+75;
            pri_key->q |= 1 << 0; //sets the lowest bit to a 1 to ensure a odd number
            pri_key->q = genPrime(pri_key->q);
        }

        pri_key->n = (pri_key->p)*(pri_key->q); //set n
        phi = (pri_key->p - 1)*(pri_key->q - 1);    //set phi

        for (i = 0; i < 6; i++) //Only allow 5 guesses here before new primes are picked
        {
            pri_key->d = -1;
            pri_key->e = picke(pri_key->p, pri_key->q); //pick a e
            pri_key->d = findd(pri_key->e, phi);    //find a d
            if (pri_key->d > 0) //break from loop if a valid d is found matching with p,q, and e
                break;
        }
    }
    pub_key->d = pri_key->d;    //store public key exponent
    pub_key->n = pri_key->n;    //store public key modulus
    return;
}

/** \brief Preforms RSA encryption on plaintext m using exponent e and modulus n\n
 * m^e mod n
 *
 * \param m int Plaintext
 * \param e int Encryption Exponent
 * \param n int Modulus
 * \return int Ciphertext
 *
 */
int RSA::encode(int m, int e, int n)
{
    int c;

	m &= 0x000000ff;

    c = modpow(m, e, n);
    return c;
}

/** \brief Preforms RSA decryption on ciphertext c using exponent d and modulus n
 * c^d mod n
 *
 * \param c int Ciphertext
 * \param d int Private Exponent
 * \param n int Modulus
 * \return int  Plaintext
 *
 */
int RSA::decode(int c, int d, int n)
{
    int m;
    m = modpow(c, d, n);
    return m;
}

/** \brief Encrypt a plaintext string using RSA encryption and stores it
 *
 * \param pt[] char Plaintext
 * \param ct[] int Where ciphertext gets stored
 * \param e int Public Exponent
 * \param n int Modulus
 * \return void
 *
 */
void RSA::RSAEncrypt( char pt[], int ct[], int e, int n,int size )
{
    int i;
    int *pte = new int[size+1];

    encodePlain(pt, pte,size);

    for (i = 0; i < size; i++) //loop thru pte member
        ct[i] = encode(pte[i], e, n); //and RSA encrypt

	delete pte;

    return;
}

/** \brief Decrypt a plaintext string using RSA decryption and stores it
 *
 * \param pt[] char Where plaintext gets stored
 * \param ct[] int Ciphertext
 * \param d int Private exponent
 * \param n int Modulus
 * \return void
 *
 */
void RSA::RSADecrypt( char pt[], int ct[], int d, int n, int size )
{
    int i;
	int *pte = new int[size+1];

    for (i = 0; i < size; i++)
        pte[i] = decode(ct[i], d, n);
  
    decodetoPlain(pt, pte,size);

	delete pte;

    return;
}
